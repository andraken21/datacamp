import re
import csv
import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

# ============================================================
# KONFIGURASI
# ============================================================

PRACTICE_LIST_URL     = 'https://app.datacamp.com/learn/practice'
OUTPUT_FILE           = 'practice_sessions.csv'
QUESTIONS_PER_SESSION = 3

COOKIES = [
    {'name': 'dc_logged_in',         'value': '1',                    'domain': '.datacamp.com', 'path': '/'},
    {'name': 'authentication_token', 'value': 'nSdRtRhyKvesgG2ixLRz', 'domain': '.datacamp.com', 'path': '/'},
    {'name': '_dct',                 'value': 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og', 'domain': '.datacamp.com', 'path': '/'},
]

CSV_FIELDS = [
    'session_name', 'topic', 'practice_url',
    'question_number', 'question', 'option_1', 'option_2', 'option_3',
]

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
    driver.get(PRACTICE_LIST_URL)
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


def scroll_and_load_all(driver):
    """Scroll ke bawah dan klik Load More sampai semua session ter-load."""
    for _ in range(30):
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(2)
        try:
            load_more = driver.find_element(By.XPATH,
                "//*[contains(text(), 'Load More') or contains(text(), 'Muat Lebih')]"
            )
            driver.execute_script("arguments[0].click();", load_more)
            time.sleep(3)
        except Exception:
            break


def get_practice_sessions(driver):
    """Ambil semua practice session dari halaman list."""
    driver.get(PRACTICE_LIST_URL)
    wait_load(driver)
    scroll_and_load_all(driver)

    # Ambil semua data session via JavaScript
    sessions_data = driver.execute_script("""
        var links = Array.from(document.querySelectorAll('a'));
        var sessions = [];
        var seen = new Set();
        
        links.forEach(function(link) {
            var href = link.href || '';
            if (href.includes('practice/start') && !seen.has(href)) {
                seen.add(href);
                
                // Cari nama dan topik dari parent card
                var card = link.closest('[class*="css-"]') || link.parentElement;
                var cardText = card ? card.innerText.trim() : '';
                var lines = cardText.split('\\n').filter(l => l.trim() && l.trim() !== 'Start');
                
                var name = '';
                var topic = '';
                
                // Nama biasanya setelah "PRACTICE"
                var practiceIdx = lines.findIndex(l => l.toUpperCase().includes('PRACTICE'));
                if (practiceIdx >= 0 && practiceIdx + 1 < lines.length) {
                    name = lines[practiceIdx + 1].trim();
                } else if (lines.length > 0) {
                    name = lines[0].trim();
                }
                
                // Topik biasanya di baris terakhir sebelum "Start"
                if (lines.length > 1) {
                    topic = lines[lines.length - 1].trim();
                }
                
                sessions.push({url: href, name: name, topic: topic});
            }
        });
        
        return sessions;
    """)

    return sessions_data


def parse_body_text(raw_text):
    """
    Parse pertanyaan dan pilihan dari body.innerText.
    Dari Console tadi format: 'Session Name\nReport an issue\nQ words...\nSelect correct answer\nOpt1\nOpt2\nOpt3\nSave time...'
    """
    lines = [l.strip() for l in raw_text.split('\n') if l.strip()]

    # Hapus baris noise
    noise = {'Report an issue', 'Select the correct answer', 'Save time with our keyboard shortcuts.',
             'Press Enter To', 'Check', 'PRESS', 'Later', 'Start Practice'}

    # Temukan indeks "Report an issue" sebagai titik mulai
    start_idx = 0
    for i, line in enumerate(lines):
        if 'Report an issue' in line:
            start_idx = i + 1
            break

    # Temukan indeks "Save time" sebagai titik akhir
    end_idx = len(lines)
    for i, line in enumerate(lines):
        if 'Save time' in line or 'keyboard shortcuts' in line:
            end_idx = i
            break

    content_lines = lines[start_idx:end_idx]

    # Pisah pertanyaan (sampai tanda tanya) dari pilihan
    question_parts = []
    option_start = 0
    for i, line in enumerate(content_lines):
        if line in noise or line.isdigit():
            continue
        question_parts.append(line)
        if line.endswith('?'):
            option_start = i + 1
            break

    question = ' '.join(question_parts).strip()

    # Pilihan jawaban: baris setelah pertanyaan, filter noise dan PRESS N
    raw_options = content_lines[option_start:]
    options = []
    current_option = []
    for line in raw_options:
        if line in noise or re.match(r'^PRESS\s*\d+$', line) or line.isdigit():
            if current_option:
                options.append(' '.join(current_option).strip())
                current_option = []
        elif len(line) > 1:
            current_option.append(line)

    if current_option:
        options.append(' '.join(current_option).strip())

    # Filter opsi yang terlalu pendek atau noise
    options = [o for o in options if len(o) > 1 and o not in noise]

    return question, options[:3]


def scrape_questions(driver, session_url, session_name, topic, n_questions=3):
    """Scrape n soal dari satu practice session."""
    results = []

    driver.get(session_url)
    wait_load(driver)
    time.sleep(3)

    # Ambil nama session dari judul halaman
    try:
        detected_name = driver.title.replace('Practice', '').replace('DataCamp', '').strip(' -|')
        if not detected_name:
            detected_name = session_name
    except Exception:
        detected_name = session_name

    # Klik "Start Practice" atau "Later" kalau ada popup
    try:
        start_btn = WebDriverWait(driver, 5).until(
            EC.element_to_be_clickable((By.XPATH,
                "//*[contains(text(), 'Start Practice')]"
            ))
        )
        driver.execute_script("arguments[0].click();", start_btn)
        time.sleep(4)
    except Exception:
        pass

    for q_num in range(1, n_questions + 1):
        try:
            time.sleep(3)

            # Klik pilihan pertama untuk trigger render semua pilihan
            try:
                clickables = driver.find_elements(By.CSS_SELECTOR,
                    '[class*="r-1q142lx"], [class*="r-ybhy5j"]'
                )
                if clickables:
                    driver.execute_script("arguments[0].click();", clickables[0])
                    time.sleep(1.5)
            except Exception:
                pass

            # Ambil teks halaman
            raw_text = driver.execute_script("return document.body.innerText")

            # Ambil nama session dari baris pertama
            first_line = raw_text.split('\n')[0].strip()
            if first_line and len(first_line) > 3:
                detected_name = first_line

            question, options = parse_body_text(raw_text)

            if question and len(question) > 5:
                row = {
                    'session_name': detected_name,
                    'topic': topic,
                    'practice_url': driver.current_url,
                    'question_number': q_num,
                    'question': question,
                    'option_1': options[0] if len(options) > 0 else '',
                    'option_2': options[1] if len(options) > 1 else '',
                    'option_3': options[2] if len(options) > 2 else '',
                }
                results.append(row)
                print(f"      Q{q_num}: {question[:70]}...")
            else:
                print(f"      ⚠ Q{q_num}: pertanyaan tidak ditemukan")

            # Lanjut ke soal berikutnya
            if q_num < n_questions:
                try:
                    check_btn = driver.find_element(By.XPATH,
                        "//*[contains(text(), 'Check')]"
                    )
                    driver.execute_script("arguments[0].click();", check_btn)
                    time.sleep(3)
                except Exception:
                    try:
                        driver.find_element(By.TAG_NAME, 'body').send_keys(Keys.RETURN)
                        time.sleep(3)
                    except Exception:
                        pass

        except Exception as e:
            print(f"      ⚠ Q{q_num} error: {e}")
            break

    return results


def save_csv(results, filename):
    with open(filename, 'w', newline='', encoding='utf-8-sig') as f:
        writer = csv.DictWriter(f, fieldnames=CSV_FIELDS)
        writer.writeheader()
        writer.writerows(results)
    print(f"\n  ✅ Disimpan ke {filename} ({len(results)} baris)")


def main():
    print("=" * 55)
    print("  DataCamp Practice Scraper")
    print(f"  Target: ~614 sessions × {QUESTIONS_PER_SESSION} soal")
    print("=" * 55)

    driver = setup_driver()
    inject_cookies(driver)

    print("→ Mengambil daftar practice sessions...")
    sessions = get_practice_sessions(driver)
    print(f"✓ {len(sessions)} practice session ditemukan\n")

    if not sessions:
        print("✗ Tidak ada session ditemukan")
        driver.quit()
        return

    all_results = []

    try:
        for i, session in enumerate(sessions):
            name = session.get('name', '') or f"Session {i+1}"
            topic = session.get('topic', '')
            url = session.get('url', '')

            print(f"  [{i+1}/{len(sessions)}] {name} | {topic}")

            try:
                questions = scrape_questions(
                    driver, url, name, topic, QUESTIONS_PER_SESSION
                )
                all_results.extend(questions)
                print(f"    ✓ {len(questions)}/{QUESTIONS_PER_SESSION} soal")
            except Exception as e:
                print(f"    ✗ Error: {e}")

            # Restart driver setiap 50 session
            if (i + 1) % 50 == 0 and i + 1 < len(sessions):
                print("\n→ Restart driver...\n")
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
        print(f"  Total baris: {len(all_results)}")
        print(f"{'=' * 55}")


if __name__ == '__main__':
    main()