import time
import pymysql
import undetected_chromedriver as uc
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait

# ============================================================
# KONFIGURASI
# ============================================================
DB_CONFIG = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'database': 'datacamp',
    'charset': 'utf8mb4'
}

# Sesuaikan profile Chrome kamu - biasanya Default
CHROME_PROFILE = r'C:\Users\lenovo\AppData\Local\Google\Chrome\User Data'
CHROME_PROFILE_DIR = 'Default'

TECH_CERTS = [
    {'slug': 'power-bi-pl-300',                'url': 'https://app.datacamp.com/certification/technology-certifications/power-bi-pl-300'},
    {'slug': 'tableau-certified-data-analyst', 'url': 'https://app.datacamp.com/certification/technology-certifications/tableau-certified-data-analyst'},
    {'slug': 'sql-associate',                  'url': 'https://app.datacamp.com/certification/technology-certifications/sql-associate'},
    {'slug': 'python-data-associate',          'url': 'https://app.datacamp.com/certification/technology-certifications/python-data-associate'},
    {'slug': 'azure-fundamentals',             'url': 'https://app.datacamp.com/certification/technology-certifications/azure-fundamentals'},
    {'slug': 'azure-developer',                'url': 'https://app.datacamp.com/certification/technology-certifications/azure-developer'},
    {'slug': 'github-foundations',             'url': 'https://app.datacamp.com/certification/technology-certifications/github-foundations'},
    {'slug': 'aws-cloud-practitioner',         'url': 'https://app.datacamp.com/certification/technology-certifications/aws-cloud-practitioner'},
    {'slug': 'alteryx-designer-core',          'url': 'https://app.datacamp.com/certification/technology-certifications/alteryx-designer-core'},
    {'slug': 'knime-fundamentals',             'url': 'https://app.datacamp.com/certification/technology-certifications/knime-fundamentals'},
]
# ============================================================


def setup_driver():
    options = uc.ChromeOptions()
    options.add_argument('--window-size=1920,1080')
    options.add_argument(f'--user-data-dir={CHROME_PROFILE}')
    options.add_argument(f'--profile-directory={CHROME_PROFILE_DIR}')
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    driver = uc.Chrome(options=options)
    driver.set_page_load_timeout(60)
    return driver


def wait_load(driver, timeout=20):
    try:
        WebDriverWait(driver, timeout).until(
            lambda d: d.execute_script("return document.readyState") == "complete"
        )
        time.sleep(5)
    except Exception:
        pass


def scroll_slow(driver):
    try:
        height = driver.execute_script("return document.body.scrollHeight")
        for i in range(0, height, 400):
            driver.execute_script(f"window.scrollTo(0, {i});")
            time.sleep(0.3)
        time.sleep(3)
    except Exception:
        pass


def get_sertifikasi_id(cursor, slug):
    cursor.execute("SELECT id FROM sertifikasi WHERE slug = %s", (slug,))
    row = cursor.fetchone()
    return row[0] if row else None


def scrape_page(driver):
    sections = []
    faqs = []

    try:
        headings = driver.find_elements(By.CSS_SELECTOR, 'h2, h3')
        for h in headings:
            title = h.text.strip()
            if not title or len(title) < 5:
                continue

            content = driver.execute_script("""
                var el = arguments[0];
                var next = el.nextElementSibling;
                var text = '';
                var count = 0;
                while (next && !['H1','H2','H3'].includes(next.tagName) && count < 8) {
                    var t = next.innerText;
                    if (t && t.trim().length > 0) {
                        text += t.trim() + '\\n\\n';
                    }
                    next = next.nextElementSibling;
                    count++;
                }
                return text.trim();
            """, h)

            if not content or len(content) < 10:
                continue

            content = content[:1000]

            if '?' in title:
                faqs.append({
                    'pertanyaan': title,
                    'jawaban': content,
                    'urutan': len(faqs)
                })
            else:
                sections.append({
                    'judul': title,
                    'konten': content,
                    'urutan': len(sections)
                })

    except Exception as e:
        print(f"  Scrape error: {e}")

    return sections, faqs


def save_to_db(conn, sertifikasi_id, sections, faqs):
    cursor = conn.cursor()
    try:
        cursor.execute("DELETE FROM sertifikasi_faq WHERE sertifikasi_id = %s", (sertifikasi_id,))
        cursor.execute("DELETE FROM sertifikasi_section WHERE sertifikasi_id = %s", (sertifikasi_id,))

        for s in sections:
            cursor.execute("""
                INSERT INTO sertifikasi_section (sertifikasi_id, judul_section, konten, urutan)
                VALUES (%s, %s, %s, %s)
            """, (sertifikasi_id, s['judul'], s['konten'], s['urutan']))

        for f in faqs:
            cursor.execute("""
                INSERT INTO sertifikasi_faq (sertifikasi_id, pertanyaan, jawaban, urutan)
                VALUES (%s, %s, %s, %s)
            """, (sertifikasi_id, f['pertanyaan'], f['jawaban'], f['urutan']))

        conn.commit()
        print(f"  ✓ Saved: {len(sections)} sections, {len(faqs)} FAQs")
    except Exception as e:
        print(f"  DB error: {e}")
        conn.rollback()
    finally:
        cursor.close()


def main():
    print("=" * 55)
    print("  DataCamp Tech Cert Scraper (Final)")
    print("=" * 55)

    # PENTING: Tutup semua Chrome yang terbuka dulu sebelum jalankan!
    print("\n⚠  Pastikan semua Chrome sudah ditutup sebelum lanjut!")
    input("Tekan ENTER jika Chrome sudah ditutup...")

    driver = setup_driver()

    # Cek login
    driver.get('https://app.datacamp.com/learn')
    time.sleep(8)
    url = driver.current_url
    print(f"✓ URL: {url}")

    if 'sign_in' in url or 'login' in url:
        print("✗ Belum login! Login dulu di browser ini, lalu jalankan ulang.")
        driver.quit()
        return

    print("✓ Login berhasil!")

    conn = pymysql.connect(**DB_CONFIG)
    cursor_check = conn.cursor()

    for cert in TECH_CERTS:
        slug = cert['slug']
        url  = cert['url']
        print(f"\n[{slug}]")

        sertifikasi_id = get_sertifikasi_id(cursor_check, slug)
        if not sertifikasi_id:
            print(f"  ✗ Slug '{slug}' tidak ditemukan di DB!")
            continue

        try:
            driver.get(url)
            wait_load(driver)

            # Tunggu cloudflare selesai
            for _ in range(10):
                if 'security' not in driver.page_source.lower() and 'verification' not in driver.page_source.lower():
                    break
                print("  Menunggu Cloudflare...")
                time.sleep(3)

            scroll_slow(driver)

            sections, faqs = scrape_page(driver)
            print(f"  → {len(sections)} sections, {len(faqs)} FAQs")

            if len(sections) == 0 and len(faqs) == 0:
                print("  ⚠ Konten kosong - mungkin masih diblokir Cloudflare")

            save_to_db(conn, sertifikasi_id, sections, faqs)

        except Exception as e:
            print(f"  ✗ Error: {e}")

        time.sleep(3)

    cursor_check.close()
    conn.close()
    driver.quit()
    print("\n✅ Selesai!")


if __name__ == '__main__':
    main()