import re
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import pymysql
import time
from datetime import datetime

# ============================================================
# KONFIGURASI — UPDATE BAGIAN INI SEBELUM DIJALANKAN
# ============================================================

COOKIES = [
    {
        'name': 'dc_logged_in',
        'value': '1',
        'domain': '.datacamp.com',
        'path': '/'
    },
    {
        'name': 'authentication_token',
        'value': 'nSdRtRhyKvesgG2ixLRz',        # ← ganti jika expired
        'domain': '.datacamp.com',
        'path': '/'
    },
    {
        'name': '_dct',
        'value': 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og',
        'domain': '.datacamp.com',
        'path': '/'
    },
]

DB_CONFIG = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'database': 'datacamp',
    'charset': 'utf8mb4'
}

# URL untuk verifikasi login
VERIFY_URL = 'https://optima.datacamp.com/introduction-to-sql/lessons/meet-databases-sql'

# ============================================================


def get_db_connection():
    return pymysql.connect(**DB_CONFIG)


def title_to_slug(title):
    """Konversi judul lesson ke slug URL.
    Contoh: 'What is A/B testing?' → 'what-is-ab-testing'
    """
    slug = title.lower()
    slug = re.sub(r'[^a-z0-9\s-]', '', slug)  # hapus karakter selain huruf, angka, spasi, dash
    slug = re.sub(r'\s+', '-', slug.strip())   # spasi → dash
    slug = re.sub(r'-+', '-', slug)            # double dash → single
    return slug


def build_url(course_slug, lesson_title):
    """Build URL optima.datacamp.com dari course slug dan lesson title."""
    lesson_slug = title_to_slug(lesson_title)
    return f"https://optima.datacamp.com/{course_slug}/lessons/{lesson_slug}"


def setup_driver():
    options = webdriver.ChromeOptions()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--disable-blink-features=AutomationControlled')
    options.add_experimental_option('excludeSwitches', ['enable-automation'])
    options.add_experimental_option('useAutomationExtension', False)
    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=options
    )
    driver.execute_script(
        "Object.defineProperty(navigator, 'webdriver', {get: () => undefined})"
    )
    return driver


def inject_cookies(driver):
    """Inject cookies ke optima.datacamp.com dan verifikasi login."""
    print("→ Membuka optima.datacamp.com...")
    driver.get('https://optima.datacamp.com')
    time.sleep(3)

    driver.delete_all_cookies()
    driver.execute_cdp_cmd('Network.setCookies', {'cookies': COOKIES})
    print("✓ Cookie diinjeksi")

    print(f"→ Verifikasi login...")
    driver.get(VERIFY_URL)
    time.sleep(6)

    current_url = driver.current_url
    page_size = len(driver.page_source)
    print(f"  URL     : {current_url}")
    print(f"  Halaman : {page_size:,} karakter")

    if 'login' in current_url or 'signin' in current_url or 'auth' in current_url:
        print("\n✗ GAGAL LOGIN — redirect ke halaman auth")
        print("  → Buka Chrome, login manual ke datacamp.com")
        print("  → F12 → Application → Cookies → copy _dct & authentication_token terbaru")
        driver.quit()
        exit(1)

    if page_size < 5000:
        print("⚠ Halaman terlalu kecil, cek browser...")
        input("Tekan ENTER jika sudah benar, Ctrl+C untuk batal: ")
    else:
        print("✓ Login berhasil!\n")

    driver.refresh()
    time.sleep(3)


def wait_for_content(driver, timeout=15):
    """Tunggu sampai konten halaman selesai dirender."""
    try:
        WebDriverWait(driver, timeout).until(
            lambda d: d.execute_script("return document.readyState") == "complete"
        )
        time.sleep(3)  # buffer tambahan untuk React render
    except Exception:
        pass


def scrape_lesson(driver, url, ex_type):
    """Scrape konten satu lesson dari optima.datacamp.com."""
    try:
        driver.get(url)
        wait_for_content(driver)

        page_size = len(driver.page_source)
        if page_size < 8000:
            print(f"    ⚠ Halaman terlalu kecil ({page_size} char) — slug mungkin salah")
            return None

        result = {
            'instructions': None,
            'sample_code': None,
            'transcript': None,
            'video_url': None,
        }

        # ── INSTRUCTIONS / KONTEN TEKS ──────────────────────────
        try:
            selectors = [
                # optima — panel kiri konten lesson
                '[class*="LessonContent"] p',
                '[class*="SlideContent"] p',
                '[class*="lesson-content"] p',
                '[class*="ExerciseContent"] p',
                '[class*="AssignmentText"] p',
                '[class*="ContentPanel"] p',
                # fallback
                '[data-cy="exercise-assignment-text"]',
                '[data-testid="assignment-text"]',
                '.exercise-assignment-description',
                '[class*="Assignment"] p',
                '[class*="instructions"] p',
                '.dc-panel__body p',
            ]
            for sel in selectors:
                els = driver.find_elements(By.CSS_SELECTOR, sel)
                if els:
                    text = '\n'.join([e.text for e in els if e.text.strip()])
                    if text.strip():
                        result['instructions'] = text[:3000]
                        break
        except Exception as e:
            print(f"    ⚠ Error instructions: {e}")

        # ── SAMPLE CODE ─────────────────────────────────────────
        try:
            selectors = [
                '.monaco-editor .view-lines',
                '[data-cy="code-editor"] .view-lines',
                '[data-testid="code-editor"] .view-lines',
                '[class*="CodeEditor"] .view-lines',
                '.CodeMirror-code',
            ]
            for sel in selectors:
                els = driver.find_elements(By.CSS_SELECTOR, sel)
                if els:
                    text = '\n'.join([e.text for e in els if e.text.strip()])
                    if text.strip():
                        result['sample_code'] = text[:3000]
                        break
        except Exception as e:
            print(f"    ⚠ Error sample_code: {e}")

        # ── VIDEO & TRANSCRIPT ───────────────────────────────────
        if ex_type == 'video':
            try:
                for sel in ['iframe[src*="projector.datacamp.com"]', 'iframe[src*="vimeo"]', 'iframe[src*="youtube"]', 'video[src]']:
                    els = driver.find_elements(By.CSS_SELECTOR, sel)
                    if els:
                        result['video_url'] = els[0].get_attribute('src')
                        break

                for sel in [
                    '[data-cy="transcript"]',
                    '[data-testid="transcript"]',
                    '[class*="Transcript"]',
                    '[class*="transcript"]',
                    '[class*="VideoTranscript"]',
                    '.video-description',
                ]:
                    els = driver.find_elements(By.CSS_SELECTOR, sel)
                    if els:
                        text = '\n'.join([e.text for e in els if e.text.strip()])
                        if text.strip():
                            result['transcript'] = text[:3000]
                            break
            except Exception as e:
                print(f"    ⚠ Error video/transcript: {e}")

        return result

    except Exception as e:
        print(f"    ✗ Error scrape: {e}")
        return None


def update_lesson(cursor, conn, lesson_id, data):
    """Simpan hasil scrape ke database."""
    try:
        cursor.execute("""
            UPDATE lessons SET
                instructions = %s,
                sample_code  = %s,
                transcript   = %s,
                video_url    = %s,
                updated_at   = %s
            WHERE id = %s
        """, (
            data['instructions'],
            data['sample_code'],
            data['transcript'],
            data['video_url'],
            datetime.now(),
            lesson_id
        ))
        conn.commit()
    except Exception as e:
        print(f"    ✗ DB error: {e}")
        conn.rollback()


def main():
    print("=" * 55)
    print("  DataCamp Scraper — optima.datacamp.com")
    print("=" * 55)

    conn = get_db_connection()
    cursor = conn.cursor()

    cursor.execute("""
        SELECT l.id, l.title, l.`order`, l.type, c.slug
        FROM lessons l
        JOIN courses c ON l.course_id = c.id
        WHERE l.instructions IS NULL
        ORDER BY c.id, l.`order`
        LIMIT 200
    """)
    lessons = cursor.fetchall()
    print(f"Total lessons belum ada konten: {len(lessons)}\n")

    if not lessons:
        print("Tidak ada lesson yang perlu di-scrape.")
        cursor.close()
        conn.close()
        return

    driver = setup_driver()
    success = 0
    slug_miss = 0
    fail = 0

    try:
        inject_cookies(driver)
        print(f"Mulai scraping {len(lessons)} lessons...\n")

        for i, (lesson_id, title, order, ex_type, course_slug) in enumerate(lessons):
            url = build_url(course_slug, title)
            lesson_slug = title_to_slug(title)
            print(f"[{i+1}/{len(lessons)}] {course_slug} / {lesson_slug} ({ex_type})")

            data = scrape_lesson(driver, url, ex_type)

            if data:
                filled = sum(1 for v in data.values() if v)
                if filled > 0:
                    update_lesson(cursor, conn, lesson_id, data)
                    print(f"    ✓ {filled}/4 field terisi")
                    success += 1
                else:
                    # Halaman load tapi tidak ada konten — slug kemungkinan salah
                    print(f"    ⚠ 0 field — cek manual: {url}")
                    slug_miss += 1
            else:
                fail += 1

            time.sleep(2)

    except KeyboardInterrupt:
        print("\n⚠ Dihentikan oleh user")
    finally:
        driver.quit()
        cursor.close()
        conn.close()
        print(f"\n{'=' * 55}")
        print(f"  Berhasil  : {success}")
        print(f"  Slug miss : {slug_miss}  ← buka URL di browser & cek format slug")
        print(f"  Error     : {fail}")
        print(f"{'=' * 55}")


if __name__ == '__main__':
    main()