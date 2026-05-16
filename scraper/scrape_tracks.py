import re
import csv
import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

# ============================================================
# KONFIGURASI
# ============================================================

CAREER_LIST_URL = 'https://app.datacamp.com/learn/career-tracks'
SKILL_LIST_URL  = 'https://app.datacamp.com/learn/skill-tracks'
CAREER_OUTPUT   = 'career_tracks.csv'
SKILL_OUTPUT    = 'skill_tracks.csv'

COOKIES = [
    {'name': 'dc_logged_in',         'value': '1',                    'domain': '.datacamp.com', 'path': '/'},
    {'name': 'authentication_token', 'value': 'nSdRtRhyKvesgG2ixLRz', 'domain': '.datacamp.com', 'path': '/'},
    {'name': '_dct',                 'value': 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og', 'domain': '.datacamp.com', 'path': '/'},
]

CSV_FIELDS = [
    'type', 'slug', 'url', 'name', 'description',
    'technology', 'duration_hours', 'total_courses',
    'total_projects', 'total_assessments', 'total_participants',
    'courses', 'projects',
]

# ============================================================


def setup_driver():
    options = webdriver.ChromeOptions()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--disable-blink-features=AutomationControlled')
    # Pakai ukuran layar besar supaya sidebar instruktur dan semua konten load
    options.add_argument('--window-size=1920,1080')
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
    driver.get('https://app.datacamp.com')
    time.sleep(3)
    driver.delete_all_cookies()
    driver.execute_cdp_cmd('Network.setCookies', {'cookies': COOKIES})
    driver.get('https://app.datacamp.com/learn/career-tracks')
    time.sleep(6)
    print(f"✓ Login — {driver.current_url}\n")


def wait_load(driver, timeout=15):
    try:
        WebDriverWait(driver, timeout).until(
            lambda d: d.execute_script("return document.readyState") == "complete"
        )
        time.sleep(4)
    except Exception:
        pass


def scroll_to_bottom(driver):
    last_height = 0
    for _ in range(20):
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(1.5)
        new_height = driver.execute_script("return document.body.scrollHeight")
        if new_height == last_height:
            break
        last_height = new_height


def get_slugs(driver, list_url, url_keyword):
    driver.get(list_url)
    wait_load(driver)
    scroll_to_bottom(driver)

    slugs = []
    seen = set()
    links = driver.find_elements(By.CSS_SELECTOR, f'a[href*="/{url_keyword}/"]')
    for link in links:
        href = link.get_attribute('href') or ''
        match = re.search(rf'/{url_keyword}/([a-z0-9-]+)', href)
        if match:
            slug = match.group(1)
            if slug not in seen:
                seen.add(slug)
                slugs.append(slug)
    return slugs


def scrape_track(driver, url, slug, track_type):
    driver.get(url)
    wait_load(driver)
    scroll_to_bottom(driver)

    data = {
        'type': track_type,
        'slug': slug,
        'url': url,
        'name': '',
        'description': '',
        'technology': '',
        'duration_hours': '',
        'total_courses': '',
        'total_projects': '',
        'total_assessments': '',
        'total_participants': '',
        'courses': '',
        'projects': '',
    }

    # ── NAMA ─────────────────────────────────────────────────
    try:
        h1 = driver.find_element(By.TAG_NAME, 'h1')
        data['name'] = h1.text.strip()
    except Exception:
        pass

    # ── DESKRIPSI ─────────────────────────────────────────────
    # Dikonfirmasi dari Console:
    # h3[data-waffles-component="heading"].nextElementSibling berisi deskripsi
    # tapi ada teks "Baca Selengkapnya" di akhir yang perlu di-trim
    try:
        headings = driver.find_elements(By.CSS_SELECTOR,
            'h3[data-waffles-component="heading"]'
        )
        for h in headings:
            if 'Deskripsi' in h.text or 'Description' in h.text:
                # Ambil sibling berikutnya
                sibling = driver.execute_script(
                    "return arguments[0].nextElementSibling;", h
                )
                if sibling:
                    raw = sibling.text.strip()
                    # Hapus "Baca Selengkapnya" dan teks tombol di akhir
                    desc = re.sub(r'\n?Baca Selengkapnya.*$', '', raw, flags=re.DOTALL).strip()
                    if len(desc) > 30:
                        data['description'] = desc
                        break
    except Exception as e:
        print(f"    ⚠ desc error: {e}")

    # ── STATS ─────────────────────────────────────────────────
    try:
        stat_els = driver.find_elements(By.CSS_SELECTOR, "span[data-waffles-component='text']")
        for el in stat_els:
            text = el.text.strip()
            if re.match(r'^\d+\s*[Jj]am$', text):
                data['duration_hours'] = re.search(r'\d+', text).group()
            elif re.match(r'^\d+\s*[Kk]ursus$', text):
                data['total_courses'] = re.search(r'\d+', text).group()
            elif re.match(r'^\d+\s*[Pp]royek$', text):
                data['total_projects'] = re.search(r'\d+', text).group()
            elif re.match(r'^\d+\s*tes kompetensi$', text, re.I):
                data['total_assessments'] = re.search(r'\d+', text).group()
            elif re.match(r'^[\d.,]+\s*peserta$', text, re.I):
                data['total_participants'] = re.sub(r'[.,]', '', re.search(r'[\d.,]+', text).group())
            elif any(t in text for t in ['Python', 'SQL', 'Power BI', 'Tableau', 'Excel', 'R,', 'Scala']):
                if not data['technology']:
                    data['technology'] = text
    except Exception:
        pass

    # ── KURSUS & PROYEK ───────────────────────────────────────
    # Dikonfirmasi dari Console:
    # h3[data-testid="expandable-content-card-title"] berisi nama kursus/proyek
    # Bedakan kursus vs proyek dari parent section data-testid
    courses = []
    projects = []
    try:
        titles = driver.find_elements(By.CSS_SELECTOR,
            'h3[data-testid="expandable-content-card-title"]'
        )
        for title in titles:
            name = title.text.strip()
            if not name or len(name) < 2:
                continue

            # Cek parent section untuk tahu kursus atau proyek
            try:
                section = title.find_element(By.XPATH, 'ancestor::section[1]')
                testid = section.get_attribute('data-testid') or ''
            except Exception:
                testid = ''

            if 'project' in testid.lower():
                if name not in projects:
                    projects.append(name)
            else:
                # Default ke kursus
                if name not in courses:
                    courses.append(name)
    except Exception as e:
        print(f"    ⚠ courses error: {e}")

    data['courses'] = ' | '.join(courses)
    data['projects'] = ' | '.join(projects)

    return data


def save_csv(results, filename):
    with open(filename, 'w', newline='', encoding='utf-8-sig') as f:
        writer = csv.DictWriter(f, fieldnames=CSV_FIELDS)
        writer.writeheader()
        writer.writerows(results)
    print(f"  ✅ Disimpan ke {filename} ({len(results)} rows)")


def main():
    print("=" * 55)
    print("  DataCamp Tracks Scraper (Career + Skill)")
    print("=" * 55)

    driver = setup_driver()
    inject_cookies(driver)

    try:
        # ── CAREER TRACKS ─────────────────────────────────────
        print("[ CAREER TRACKS ]")
        career_slugs = get_slugs(driver, CAREER_LIST_URL, 'career-tracks')
        print(f"✓ {len(career_slugs)} career track ditemukan\n")

        career_results = []
        for i, slug in enumerate(career_slugs):
            url = f"https://app.datacamp.com/learn/career-tracks/{slug}"
            print(f"  [{i+1}/{len(career_slugs)}] {slug}")
            try:
                data = scrape_track(driver, url, slug, 'career')
                career_results.append(data)
                n_courses = len([c for c in data['courses'].split('|') if c.strip()])
                n_projects = len([c for c in data['projects'].split('|') if c.strip()])
                print(f"    ✓ {data['name']} | {data['duration_hours']}jam | {n_courses} kursus | {n_projects} proyek | {data['total_participants']} peserta")
                if data['description']:
                    print(f"    desc: {data['description'][:80]}...")
                else:
                    print(f"    ⚠ deskripsi kosong")
            except Exception as e:
                print(f"    ✗ Error: {e}")
            time.sleep(1)

        save_csv(career_results, CAREER_OUTPUT)

        # ── SKILL TRACKS ──────────────────────────────────────
        print("\n[ SKILL TRACKS ]")
        skill_slugs = get_slugs(driver, SKILL_LIST_URL, 'skill-tracks')
        print(f"✓ {len(skill_slugs)} skill track ditemukan\n")

        skill_results = []
        for i, slug in enumerate(skill_slugs):
            url = f"https://app.datacamp.com/learn/skill-tracks/{slug}"
            print(f"  [{i+1}/{len(skill_slugs)}] {slug}")
            try:
                data = scrape_track(driver, url, slug, 'skill')
                skill_results.append(data)
                n_courses = len([c for c in data['courses'].split('|') if c.strip()])
                n_projects = len([c for c in data['projects'].split('|') if c.strip()])
                print(f"    ✓ {data['name']} | {data['duration_hours']}jam | {n_courses} kursus | {n_projects} proyek | {data['total_participants']} peserta")
                if data['description']:
                    print(f"    desc: {data['description'][:80]}...")
                else:
                    print(f"    ⚠ deskripsi kosong")
            except Exception as e:
                print(f"    ✗ Error: {e}")
            time.sleep(1)

        save_csv(skill_results, SKILL_OUTPUT)

    except KeyboardInterrupt:
        print("\n⚠ Dihentikan oleh user")
    finally:
        driver.quit()
        print("\n✅ Selesai!")


if __name__ == '__main__':
    main()