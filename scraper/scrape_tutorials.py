import csv
import time
import re
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

# ============================================================
# KONFIGURASI
# ============================================================

TUTORIALS_LIST_URL = 'https://app.datacamp.com/learn/tutorials'
OUTPUT_FILE        = 'tutorials.csv'
MAX_TUTORIALS      = 1000

COOKIES = [
    {'name': 'dc_logged_in',         'value': '1',                    'domain': '.datacamp.com', 'path': '/'},
    {'name': 'authentication_token', 'value': 'nSdRtRhyKvesgG2ixLRz', 'domain': '.datacamp.com', 'path': '/'},
    {'name': '_dct',                 'value': 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og', 'domain': '.datacamp.com', 'path': '/'},
]

CSV_FIELDS = [
    'slug', 'url', 'title', 'category',
    'date_published', 'read_time', 'author',
    'description', 'content',
]

NOISE = {'Share', 'Contents', 'Load More', 'See More', 'Feedback',
         'Buy Now', 'Upgrade', 'Dashboard', 'Tutorials', 'Courses',
         'Learn', 'Practice', 'Report an issue', 'TUTORIAL'}

# ============================================================


def setup_driver():
    options = webdriver.ChromeOptions()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--window-size=1280,900')
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
    driver.get('https://app.datacamp.com')
    time.sleep(3)
    driver.delete_all_cookies()
    driver.execute_cdp_cmd('Network.setCookies', {'cookies': COOKIES})
    driver.get(TUTORIALS_LIST_URL)
    time.sleep(6)
    print(f"✓ Login — {driver.current_url}\n")


def wait_load(driver, timeout=15):
    try:
        WebDriverWait(driver, timeout).until(
            lambda d: d.execute_script("return document.readyState") == "complete"
        )
        time.sleep(3)
    except Exception:
        pass


def get_tutorial_slugs(driver):
    """Ambil semua slug tutorial dari halaman list."""
    driver.get(TUTORIALS_LIST_URL)
    wait_load(driver)

    slugs = []
    seen = set()

    while len(slugs) < MAX_TUTORIALS:
        links = driver.execute_script("""
            return Array.from(document.querySelectorAll('a[href*="/tutorials/"]'))
                .map(a => a.href)
                .filter(h => h.includes('/learn/tutorials/') && !h.endsWith('/tutorials/'));
        """)

        for href in links:
            match = re.search(r'/tutorials/([^/?#]+)', href)
            if match:
                slug = match.group(1)
                if slug not in seen:
                    seen.add(slug)
                    slugs.append(slug)

        print(f"  → {len(slugs)} tutorial ditemukan...")

        if len(slugs) >= MAX_TUTORIALS:
            break

        try:
            load_more = driver.find_element(By.XPATH,
                "//*[contains(text(), 'Load More') or contains(text(), 'Muat Lebih')]"
            )
            driver.execute_script("arguments[0].click();", load_more)
            time.sleep(3)
        except Exception:
            print("  → Tidak ada Load More lagi")
            break

    return slugs[:MAX_TUTORIALS]


def scrape_tutorial(driver, slug):
    """Scrape detail satu tutorial via Selenium."""
    url = f"https://app.datacamp.com/learn/tutorials/{slug}"
    driver.get(url)
    wait_load(driver)

    data = {
        'slug': slug,
        'url': url,
        'title': '',
        'category': '',
        'date_published': '',
        'read_time': '',
        'author': '',
        'description': '',
        'content': '',
    }

    # Ambil semua teks via JavaScript sekaligus (lebih cepat dari find_elements)
    result = driver.execute_script("""
        var data = {};

        // JUDUL
        var h1 = document.querySelector('h1');
        data.title = h1 ? h1.innerText.trim() : '';

        // KATEGORI — cari badge/tag di dekat judul
        var badges = Array.from(document.querySelectorAll(
            '[data-waffles-component="badge"], [class*="tag"], [class*="badge"], [class*="category"]'
        ));
        data.category = '';
        for (var b of badges) {
            var t = b.innerText.trim();
            if (t && t.length < 50 && t !== 'TUTORIAL' && t !== 'Tutorial') {
                data.category = t;
                break;
            }
        }

        // TANGGAL & DURASI BACA — ambil dari teks body
        var bodyText = document.body.innerText;
        var dateMatch = bodyText.match(/(January|February|March|April|May|June|July|August|September|October|November|December)\\s+\\d{4}/);
        data.date_published = dateMatch ? dateMatch[0] : '';

        var timeMatch = bodyText.match(/(\\d+)\\s*min\\s*read/i);
        data.read_time = timeMatch ? timeMatch[1] + ' min' : '';

        // PENULIS — cari nama di section author
        var authorEl = document.querySelector('[class*="author"], [data-testid*="author"]');
        if (authorEl) {
            var lines = authorEl.innerText.trim().split('\\n');
            data.author = lines[0].trim();
        }

        // Fallback penulis dari struktur card
        if (!data.author) {
            var imgs = Array.from(document.querySelectorAll('img[alt*="profile"], img[alt*="author"]'));
            for (var img of imgs) {
                var parent = img.parentElement;
                if (parent && parent.nextElementSibling) {
                    var name = parent.nextElementSibling.innerText.split('\\n')[0].trim();
                    if (name && name.length < 60) {
                        data.author = name;
                        break;
                    }
                }
            }
        }

        // ISI ARTIKEL — ambil semua h2, h3, p dari area konten utama
        var main = document.querySelector('main, article, [class*="listed-menu"]');
        if (!main) main = document.body;

        var contentParts = [];
        var noise = new Set(['Share', 'Contents', 'Load More', 'See More',
                             'Feedback', 'Buy Now', 'Upgrade', 'TUTORIAL',
                             'Dashboard', 'Tutorials', 'Courses', 'Learn']);

        var elements = main.querySelectorAll('h2, h3, p');
        for (var el of elements) {
            var text = el.innerText.trim();
            if (!text || text.length < 5) continue;
            if (noise.has(text)) continue;
            if (text.length > 5000) continue;

            if (el.tagName === 'H2' || el.tagName === 'H3') {
                contentParts.push('## ' + text);
            } else {
                contentParts.push(text);
            }

            if (contentParts.join('\\n').length > 5000) break;
        }

        data.content = contentParts.join('\\n').substring(0, 5000);

        // DESKRIPSI — paragraf pertama yang panjang
        for (var part of contentParts) {
            if (!part.startsWith('##') && part.length > 80) {
                data.description = part.substring(0, 500);
                break;
            }
        }

        return data;
    """)

    if result:
        data.update(result)

    return data


def save_csv(results, filename):
    with open(filename, 'w', newline='', encoding='utf-8-sig') as f:
        writer = csv.DictWriter(f, fieldnames=CSV_FIELDS)
        writer.writeheader()
        writer.writerows(results)
    print(f"\n  ✅ Disimpan ke {filename} ({len(results)} baris)")


def main():
    print("=" * 55)
    print("  DataCamp Tutorial Scraper (Full Selenium)")
    print(f"  Target: {MAX_TUTORIALS} tutorial")
    print("=" * 55)

    driver = setup_driver()
    inject_cookies(driver)

    # Step 1: Ambil slugs
    print("[Step 1] Ambil daftar tutorial slugs...")
    slugs = get_tutorial_slugs(driver)
    print(f"✓ {len(slugs)} slug ditemukan\n")

    # Step 2: Scrape detail
    print(f"[Step 2] Scrape detail {len(slugs)} tutorial...\n")
    all_results = []

    try:
        for i, slug in enumerate(slugs):
            print(f"  [{i+1}/{len(slugs)}] {slug}")

            try:
                data = scrape_tutorial(driver, slug)
                if data['title']:
                    all_results.append(data)
                    print(f"    ✓ {data['title'][:55]} | {data['author'][:20]} | {data['read_time']}")
                    if not data['content']:
                        print(f"    ⚠ content kosong")
                else:
                    print(f"    ⚠ judul kosong, skip")
            except Exception as e:
                print(f"    ✗ Error: {e}")

            # Restart driver setiap 100 tutorial
            if (i + 1) % 20 == 0 and i + 1 < len(slugs):
                print(f"\n→ Restart driver ({i+1}/{len(slugs)})...\n")
                try:
                    driver.quit()
                except Exception:
                    pass
                driver = setup_driver()
                inject_cookies(driver)

            time.sleep(1)

    except KeyboardInterrupt:
        print("\n⚠ Dihentikan oleh user")
    finally:
        try:
            driver.quit()
        except Exception:
            pass

        save_csv(all_results, OUTPUT_FILE)
        print(f"\n{'=' * 55}")
        print(f"  Total: {len(all_results)} tutorial berhasil di-scrape")
        print(f"{'=' * 55}")


if __name__ == '__main__':
    main()