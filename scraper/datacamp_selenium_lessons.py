import re
import argparse
from selenium import webdriver
import argparse
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import pymysql
import time
from datetime import datetime

# ============================================================
# KONFIGURASI
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

# ============================================================


def get_db_connection():
    return pymysql.connect(**DB_CONFIG)


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
    print("→ Inject cookies...")
    driver.get('https://campus.datacamp.com')
    time.sleep(3)
    driver.delete_all_cookies()
    driver.execute_cdp_cmd('Network.setCookies', {'cookies': COOKIES})

    driver.get('https://campus.datacamp.com/courses/introduction-to-sql/relational-databases?ex=1')
    time.sleep(8)

    if 'login' in driver.current_url or 'signin' in driver.current_url:
        print("✗ GAGAL LOGIN — update cookie dulu")
        driver.quit()
        exit(1)

    print(f"✓ Login berhasil\n")
    driver.refresh()
    time.sleep(3)
    return driver


def wait_for_page(driver, timeout=15):
    try:
        WebDriverWait(driver, timeout).until(
            lambda d: d.execute_script("return document.readyState") == "complete"
        )
        time.sleep(4)
    except Exception:
        pass


def scrape_lesson(driver, url, ex_type):
    try:
        driver.get(url)
        wait_for_page(driver)

        page_size = len(driver.page_source)
        if page_size < 8000:
            print(f"    ⚠ Halaman blank ({page_size} char)")
            return None

        result = {
            'instructions': None,
            'sample_code': None,
            'transcript': None,
            'video_url': None,
        }

        # ── INSTRUCTIONS ─────────────────────────────────────
        try:
            for sel in [
                '[data-cy="exercise-assignment-text"]',
                '[data-testid="assignment-text"]',
                '[class*="LessonContent"] p',
                '[class*="SlideContent"] p',
                '[class*="AssignmentText"] p',
                '[class*="ExerciseContent"] p',
                '[class*="ContentPanel"] p',
                '[class*="Assignment"] p',
                '[class*="instructions"] p',
                '.dc-panel__body p',
            ]:
                els = driver.find_elements(By.CSS_SELECTOR, sel)
                if els:
                    text = '\n'.join([e.text for e in els if e.text.strip()])
                    if text.strip():
                        result['instructions'] = text[:3000]
                        break
        except Exception as e:
            print(f"    ⚠ instructions error: {e}")

        # ── SAMPLE CODE ───────────────────────────────────────
        try:
            for sel in [
                '.monaco-editor .view-lines',
                '[data-cy="code-editor"] .view-lines',
                '[data-testid="code-editor"] .view-lines',
                '[class*="CodeEditor"] .view-lines',
                '.CodeMirror-code',
            ]:
                els = driver.find_elements(By.CSS_SELECTOR, sel)
                if els:
                    text = '\n'.join([e.text for e in els if e.text.strip()])
                    if text.strip():
                        result['sample_code'] = text[:3000]
                        break
        except Exception as e:
            print(f"    ⚠ sample_code error: {e}")

        # ── VIDEO & TRANSCRIPT ────────────────────────────────
        if ex_type == 'video':
            try:
                # Selector video — DataCamp pakai projector.datacamp.com
                for sel in [
                    'iframe[src*="projector.datacamp.com"]',
                    'iframe[src*="vimeo"]',
                    'iframe[src*="youtube"]',
                    'video[src]',
                ]:
                    els = driver.find_elements(By.CSS_SELECTOR, sel)
                    if els:
                        result['video_url'] = els[0].get_attribute('src')
                        break

                # Kalau iframe tidak punya src langsung, coba data-src atau cari dari page source
                if not result['video_url']:
                    match = re.search(
                        r'(https://projector\.datacamp\.com[^\s"\']+)',
                        driver.page_source
                    )
                    if match:
                        result['video_url'] = match.group(1)

                # Transcript — cari tombol Transkripsi lalu klik
                try:
                    transcript_btn = driver.find_elements(By.XPATH,
                        "//*[contains(text(), 'Transkripsi') or contains(text(), 'Transcript')]"
                    )
                    if transcript_btn:
                        transcript_btn[0].click()
                        time.sleep(2)
                except Exception:
                    pass

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

                # Fallback transcript — ambil teks panel kiri (AI Coach area)
                if not result['transcript']:
                    for sel in [
                        '[class*="LessonContent"]',
                        '[class*="SlideContent"]',
                        '[class*="ContentPanel"]',
                    ]:
                        els = driver.find_elements(By.CSS_SELECTOR, sel)
                        if els:
                            text = '\n'.join([e.text for e in els if e.text.strip()])
                            if text.strip():
                                result['transcript'] = text[:3000]
                                break

            except Exception as e:
                print(f"    ⚠ video error: {e}")

        return result

    except Exception as e:
        print(f"    ✗ Scrape error: {e}")
        return None


def update_lesson(cursor, conn, lesson_id, data):
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
    parser = argparse.ArgumentParser()
    parser.add_argument('--offset', type=int, default=0,   help='Mulai dari lesson ke-N')
    parser.add_argument('--limit',  type=int, default=100, help='Jumlah lesson per batch (default: 100)')
    args = parser.parse_args()

    print("=" * 55)
    print(f"  DataCamp Scraper — offset={args.offset} limit={args.limit}")
    print("=" * 55)

    conn = get_db_connection()
    cursor = conn.cursor()

    # Ambil lessons yang belum ada instruksi
    # Anti-redundan: WHERE instructions IS NULL memastikan tidak overwrite data yang sudah ada
    cursor.execute("""
    SELECT l.id, l.title, l.`order`, l.type, c.slug, lc.title as chapter_title
    FROM lessons l
    JOIN courses c ON l.course_id = c.course_id
    LEFT JOIN lesson_chapters lc ON l.chapter_id = lc.id
    WHERE l.instructions IS NULL
    ORDER BY c.course_id, l.`order`
    LIMIT %s OFFSET %s
""", (args.limit, args.offset))
    lessons = cursor.fetchall()

    print(f"Lessons di batch ini: {len(lessons)}")
    if not lessons:
        print("Tidak ada lesson yang perlu di-scrape di range ini.")
        cursor.close()
        conn.close()
        return

    driver = setup_driver()
    inject_cookies(driver)

    success = 0
    fail = 0

    try:
        print(f"Mulai scraping...\n")
        for i, (lesson_id, title, order, ex_type, course_slug, chapter_title) in enumerate(lessons):

            # Restart driver setiap 50 lesson biar tidak crash
            if i > 0 and i % 50 == 0:
                print("\n→ Restart driver...\n")
                try:
                    driver.quit()
                except Exception:
                    pass
                driver = setup_driver()
                inject_cookies(driver)

            # Build URL pakai chapter slug kalau ada
            url = f"https://campus.datacamp.com/courses/{course_slug}?ex={order}"
            print(f"[{i+1}/{len(lessons)}] {course_slug} ex={order} ({ex_type})")

            try:
                data = scrape_lesson(driver, url, ex_type)
            except Exception as e:
                print(f"    ✗ Driver error: {e} — restart")
                try:
                    driver.quit()
                except Exception:
                    pass
                driver = setup_driver()
                inject_cookies(driver)
                data = None

            if data:
                filled = sum(1 for v in data.values() if v)
                update_lesson(cursor, conn, lesson_id, data)
                print(f"    ✓ {filled}/4 field terisi {list(k for k,v in data.items() if v)}")
                if filled > 0:
                    success += 1
                else:
                    fail += 1
            else:
                print(f"    ✗ Gagal")
                fail += 1

            time.sleep(2)

    except KeyboardInterrupt:
        print("\n⚠ Dihentikan oleh user")
    finally:
        try:
            driver.quit()
        except Exception:
            pass
        cursor.close()
        conn.close()
        print(f"\n{'=' * 55}")
        print(f"  Berhasil : {success}")
        print(f"  Gagal    : {fail}")
        print(f"{'=' * 55}")
        if args.offset + args.limit < args.offset + len(lessons) + fail:
            next_offset = args.offset + args.limit
            print(f"\n  Lanjut batch berikutnya:")
            print(f"  python scraper/datacamp_selenium_lessons.py --offset {next_offset} --limit {args.limit}")


if __name__ == '__main__':
    main()