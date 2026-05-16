"""
DEBUG SCRIPT — jalankan ini dulu sebelum scraper utama.
Tujuan: lihat struktur HTML asli halaman tutorials DataCamp.
"""

import time
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager

TUTORIALS_LIST_URL = 'https://app.datacamp.com/learn/tutorials'

COOKIES = [
    {'name': 'dc_logged_in',         'value': '1',                    'domain': '.datacamp.com', 'path': '/'},
    {'name': 'authentication_token', 'value': 'nSdRtRhyKvesgG2ixLRz', 'domain': '.datacamp.com', 'path': '/'},
    {'name': '_dct',                 'value': 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og', 'domain': '.datacamp.com', 'path': '/'},
]


def setup_driver():
    options = webdriver.ChromeOptions()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--window-size=1280,900')
    options.add_argument('--disable-blink-features=AutomationControlled')
    options.add_argument('--disable-extensions')
    options.add_argument('--disable-gpu')
    options.add_argument('--remote-debugging-port=0')
    options.add_experimental_option('excludeSwitches', ['enable-automation'])
    options.add_experimental_option('useAutomationExtension', False)
    options.page_load_strategy = 'eager'

    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=options
    )
    driver.set_page_load_timeout(60)
    driver.set_script_timeout(30)
    return driver


def main():
    driver = setup_driver()

    # --- Login ---
    print("[1] Buka datacamp.com...")
    driver.get('https://www.datacamp.com')
    time.sleep(3)
    driver.delete_all_cookies()
    for cookie in COOKIES:
        try:
            driver.add_cookie({k: v for k, v in cookie.items() if k in ('name', 'value', 'domain', 'path')})
        except Exception as e:
            print(f"  ⚠ Cookie gagal: {cookie['name']} — {e}")

    print("[2] Buka halaman tutorials...")
    driver.get(TUTORIALS_LIST_URL)
    print(f"  URL sekarang: {driver.current_url}")

    # Tunggu lebih lama supaya JS selesai render
    print("[3] Tunggu 10 detik biar halaman render penuh...")
    time.sleep(10)

    # --- DIAGNOSIS 1: Semua <a> tag di halaman ---
    print("\n[DIAG 1] Semua href yang mengandung 'tutorial' (max 20):")
    all_hrefs = driver.execute_script("""
        return Array.from(document.querySelectorAll('a'))
            .map(a => a.href)
            .filter(h => h.toLowerCase().includes('tutorial'))
            .slice(0, 20);
    """)
    if all_hrefs:
        for h in all_hrefs:
            print(f"  {h}")
    else:
        print("  ❌ TIDAK ADA href yang mengandung 'tutorial'!")

    # --- DIAGNOSIS 2: Semua <a> tag apapun (10 pertama) ---
    print("\n[DIAG 2] Semua <a> href (10 pertama, apapun):")
    any_hrefs = driver.execute_script("""
        return Array.from(document.querySelectorAll('a'))
            .map(a => a.href)
            .filter(h => h.startsWith('http'))
            .slice(0, 10);
    """)
    for h in any_hrefs:
        print(f"  {h}")

    # --- DIAGNOSIS 3: Cek apakah ada konten tutorial (card, article, dll) ---
    print("\n[DIAG 3] Elemen yang mungkin jadi card tutorial:")
    cards = driver.execute_script("""
        var selectors = [
            'article', '[class*="card"]', '[class*="tutorial"]',
            '[class*="CourseCard"]', '[class*="tile"]', '[class*="item"]',
            '[data-testid]'
        ];
        var results = {};
        for (var sel of selectors) {
            var count = document.querySelectorAll(sel).length;
            if (count > 0) results[sel] = count;
        }
        return results;
    """)
    if cards:
        for sel, count in cards.items():
            print(f"  {sel}: {count} elemen")
    else:
        print("  ❌ Tidak ada elemen card/tutorial ditemukan!")

    # --- DIAGNOSIS 4: Cek title halaman & body text ---
    print("\n[DIAG 4] Info halaman:")
    info = driver.execute_script("""
        return {
            title: document.title,
            bodyLength: document.body.innerText.length,
            bodyPreview: document.body.innerText.substring(0, 300)
        };
    """)
    print(f"  Title   : {info['title']}")
    print(f"  Body len: {info['bodyLength']} karakter")
    print(f"  Preview : {info['bodyPreview'][:200]}")

    # --- DIAGNOSIS 5: Simpan HTML ke file untuk inspeksi manual ---
    print("\n[DIAG 5] Simpan HTML halaman ke debug_page.html...")
    html = driver.page_source
    with open('debug_page.html', 'w', encoding='utf-8') as f:
        f.write(html)
    print(f"  ✅ Disimpan! ({len(html)} karakter)")
    print("  → Buka file debug_page.html di browser untuk lihat struktur HTML-nya")

    # --- DIAGNOSIS 6: Cek apakah ada shadow DOM atau iframe ---
    print("\n[DIAG 6] Cek iframe:")
    iframes = driver.execute_script("""
        return Array.from(document.querySelectorAll('iframe')).map(f => f.src);
    """)
    if iframes:
        for f in iframes:
            print(f"  iframe: {f}")
    else:
        print("  Tidak ada iframe")

    print("\n" + "="*55)
    print("Diagnosis selesai. Lihat hasil di atas.")
    print("Kirim output ini untuk analisis lebih lanjut.")
    print("="*55)

    # Jangan langsung quit — biarkan browser terbuka 30 detik
    # supaya kamu bisa lihat tampilan browsernya secara manual
    print("\nBrowser akan tutup dalam 30 detik... (Ctrl+C untuk tutup sekarang)")
    try:
        time.sleep(30)
    except KeyboardInterrupt:
        pass

    driver.quit()


if __name__ == '__main__':
    main()