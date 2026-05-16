"""
DataCamp Real World Projects Scraper
Mengambil data dari:
  - Card list  : title, level, description, authors, duration
  - Detail page: project_type, tools, prerequisites, instructors, topics, updated_date
"""

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
# KONFIGURASI -- edit di sini sesuai kebutuhan
# ============================================================

PROJECTS_LIST_URL = 'https://app.datacamp.com/learn/projects'
OUTPUT_FILE       = 'projects.csv'
MAX_PROJECTS      = 500
RESTART_EVERY     = 30    # restart Chrome setiap N project (cegah crash)
SLEEP_BETWEEN     = 2     # jeda antar request (detik)
PAGE_LOAD_TIMEOUT = 20    # timeout tunggu halaman (detik)
MAX_RETRIES       = 3     # retry per project jika gagal

COOKIES = [
    {'name': 'dc_logged_in',         'value': '1',                    'domain': '.datacamp.com', 'path': '/'},
    {'name': 'authentication_token', 'value': 'nSdRtRhyKvesgG2ixLRz', 'domain': '.datacamp.com', 'path': '/'},
    {'name': '_dct',                 'value': 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og', 'domain': '.datacamp.com', 'path': '/'},
]

CSV_FIELDS = [
    'slug', 'url',
    'title', 'level', 'duration',
    'description',
    'project_type',
    'tools',
    'prerequisites',
    'instructors',
    'topics',
    'updated_date',
    'authors',
]

# ============================================================


def setup_driver():
    options = webdriver.ChromeOptions()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--window-size=1280,900')
    options.add_argument('--disable-blink-features=AutomationControlled')
    options.add_argument('--disable-extensions')
    options.add_experimental_option('excludeSwitches', ['enable-automation'])
    options.add_experimental_option('useAutomationExtension', False)
    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=options
    )
    driver.set_page_load_timeout(60)
    driver.execute_script(
        "Object.defineProperty(navigator, 'webdriver', {get: () => undefined})"
    )
    return driver


def inject_cookies(driver):
    driver.get('https://app.datacamp.com')
    time.sleep(3)
    driver.delete_all_cookies()
    driver.execute_cdp_cmd('Network.setCookies', {'cookies': COOKIES})
    driver.get(PROJECTS_LIST_URL)
    time.sleep(5)
    print(f"✓ Login — {driver.current_url}\n")


def wait_for_page(driver, timeout=None):
    t = timeout or PAGE_LOAD_TIMEOUT
    try:
        WebDriverWait(driver, t).until(
            lambda d: d.execute_script("return document.readyState") == "complete"
        )
        time.sleep(2)
    except Exception:
        pass


def scroll_to_load(driver, max_height=10000):
    """Scroll bertahap agar lazy-load terpicu."""
    total = driver.execute_script("return document.body.scrollHeight")
    for pos in range(0, min(total, max_height), 800):
        driver.execute_script(f"window.scrollTo(0, {pos});")
        time.sleep(0.15)
    driver.execute_script("window.scrollTo(0, 0);")


# ============================================================
# Step 1 — Ambil semua project dari halaman list + data card
# ============================================================

def get_project_cards(driver):
    """
    Ambil slug + data card (title, level, desc, authors, duration)
    dari halaman list dengan klik Load More sampai habis.
    """
    driver.get(PROJECTS_LIST_URL)
    wait_for_page(driver)

    cards_data = {}   # slug → dict
    no_new_count = 0

    while len(cards_data) < MAX_PROJECTS:
        # Scroll dulu agar semua card ter-render
        scroll_to_load(driver, max_height=20000)
        time.sleep(1)

        # Extract semua card sekaligus via JS
        new_cards = driver.execute_script("""
            var results = [];
            var cards = document.querySelectorAll('section[data-waffles-component="card"]');

            for (var card of cards) {
                var linkEl = card.querySelector('a[href*="/learn/projects/"]');
                if (!linkEl) continue;

                var href = linkEl.href;
                var slugMatch = href.match(/\\/projects\\/([^/?#]+)/);
                if (!slugMatch) continue;
                var slug = slugMatch[1];

                // Title — h2 atau h3 di dalam card
                var titleEl = card.querySelector('h2, h3, [data-waffles-component="heading"]');
                var title = titleEl ? titleEl.innerText.trim() : '';

                // Level — cari teks Basic/Intermediate/Advanced
                var levelEl = card.querySelector('[class*="level"], [class*="badge"]');
                var level = '';
                if (levelEl) {
                    level = levelEl.innerText.trim();
                } else {
                    var bodyText = card.innerText;
                    var lm = bodyText.match(/\\b(Basic|Intermediate|Advanced)\\b/);
                    level = lm ? lm[1] : '';
                }

                // Deskripsi — paragraf terpanjang di card
                var desc = '';
                var paras = card.querySelectorAll('p, [data-waffles-component="text"]');
                for (var p of paras) {
                    var t = p.innerText.trim();
                    if (t.length > desc.length && t.length > 30) desc = t;
                }

                // Durasi — cari pola "N hr" atau "N min"
                var dur = '';
                var durMatch = card.innerText.match(/(\\d+\\s*hr|\\d+\\s*min)/i);
                if (durMatch) dur = durMatch[0].trim();

                // Authors — kumpulkan semua nama kecil (bukan kata tunggal noise)
                var noise = new Set(['Basic','Intermediate','Advanced','PROJECT',
                                     'Start','Continue','Retry','Ready','Skill','Level']);
                var authors = [];
                var nameEls = card.querySelectorAll('h3, [class*="author"], [class*="name"]');
                for (var ne of nameEls) {
                    var name = ne.innerText.trim().split('\\n')[0].trim();
                    if (name && name.length > 3 && name.length < 60 && !noise.has(name)) {
                        authors.push(name);
                    }
                }

                results.push({
                    slug: slug,
                    url: href,
                    title: title,
                    level: level,
                    description: desc,
                    duration: dur,
                    authors: authors.join(', '),
                });
            }
            return results;
        """)

        prev = len(cards_data)
        for c in new_cards:
            if c['slug'] not in cards_data:
                cards_data[c['slug']] = c

        print(f"  → {len(cards_data)} project ditemukan...")

        if len(cards_data) >= MAX_PROJECTS:
            break

        if len(cards_data) == prev:
            no_new_count += 1
            if no_new_count >= 3:
                print("  → Tidak ada card baru setelah 3x, berhenti.")
                break
        else:
            no_new_count = 0

        # Klik Load More
        try:
            load_more = WebDriverWait(driver, 8).until(
                EC.element_to_be_clickable((By.XPATH,
                    "//*[contains(text(),'Load More') or contains(text(),'Muat Lebih')]"
                ))
            )
            driver.execute_script("arguments[0].scrollIntoView(true);", load_more)
            time.sleep(0.5)
            driver.execute_script("arguments[0].click();", load_more)
            time.sleep(3)
        except Exception:
            print("  → Tidak ada Load More lagi")
            break

    return list(cards_data.values())[:MAX_PROJECTS]


# ============================================================
# Step 2 — Scrape halaman detail tiap project
# ============================================================

def scrape_project_detail(driver, slug, url):
    """
    Ambil detail dari halaman project:
    project_type, tools, prerequisites, instructors, topics, updated_date
    """
    try:
        driver.get(url)
    except Exception:
        pass  # lanjut meski timeout navigasi

    # Tunggu H1 muncul
    try:
        WebDriverWait(driver, PAGE_LOAD_TIMEOUT).until(
            EC.presence_of_element_located((By.TAG_NAME, 'h1'))
        )
    except Exception:
        raise Exception("h1 tidak muncul — halaman error atau login-wall")

    scroll_to_load(driver, max_height=6000)
    time.sleep(1)

    detail = driver.execute_script("""
        var d = {
            project_type: '',
            tools: '',
            prerequisites: '',
            instructors: '',
            topics: '',
            updated_date: '',
            title: '',
            level: '',
            description: '',
        };

        // TITLE dari H1
        var h1 = document.querySelector('h1');
        d.title = h1 ? h1.innerText.trim() : '';

        // LEVEL
        var bodyText = document.body.innerText;
        var lm = bodyText.match(/\\b(Basic|Intermediate|Advanced)\\b/);
        d.level = lm ? lm[1] : '';

        // UPDATED DATE
        var dateMatch = bodyText.match(/Updated:\\s*([A-Za-z]+\\s+\\d{4})/);
        d.updated_date = dateMatch ? dateMatch[1] : '';

        // DESCRIPTION — ambil teks setelah H2 "Project Description"
        var desc = '';
        var headings = Array.from(document.querySelectorAll('h2'));
        for (var h of headings) {
            if (h.innerText.trim() === 'Project Description') {
                // Ambil semua paragraf setelah heading ini sampai heading berikutnya
                var next = h.nextElementSibling;
                var parts = [];
                while (next && next.tagName !== 'H2' && next.tagName !== 'H3') {
                    var t = next.innerText ? next.innerText.trim() : '';
                    if (t.length > 20) parts.push(t);
                    next = next.nextElementSibling;
                }
                desc = parts.join(' ').substring(0, 1000);
                break;
            }
        }
        // Fallback: paragraf terpanjang di main
        if (!desc) {
            var main = document.querySelector('main') || document.body;
            var paras = Array.from(main.querySelectorAll('p'));
            paras.sort(function(a,b){ return b.innerText.length - a.innerText.length; });
            if (paras[0]) desc = paras[0].innerText.trim().substring(0, 1000);
        }
        d.description = desc;

        // PROJECT TYPE & TOOLS — ambil dari H3 sections
        // Struktur: H3 "Guided Project" / "Unguided Project", lalu H3 nama tool (SQL, Python, dll)
        var h3s = Array.from(document.querySelectorAll('h3'));
        var tools = [];
        for (var h3 of h3s) {
            var text = h3.innerText.trim();
            if (text.match(/Guided|Unguided/i)) {
                d.project_type = text.replace(/\\s*Project/i, '').trim();
            } else if (text.match(/^(SQL|Python|R|Scala|Shell|Julia|Julia|Redshift|BigQuery|Snowflake|Databricks|PySpark|Excel|Power BI|Tableau)$/i)) {
                tools.push(text);
            }
        }
        d.tools = tools.join(', ');

        // Helper: ambil list item setelah heading tertentu
        function getItemsAfterHeading(keyword) {
            var items = [];
            var allH3 = Array.from(document.querySelectorAll('h2, h3'));
            for (var i = 0; i < allH3.length; i++) {
                if (allH3[i].innerText.trim().toUpperCase().indexOf(keyword.toUpperCase()) !== -1) {
                    // Kumpulkan sibling sampai heading berikutnya
                    var sib = allH3[i].nextElementSibling;
                    var count = 0;
                    while (sib && sib.tagName !== 'H2' && sib.tagName !== 'H3' && count < 20) {
                        var t = sib.innerText ? sib.innerText.trim().split('\\n')[0].trim() : '';
                        if (t && t.length > 1 && t.length < 100) items.push(t);
                        sib = sib.nextElementSibling;
                        count++;
                    }
                    // Juga cari nested nama (H3 langsung sebagai item)
                    if (items.length === 0) {
                        for (var j = i+1; j < allH3.length; j++) {
                            var nextH = allH3[j];
                            if (nextH.tagName === 'H2') break;
                            var nt = nextH.innerText.trim();
                            // Stop jika ketemu section lain
                            if (nt.toUpperCase() === 'TOPICS' || nt.toUpperCase() === 'PREREQUISITES'
                                || nt.toUpperCase() === 'INSTRUCTORS') break;
                            if (nt && nt.length < 80) items.push(nt);
                        }
                    }
                    break;
                }
            }
            return items;
        }

        d.prerequisites = getItemsAfterHeading('PREREQUISITES').join(', ');
        d.topics        = getItemsAfterHeading('TOPICS').join(', ');

        // INSTRUCTORS — H3 setelah "INSTRUCTORS", tiap instructor adalah H3
        var instrItems = getItemsAfterHeading('INSTRUCTORS');
        d.instructors = instrItems.join(', ');

        return d;
    """)

    return detail or {}


# ============================================================
# CSV & Main
# ============================================================

def save_csv(results, filename):
    with open(filename, 'w', newline='', encoding='utf-8-sig') as f:
        writer = csv.DictWriter(f, fieldnames=CSV_FIELDS, extrasaction='ignore')
        writer.writeheader()
        writer.writerows(results)
    print(f"\n  ✅ Disimpan ke {filename} ({len(results)} baris)")


def main():
    print("=" * 55)
    print("  DataCamp Real World Projects Scraper")
    print(f"  Target     : {MAX_PROJECTS} project")
    print(f"  Restart per: {RESTART_EVERY} project")
    print(f"  Output     : {OUTPUT_FILE}")
    print("=" * 55)

    driver = setup_driver()
    inject_cookies(driver)

    # ── Step 1: Ambil semua card ──────────────────────────────
    print("[Step 1] Ambil daftar project dari halaman list...")
    cards = get_project_cards(driver)
    print(f"✓ {len(cards)} project card ditemukan\n")

    # ── Step 2: Scrape detail tiap project ───────────────────
    print(f"[Step 2] Scrape detail {len(cards)} project...\n")
    all_results = []

    try:
        for i, card in enumerate(cards):
            slug = card['slug']
            url  = card['url']
            print(f"  [{i+1}/{len(cards)}] {slug}")

            success = False
            for attempt in range(1, MAX_RETRIES + 1):
                try:
                    detail = scrape_project_detail(driver, slug, url)

                    # Merge card + detail, utamakan detail untuk field overlap
                    row = {**card}
                    if detail.get('title'):   row['title']       = detail['title']
                    if detail.get('level'):   row['level']       = detail['level']
                    if detail.get('description') and len(detail['description']) > len(card.get('description','')):
                        row['description'] = detail['description']
                    row['project_type']  = detail.get('project_type', '')
                    row['tools']         = detail.get('tools', '')
                    row['prerequisites'] = detail.get('prerequisites', '')
                    row['instructors']   = detail.get('instructors', '')
                    row['topics']        = detail.get('topics', '')
                    row['updated_date']  = detail.get('updated_date', '')

                    all_results.append(row)
                    print(f"    ✓ {row['title'][:50]} | {row['level']} | {row['tools'][:30]}")
                    success = True
                    break

                except Exception as e:
                    err = str(e)
                    if 'localhost' in err and ('timed out' in err or 'refused' in err):
                        print(f"    ✗ Driver crash — restart...")
                        try: driver.quit()
                        except Exception: pass
                        driver = setup_driver()
                        inject_cookies(driver)
                        break
                    else:
                        print(f"    ✗ Error (attempt {attempt}): {err[:80]}")
                        time.sleep(2)

            if not success:
                # Simpan data card saja tanpa detail
                all_results.append({**card,
                    'project_type':'', 'tools':'', 'prerequisites':'',
                    'instructors':'', 'topics':'', 'updated_date':''
                })
                print(f"    ⚠ Simpan data card saja (detail gagal)")

            # Restart berkala
            if (i + 1) % RESTART_EVERY == 0 and i + 1 < len(cards):
                print(f"\n→ Restart driver berkala ({i+1}/{len(cards)})...\n")
                try: driver.quit()
                except Exception: pass
                driver = setup_driver()
                inject_cookies(driver)

            # Checkpoint setiap 50
            if (i + 1) % 50 == 0:
                cp = OUTPUT_FILE.replace('.csv', f'_checkpoint_{i+1}.csv')
                save_csv(all_results, cp)
                print(f"  💾 Checkpoint: {cp}")

            time.sleep(SLEEP_BETWEEN)

    except KeyboardInterrupt:
        print("\n⚠ Dihentikan oleh user")
    finally:
        try: driver.quit()
        except Exception: pass
        save_csv(all_results, OUTPUT_FILE)
        print(f"\n{'='*55}")
        print(f"  Total: {len(all_results)} project berhasil di-scrape")
        print(f"{'='*55}")


if __name__ == '__main__':
    main()