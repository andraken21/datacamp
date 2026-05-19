"""
Scraper Lessons DataCamp -> MySQL + CSV (BCNF)
=============================================
Struktur tabel lessons (BCNF):
  - lessons(id, course_id, chapter_id, title, content, video_url,
            duration_minutes, order, type, is_free_preview,
            created_at, updated_at)
  - lesson_chapters(id, course_id, title, order, created_at, updated_at)

Cara pakai:
  pip install requests beautifulsoup4 pymysql pandas
  python scrape_lessons.py
"""

import re
import json
import time
import requests
import pymysql
import pandas as pd
from datetime import datetime
from bs4 import BeautifulSoup

# ── CONFIG ────────────────────────────────────────────────────────────────────
DB_CONFIG = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'database': 'datacamp',
    'charset': 'utf8mb4',
}

CSV_LESSONS  = 'lessons_scraped.csv'
CSV_CHAPTERS = 'chapters_scraped.csv'

HEADERS = {
    'User-Agent': (
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '
        'AppleWebKit/537.36 (KHTML, like Gecko) '
        'Chrome/124.0.0.0 Safari/537.36'
    ),
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language': 'en-US,en;q=0.5',
}

DELAY_SECONDS = 2   # jeda antar request biar tidak di-ban

# ── BCNF DDL (jalankan sekali jika tabel belum ada) ──────────────────────────
DDL_CHAPTERS = """
CREATE TABLE IF NOT EXISTS lesson_chapters (
    id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    course_id    BIGINT UNSIGNED NOT NULL,
    title        VARCHAR(255)    NOT NULL,
    `order`      INT             NOT NULL DEFAULT 0,
    created_at   TIMESTAMP       NULL,
    updated_at   TIMESTAMP       NULL,
    PRIMARY KEY (id),
    KEY idx_lc_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
"""

# ── DB HELPERS ────────────────────────────────────────────────────────────────
def get_conn():
    return pymysql.connect(**DB_CONFIG)


def _column_exists(cur, table: str, column: str) -> bool:
    cur.execute(
        "SELECT COUNT(*) FROM information_schema.COLUMNS "
        "WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s",
        (DB_CONFIG['database'], table, column)
    )
    return cur.fetchone()[0] > 0


def _index_exists(cur, table: str, index: str) -> bool:
    cur.execute(
        "SELECT COUNT(*) FROM information_schema.STATISTICS "
        "WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND INDEX_NAME = %s",
        (DB_CONFIG['database'], table, index)
    )
    return cur.fetchone()[0] > 0


def ensure_schema():
    """Buat tabel lesson_chapters & kolom chapter_id jika belum ada (MySQL 5.7+)."""
    conn = get_conn()
    try:
        with conn.cursor() as cur:
            # 1. Buat tabel lesson_chapters
            cur.execute(DDL_CHAPTERS)

            # 2. Tambah kolom chapter_id ke lessons jika belum ada
            if not _column_exists(cur, 'lessons', 'chapter_id'):
                cur.execute(
                    "ALTER TABLE lessons "
                    "ADD COLUMN chapter_id BIGINT UNSIGNED NULL AFTER course_id"
                )

            # 3. Tambah index jika belum ada
            if not _index_exists(cur, 'lessons', 'idx_l_chapter'):
                cur.execute(
                    "ALTER TABLE lessons ADD KEY idx_l_chapter (chapter_id)"
                )

        conn.commit()
    finally:
        conn.close()


def get_all_courses():
    conn = get_conn()
    try:
        with conn.cursor(pymysql.cursors.DictCursor) as cur:
            cur.execute(
                "SELECT course_id, nama_course, slug "
                "FROM courses WHERE slug IS NOT NULL ORDER BY course_id"
            )
            return cur.fetchall()
    finally:
        conn.close()


def save_chapter(conn, course_id: int, title: str, order: int) -> int:
    """Insert chapter, return chapter_id."""
    now = datetime.now()
    with conn.cursor() as cur:
        cur.execute(
            "INSERT INTO lesson_chapters (course_id, title, `order`, created_at, updated_at) "
            "VALUES (%s, %s, %s, %s, %s)",
            (course_id, title[:255], order, now, now)
        )
        return cur.lastrowid


def save_lessons_to_db(course_id: int, chapters_data: list) -> tuple[int, int]:
    """
    chapters_data: [
      {
        'title': str,
        'order': int,
        'lessons': [
          {
            'title', 'content', 'video_url',
            'duration_minutes', 'order', 'type', 'is_free_preview'
          }
        ]
      }
    ]
    Return: (total_chapters_saved, total_lessons_saved)
    """
    if not chapters_data:
        return 0, 0

    conn = get_conn()
    chapters_saved = 0
    lessons_saved  = 0
    now = datetime.now()

    try:
        with conn.cursor() as cur:
            # Hapus data lama
            cur.execute(
                "DELETE lc FROM lesson_chapters lc WHERE lc.course_id = %s",
                (course_id,)
            )
            cur.execute(
                "DELETE FROM lessons WHERE course_id = %s",
                (course_id,)
            )

        for ch in chapters_data:
            chapter_id = save_chapter(conn, course_id, ch['title'], ch['order'])
            chapters_saved += 1

            with conn.cursor() as cur:
                for les in ch['lessons']:
                    cur.execute(
                        """
                        INSERT INTO lessons
                            (course_id, chapter_id, title, content, video_url,
                             duration_minutes, `order`, type, is_free_preview,
                             created_at, updated_at)
                        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
                        """,
                        (
                            course_id,
                            chapter_id,
                            les['title'][:255],
                            les['content'],
                            les['video_url'],
                            les['duration_minutes'],
                            les['order'],
                            les['type'],
                            1 if les['is_free_preview'] else 0,
                            now,
                            now,
                        )
                    )
                    lessons_saved += 1

        conn.commit()
    finally:
        conn.close()

    return chapters_saved, lessons_saved

# ── SCRAPER ───────────────────────────────────────────────────────────────────
def _parse_next_data(html: str) -> list:
    """
    Coba ambil data dari __NEXT_DATA__ JSON yang di-embed DataCamp.
    Return list chapter_data atau [] kalau gagal.
    """
    match = re.search(
        r'<script id="__NEXT_DATA__" type="application/json">(.*?)</script>',
        html, re.DOTALL
    )
    if not match:
        return []

    try:
        data = json.loads(match.group(1))
    except json.JSONDecodeError:
        return []

    chapters_raw = (
        data.get('props', {})
            .get('pageProps', {})
            .get('course', {})
            .get('chapters', [])
    )
    if not chapters_raw:
        return []

    chapters_data = []
    lesson_order  = 1

    for ch_order, chapter in enumerate(chapters_raw, 1):
        ch_title   = chapter.get('title', f'Chapter {ch_order}')
        exercises  = chapter.get('exercises', [])
        lessons    = []

        for ex in exercises:
            ex_type = ex.get('type', 'exercise').lower()
            if 'video' in ex_type:
                ltype = 'video'
            elif 'quiz' in ex_type or 'multiple' in ex_type:
                ltype = 'quiz'
            else:
                ltype = 'exercise'

            # video_url: DataCamp kadang taruh di field 'video_url' atau 'projector_key'
            video_url = (
                ex.get('video_url') or
                ex.get('projector_key') or
                None
            )
            if video_url and not video_url.startswith('http'):
                video_url = None  # bukan URL valid, abaikan

            xp = ex.get('xp', 100) or 100
            lessons.append({
                'title'           : ex.get('title', ch_title)[:255],
                'content'         : ex.get('description', '') or '',
                'video_url'       : video_url,
                'duration_minutes': max(1, xp // 20),  # estimasi: 100xp ~ 5 menit
                'order'           : lesson_order,
                'type'            : ltype,
                'is_free_preview' : lesson_order == 1,
            })
            lesson_order += 1

        # Kalau chapter tidak punya exercises → jadikan 1 lesson
        if not exercises:
            lessons.append({
                'title'           : ch_title,
                'content'         : chapter.get('description', '') or '',
                'video_url'       : None,
                'duration_minutes': 10,
                'order'           : lesson_order,
                'type'            : 'video',
                'is_free_preview' : lesson_order == 1,
            })
            lesson_order += 1

        chapters_data.append({
            'title'  : ch_title,
            'order'  : ch_order,
            'lessons': lessons,
        })

    return chapters_data


def _parse_html_fallback(html: str) -> list:
    """
    Fallback: parse HTML langsung kalau __NEXT_DATA__ tidak ada / kosong.
    """
    soup = BeautifulSoup(html, 'html.parser')
    chapters_data = []
    lesson_order  = 1
    ch_order      = 0

    # Cari elemen chapter (DataCamp pakai beberapa class berbeda antar waktu)
    chapter_els = soup.select(
        '[data-cy="chapter-title"], '
        '.css-1g7j2u1, '           # class lama
        'h4[class*="chapter"]'
    )

    for ch_el in chapter_els:
        ch_order += 1
        ch_title = ch_el.get_text(strip=True) or f'Chapter {ch_order}'
        lessons  = []

        # Cari lesson/exercise di bawah chapter ini
        container = ch_el.find_parent()
        if container:
            for ex_el in container.select(
                '[data-cy="exercise-title"], '
                'li[class*="exercise"], '
                'span[class*="lesson"]'
            ):
                ex_title = ex_el.get_text(strip=True)
                if not ex_title or len(ex_title) < 2:
                    continue
                lessons.append({
                    'title'           : ex_title[:255],
                    'content'         : '',
                    'video_url'       : None,
                    'duration_minutes': 5,
                    'order'           : lesson_order,
                    'type'            : 'exercise',
                    'is_free_preview' : lesson_order == 1,
                })
                lesson_order += 1

        # Kalau tidak ada lesson child, pakai chapter itu sendiri
        if not lessons:
            lessons.append({
                'title'           : ch_title,
                'content'         : '',
                'video_url'       : None,
                'duration_minutes': 10,
                'order'           : lesson_order,
                'type'            : 'video',
                'is_free_preview' : lesson_order == 1,
            })
            lesson_order += 1

        chapters_data.append({
            'title'  : ch_title,
            'order'  : ch_order,
            'lessons': lessons,
        })

    return chapters_data


def scrape_course(slug: str) -> list:
    """
    Scrape satu course. Return list chapter_data.
    """
    url = f"https://www.datacamp.com/courses/{slug}"

    try:
        resp = requests.get(url, headers=HEADERS, timeout=15)
    except requests.RequestException as e:
        print(f"    ✗ Request error: {e}")
        return []

    if resp.status_code != 200:
        print(f"    ✗ HTTP {resp.status_code}")
        return []

    chapters_data = _parse_next_data(resp.text)

    if not chapters_data:
        print("    ⚠ __NEXT_DATA__ kosong, coba fallback HTML parser…")
        chapters_data = _parse_html_fallback(resp.text)

    return chapters_data

# ── MAIN ──────────────────────────────────────────────────────────────────────
def main():
    sep = "=" * 60
    print(sep)
    print("  DataCamp Lesson Scraper  (BCNF Edition)")
    print(sep)

    print("\n⚙  Memastikan skema database (BCNF)…")
    ensure_schema()
    print("   ✓ Skema OK")

    courses = get_all_courses()
    print(f"\n📚 Total courses di DB: {len(courses)}\n")

    all_chapters_csv: list[dict] = []
    all_lessons_csv : list[dict] = []
    total_ch  = 0
    total_les = 0

    for i, course in enumerate(courses, 1):
        slug      = course['slug']
        course_id = course['course_id']
        nama      = course['nama_course']

        print(f"[{i:>3}/{len(courses)}] {nama}")
        print(f"         slug : {slug}")

        chapters_data = scrape_course(slug)

        if chapters_data:
            ch_saved, les_saved = save_lessons_to_db(course_id, chapters_data)
            total_ch  += ch_saved
            total_les += les_saved
            print(f"         ✓ {ch_saved} chapters | {les_saved} lessons disimpan")

            # Kumpulkan data untuk CSV
            for ch in chapters_data:
                all_chapters_csv.append({
                    'course_id'  : course_id,
                    'course_name': nama,
                    'slug'       : slug,
                    'chapter'    : ch['title'],
                    'ch_order'   : ch['order'],
                })
                for les in ch['lessons']:
                    all_lessons_csv.append({
                        'course_id'       : course_id,
                        'course_name'     : nama,
                        'slug'            : slug,
                        'chapter'         : ch['title'],
                        **les,
                    })
        else:
            print("         ⚠ Tidak ada data ditemukan (mungkin butuh login)")

        time.sleep(DELAY_SECONDS)

    # ── Simpan CSV ────────────────────────────────────────────
    if all_lessons_csv:
        pd.DataFrame(all_lessons_csv).to_csv(
            CSV_LESSONS, index=False, encoding='utf-8-sig'
        )
        print(f"\n💾 Lessons CSV  → {CSV_LESSONS}")

    if all_chapters_csv:
        pd.DataFrame(all_chapters_csv).to_csv(
            CSV_CHAPTERS, index=False, encoding='utf-8-sig'
        )
        print(f"💾 Chapters CSV → {CSV_CHAPTERS}")

    print(f"\n{sep}")
    print(f"  SELESAI!")
    print(f"  Total chapters : {total_ch}")
    print(f"  Total lessons  : {total_les}")
    print(sep)


if __name__ == '__main__':
    main()