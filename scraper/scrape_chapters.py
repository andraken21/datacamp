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
        'value': 'nSdRtRhyKvesgG2ixLRz',
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
    driver.get('https://campus.datacamp.com')
    time.sleep(3)
    driver.delete_all_cookies()
    driver.execute_cdp_cmd('Network.setCookies', {'cookies': COOKIES})

    driver.get('https://campus.datacamp.com/courses/introduction-to-sql/relational-databases?ex=1')
    time.sleep(6)

    if 'login' in driver.current_url or 'signin' in driver.current_url:
        print("✗ GAGAL LOGIN — update cookie dulu")
        driver.quit()
        exit(1)

    print(f"✓ Login berhasil — {driver.current_url}\n")
    driver.refresh()
    time.sleep(3)
    return driver


def scrape_chapters(driver, course_id, course_slug):
    url = f"https://campus.datacamp.com/courses/{course_slug}"
    driver.get(url)
    time.sleep(8)

    chapters = []

    try:
        WebDriverWait(driver, 15).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, 'a[href*="/courses/"]'))
        )

        links = driver.find_elements(By.CSS_SELECTOR, 'a[href*="/courses/"]')
        seen_slugs = set()
        order = 1

        for link in links:
            href = link.get_attribute('href') or ''
            # hapus prefix lokalisasi /id/ sebelum match
            href_clean = href.replace('/id/', '/')
            match = re.search(
                rf'/courses/{re.escape(course_slug)}/([a-z0-9-]+)',
                href_clean
            )
            if match:
                chapter_slug = match.group(1)
                if chapter_slug not in seen_slugs:
                    seen_slugs.add(chapter_slug)
                    title = link.text.strip() or chapter_slug
                    chapters.append({
                        'slug': chapter_slug,
                        'title': title,
                        'order': order
                    })
                    order += 1

    except Exception as e:
        print(f"    ⚠ Error scrape chapters: {e}")

    return chapters


def save_chapters(cursor, conn, course_id, chapters):
    slug_to_id = {}
    for ch in chapters:
        cursor.execute(
            "SELECT id FROM chapters WHERE course_id = %s AND slug = %s",
            (course_id, ch['slug'])
        )
        row = cursor.fetchone()
        if row:
            slug_to_id[ch['slug']] = row[0]
        else:
            cursor.execute("""
                INSERT INTO chapters (course_id, title, slug, `order`, created_at, updated_at)
                VALUES (%s, %s, %s, %s, %s, %s)
            """, (course_id, ch['title'], ch['slug'], ch['order'], datetime.now(), datetime.now()))
            conn.commit()
            slug_to_id[ch['slug']] = cursor.lastrowid
            print(f"    + Chapter: {ch['slug']} (order {ch['order']})")
    return slug_to_id


def assign_chapters_to_lessons(cursor, conn, course_id, course_slug, slug_to_id, driver):
    cursor.execute("""
        SELECT id, `order` FROM lessons
        WHERE course_id = %s AND chapter_id IS NULL
        ORDER BY `order`
    """, (course_id,))
    lessons = cursor.fetchall()

    if not lessons:
        print("    ℹ Semua lesson sudah punya chapter_id")
        return

    print(f"    → Assign chapter ke {len(lessons)} lessons...")

    for lesson_id, order in lessons:
        url = f"https://campus.datacamp.com/courses/{course_slug}?ex={order}"
        driver.get(url)
        time.sleep(4)

        final_url = driver.current_url
        # hapus prefix lokalisasi /id/ sebelum match
        match = re.search(
            rf'/courses/{re.escape(course_slug)}/([a-z0-9-]+)',
            final_url.replace('/id/', '/')
        )
        if match:
            chapter_slug = match.group(1)
            chapter_id = slug_to_id.get(chapter_slug)
            if chapter_id:
                cursor.execute(
                    "UPDATE lessons SET chapter_id = %s WHERE id = %s",
                    (chapter_id, lesson_id)
                )
                conn.commit()
                print(f"      lesson {lesson_id} (ex={order}) → {chapter_slug}")
            else:
                print(f"      ⚠ lesson {lesson_id} — chapter '{chapter_slug}' tidak ada di DB")
        else:
            print(f"      ⚠ lesson {lesson_id} — tidak bisa ekstrak chapter dari: {final_url}")

        time.sleep(1)


def main():
    print("=" * 55)
    print("  DataCamp — Scrape Chapters & Assign ke Lessons")
    print("=" * 55)

    conn = get_db_connection()
    cursor = conn.cursor()

    cursor.execute("SELECT id, title, slug FROM courses ORDER BY id")
    courses = cursor.fetchall()
    print(f"Total courses: {len(courses)}\n")

    driver = setup_driver()
    inject_cookies(driver)

    try:
        for i, (course_id, course_title, course_slug) in enumerate(courses):

            # Restart driver setiap 50 course biar tidak crash
            if i > 0 and i % 50 == 0:
                print("\n→ Restart driver...\n")
                try:
                    driver.quit()
                except Exception:
                    pass
                driver = setup_driver()
                inject_cookies(driver)

            print(f"[{i+1}/{len(courses)}] {course_title} ({course_slug})")

            # Scrape chapters dengan error handling
            try:
                chapters = scrape_chapters(driver, course_id, course_slug)
            except Exception as e:
                print(f"    ✗ Driver error: {e} — restart driver")
                try:
                    driver.quit()
                except Exception:
                    pass
                driver = setup_driver()
                inject_cookies(driver)
                chapters = []

            if not chapters:
                print(f"    ⚠ Tidak ada chapter ditemukan, skip")
                continue

            print(f"    ✓ {len(chapters)} chapter ditemukan")

            slug_to_id = save_chapters(cursor, conn, course_id, chapters)

            try:
                assign_chapters_to_lessons(cursor, conn, course_id, course_slug, slug_to_id, driver)
            except Exception as e:
                print(f"    ✗ Error assign: {e}")

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
        print("\n✅ Selesai!")


if __name__ == '__main__':
    main()