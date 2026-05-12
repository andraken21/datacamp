from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import csv
import time
import re

OUTPUT_FILE = 'career_tracks.csv'

DCT_TOKEN = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og'

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
    driver.delete_all_cookies()
    driver.execute_cdp_cmd('Network.setCookies', {'cookies': [
        {'name': 'dc_logged_in', 'value': '1', 'domain': '.datacamp.com', 'path': '/'},
        {'name': 'authentication_token', 'value': 'nSdRtRhyKvesgG2ixLRz', 'domain': '.datacamp.com', 'path': '/'},
        {'name': '_dct', 'value': DCT_TOKEN, 'domain': '.datacamp.com', 'path': '/'},
    ]})
    driver.refresh()
    time.sleep(3)
    print("✓ Cookie injected")

def get_track_slugs(driver):
    print("→ Buka halaman career tracks...")
    driver.get('https://app.datacamp.com/learn/career-tracks')
    time.sleep(5)

    # Scroll ke bawah biar semua card load
    for _ in range(5):
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(1)

    slugs = []
    seen = set()

    links = driver.find_elements(By.CSS_SELECTOR, 'a[href*="/learn/career-tracks/"]')
    for link in links:
        href = link.get_attribute('href') or ''
        match = re.search(r'/learn/career-tracks/([a-z0-9-]+)', href)
        if match:
            slug = match.group(1)
            if slug not in seen:
                seen.add(slug)
                slugs.append(slug)

    print(f"✓ Ditemukan {len(slugs)} career tracks: {slugs}")
    return slugs

def scrape_track_detail(driver, slug):
    url = f"https://app.datacamp.com/learn/career-tracks/{slug}"
    driver.get(url)
    time.sleep(4)

    # Scroll biar semua konten load
    for _ in range(3):
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(1)
    driver.execute_script("window.scrollTo(0, 0);")
    time.sleep(1)

    data = {
        'slug': slug,
        'url': url,
        'name': '',
        'description': '',
        'duration_hours': '',
        'total_courses': '',
        'total_projects': '',
        'total_assessments': '',
        'total_participants': '',
        'courses': '',
        'projects': '',
        'instructors': '',
    }

    # === NAMA ===
    try:
        h1 = driver.find_element(By.CSS_SELECTOR, 'h1')
        data['name'] = h1.text.strip()
    except:
        pass

    # === DESKRIPSI ===
    try:
        selectors = [
            '[class*="description"]',
            '[class*="Description"]',
            '[class*="track-description"]',
        ]
        for sel in selectors:
            els = driver.find_elements(By.CSS_SELECTOR, sel)
            for el in els:
                text = el.text.strip()
                if text and len(text) > 50:
                    data['description'] = text[:500]
                    break
            if data['description']:
                break
    except:
        pass

    # === STATS dari teks halaman ===
    try:
        full_text = driver.find_element(By.TAG_NAME, 'body').text

        match = re.search(r'(\d+)\s*[Hh]ours?', full_text)
        if match:
            data['duration_hours'] = match.group(1)

        match = re.search(r'(\d+)\s*[Cc]ourses?', full_text)
        if match:
            data['total_courses'] = match.group(1)

        match = re.search(r'(\d+)\s*[Pp]rojects?', full_text)
        if match:
            data['total_projects'] = match.group(1)

        match = re.search(r'(\d+)\s*[Aa]ssessments?', full_text)
        if match:
            data['total_assessments'] = match.group(1)

        match = re.search(r'([\d,]+)\s*[Ll]earners?', full_text)
        if match:
            data['total_participants'] = match.group(1).replace(',', '')
    except:
        pass

    # === LIST KURSUS ===
    try:
        course_els = driver.find_elements(By.CSS_SELECTOR, '[class*="course"] h3, [class*="Course"] h3, [class*="syllabus"] h3')
        courses = [el.text.strip() for el in course_els if el.text.strip()]
        data['courses'] = ' | '.join(courses[:20])
    except:
        pass

    # === INSTRUKTUR ===
    try:
        instr_els = driver.find_elements(By.CSS_SELECTOR, '[class*="instructor"] [class*="name"], [class*="Instructor"] h3')
        instructors = [el.text.strip() for el in instr_els if el.text.strip()]
        data['instructors'] = ' | '.join(instructors[:10])
    except:
        pass

    return data

def save_to_csv(results):
    fieldnames = [
        'slug', 'url', 'name', 'description',
        'duration_hours', 'total_courses', 'total_projects',
        'total_assessments', 'total_participants',
        'courses', 'projects', 'instructors'
    ]
    with open(OUTPUT_FILE, 'w', newline='', encoding='utf-8-sig') as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(results)
    print(f"✓ Tersimpan ke {OUTPUT_FILE}")

def main():
    print("=" * 50)
    print("  DataCamp Career Tracks Scraper (Selenium)")
    print("=" * 50)

    driver = setup_driver()
    try:
        inject_cookies(driver)
        slugs = get_track_slugs(driver)

        if not slugs:
            print("✗ Tidak ada slug ditemukan!")
            return

        results = []
        for i, slug in enumerate(slugs):
            print(f"\n[{i+1}/{len(slugs)}] Scraping {slug}...")
            data = scrape_track_detail(driver, slug)
            results.append(data)
            print(f"  ✓ {data['name']} — {data['total_courses']} courses, {data['duration_hours']} jam")
            time.sleep(1)

        save_to_csv(results)
        print(f"\n✅ Selesai! {len(results)} career tracks tersimpan ke {OUTPUT_FILE}")

    finally:
        driver.quit()

if __name__ == '__main__':
    main()