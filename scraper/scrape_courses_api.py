import time
import pymysql
import requests

# ============================================================
DB_CONFIG = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'database': 'datacamp',
    'charset': 'utf8mb4'
}

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    'Accept': 'application/json',
    'Referer': 'https://www.datacamp.com',
}
# ============================================================


def get_courses_from_db(conn):
    cursor = conn.cursor()
    cursor.execute("""
        SELECT course_id, slug FROM courses 
        WHERE duration_hours = 0 AND slug IS NOT NULL AND slug != ''
        ORDER BY course_id
        LIMIT 583
    """)
    rows = cursor.fetchall()
    cursor.close()
    return rows


def scrape_course(slug):
    """Coba ambil data course dari DataCamp API"""
    url = f"https://www.datacamp.com/api/courses/{slug}"
    try:
        resp = requests.get(url, headers=HEADERS, timeout=10)
        if resp.status_code == 200:
            return resp.json()
    except Exception:
        pass

    # Coba endpoint alternatif
    url2 = f"https://app.datacamp.com/learn/courses/{slug}"
    try:
        resp2 = requests.get(url2, headers=HEADERS, timeout=10)
        if resp2.status_code == 200:
            return {'html': resp2.text[:2000]}
    except Exception:
        pass

    return None


def update_course(conn, course_id, data):
    cursor = conn.cursor()
    try:
        # Coba parse dari JSON API
        duration = data.get('estimated_hours') or data.get('duration_hours') or 0
        videos    = data.get('videos_count') or data.get('num_videos') or 0
        exercises = data.get('exercises_count') or data.get('num_exercises') or 0
        desc      = data.get('description') or data.get('short_description') or ''
        learners  = data.get('participants_count') or data.get('students_count') or 0

        if duration or videos or exercises or desc:
            cursor.execute("""
                UPDATE courses SET
                    duration_hours = CASE WHEN %s > 0 THEN %s ELSE duration_hours END,
                    jumlah_video   = CASE WHEN %s > 0 THEN %s ELSE jumlah_video END,
                    jumlah_latihan = CASE WHEN %s > 0 THEN %s ELSE jumlah_latihan END,
                    deskripsi      = CASE WHEN %s != '' THEN %s ELSE deskripsi END,
                    total_learners = CASE WHEN %s > 0 THEN %s ELSE total_learners END
                WHERE course_id = %s
            """, (
                duration, duration,
                videos, videos,
                exercises, exercises,
                desc, desc,
                learners, learners,
                course_id
            ))
            conn.commit()
            return True
    except Exception as e:
        print(f"  DB error: {e}")
        conn.rollback()
    finally:
        cursor.close()
    return False


def main():
    print("=" * 55)
    print("  DataCamp Courses Scraper (API)")
    print("=" * 55)

    conn = pymysql.connect(**DB_CONFIG)
    courses = get_courses_from_db(conn)
    print(f"Total courses to scrape: {len(courses)}")

    success = 0
    failed  = 0

    for i, (course_id, slug) in enumerate(courses):
        print(f"[{i+1}/{len(courses)}] {slug}", end=" ")

        data = scrape_course(slug)
        if data and isinstance(data, dict) and 'html' not in data:
            ok = update_course(conn, course_id, data)
            if ok:
                print("✓")
                success += 1
            else:
                print("- (no useful data)")
                failed += 1
        else:
            print("✗ (API failed)")
            failed += 1

        # Jangan terlalu cepat
        if i % 10 == 9:
            time.sleep(2)
        else:
            time.sleep(0.3)

    conn.close()
    print(f"\n✅ Selesai! Success: {success}, Failed: {failed}")


if __name__ == '__main__':
    main()