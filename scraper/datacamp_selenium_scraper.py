from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import pymysql
import time
from datetime import datetime

conn = pymysql.connect(
    host='127.0.0.1',
    port=3306,
    user='root',
    password='',
    database='datacamp',
    charset='utf8mb4'
)
cursor = conn.cursor()

def setup_driver():
    options = webdriver.ChromeOptions()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--disable-blink-features=AutomationControlled')
    options.add_experimental_option('excludeSwitches', ['enable-automation'])
    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
    return driver

def inject_cookies(driver):
    driver.get('https://www.datacamp.com')
    time.sleep(2)
    
    cookies = [
        {'name': 'dc_logged_in', 'value': '1', 'domain': '.datacamp.com'},
        {'name': 'authentication_token', 'value': 'nSdRtRhyKvesgG2ixLRz', 'domain': '.datacamp.com'},
        {'name': '_dct', 'value': 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og', 'domain': '.datacamp.com'},
    ]
    
    for cookie in cookies:
        try:
            driver.add_cookie(cookie)
        except:
            pass
    
    driver.refresh()
    time.sleep(3)
    print("✓ Cookie injected")

def scrape_exercise(driver, url, ex_type):
    try:
        driver.get(url)
        time.sleep(4)

        result = {
            'instructions': None,
            'sample_code': None,
            'transcript': None,
            'video_url': None,
        }

        # === INSTRUCTIONS ===
        try:
            selectors = [
                '[data-cy="exercise-assignment-text"]',
                '.exercise-assignment-description',
                '[class*="Assignment"] p',
                '[class*="instructions"] p',
                '.dc-panel__body',
            ]
            for sel in selectors:
                els = driver.find_elements(By.CSS_SELECTOR, sel)
                if els:
                    text = '\n'.join([e.text for e in els if e.text.strip()])
                    if text:
                        result['instructions'] = text[:2000]
                        break
        except:
            pass

        # === SAMPLE CODE ===
        try:
            selectors = [
                '.monaco-editor .view-lines',
                '[data-cy="code-editor"] .view-lines',
                '.CodeMirror-code',
            ]
            for sel in selectors:
                els = driver.find_elements(By.CSS_SELECTOR, sel)
                if els:
                    text = '\n'.join([e.text for e in els if e.text.strip()])
                    if text:
                        result['sample_code'] = text[:3000]
                        break
        except:
            pass

        # === TRANSCRIPT / VIDEO ===
        if ex_type == 'video':
            try:
                # Cari iframe video
                iframes = driver.find_elements(By.CSS_SELECTOR, 'iframe[src*="vimeo"], iframe[src*="youtube"], video')
                if iframes:
                    result['video_url'] = iframes[0].get_attribute('src')
                
                # Cari transcript
                selectors = [
                    '[class*="transcript"]',
                    '[data-cy="transcript"]',
                    '.video-description',
                ]
                for sel in selectors:
                    els = driver.find_elements(By.CSS_SELECTOR, sel)
                    if els:
                        text = '\n'.join([e.text for e in els if e.text.strip()])
                        if text:
                            result['transcript'] = text[:3000]
                            break
            except:
                pass

        return result

    except Exception as e:
        print(f"    ✗ Error: {e}")
        return None

def update_lesson(lesson_id, data):
    try:
        cursor.execute("""
            UPDATE lessons SET
                instructions = %s,
                sample_code = %s,
                transcript = %s,
                video_url = %s,
                updated_at = %s
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

def main():
    print("=== Selenium Lesson Scraper ===")

    cursor.execute("""
        SELECT l.id, l.title, l.`order`, l.type, c.slug
        FROM lessons l
        JOIN courses c ON l.course_id = c.id
        WHERE l.instructions IS NULL AND l.sample_code IS NULL
        ORDER BY c.id, l.`order`
        LIMIT 200
    """)
    lessons = cursor.fetchall()
    print(f"Total lessons belum ada konten: {len(lessons)}")

    driver = setup_driver()
    try:
        inject_cookies(driver)

        for i, (lesson_id, title, order, ex_type, slug) in enumerate(lessons):
            url = f"https://campus.datacamp.com/courses/{slug}?ex={order}"
            print(f"[{i+1}/{len(lessons)}] {slug} ex={order} ({ex_type})")

            data = scrape_exercise(driver, url, ex_type)
            if data:
                update_lesson(lesson_id, data)
                filled = sum(1 for v in data.values() if v)
                print(f"    ✓ {filled}/4 field terisi")

            time.sleep(2)

    finally:
        driver.quit()
        cursor.close()
        conn.close()
        print("\n✅ Selesai!")

if __name__ == '__main__':
    main()