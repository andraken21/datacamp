import time
import json
import pymysql
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager

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

COOKIES = [
    {'name': 'dc_logged_in',         'value': '1',                    'domain': '.datacamp.com', 'path': '/'},
    {'name': 'authentication_token', 'value': 'nSdRtRhyKvesgG2ixLRz', 'domain': '.datacamp.com', 'path': '/'},
]

TECH_CERTS = [
    {'slug': 'power-bi-pl-300',                  'url': 'https://app.datacamp.com/certification/technology-certifications/power-bi-pl-300'},
    {'slug': 'tableau-certified-data-analyst',   'url': 'https://app.datacamp.com/certification/technology-certifications/tableau-certified-data-analyst'},
    {'slug': 'sql-associate',                    'url': 'https://app.datacamp.com/certification/technology-certifications/sql-associate'},
    {'slug': 'python-data-associate',            'url': 'https://app.datacamp.com/certification/technology-certifications/python-data-associate'},
    {'slug': 'azure-fundamentals',               'url': 'https://app.datacamp.com/certification/technology-certifications/azure-fundamentals'},
    {'slug': 'azure-developer',                  'url': 'https://app.datacamp.com/certification/technology-certifications/azure-developer'},
    {'slug': 'github-foundations',               'url': 'https://app.datacamp.com/certification/technology-certifications/github-foundations'},
    {'slug': 'aws-cloud-practitioner',           'url': 'https://app.datacamp.com/certification/technology-certifications/aws-cloud-practitioner'},
    {'slug': 'alteryx-designer-core',            'url': 'https://app.datacamp.com/certification/technology-certifications/alteryx-designer-core'},
    {'slug': 'knime-fundamentals',               'url': 'https://app.datacamp.com/certification/technology-certifications/knime-fundamentals'},
]
# ============================================================


def setup_driver():
    options = webdriver.ChromeOptions()
    options.add_argument('--no-sandbox')
    options.add_argument('--disable-dev-shm-usage')
    options.add_argument('--window-size=1920,1080')
    options.add_experimental_option('excludeSwitches', ['enable-automation'])
    driver = webdriver.Chrome(
        service=Service(ChromeDriverManager().install()),
        options=options
    )
    return driver


def inject_cookies(driver):
    driver.get('https://app.datacamp.com')
    time.sleep(3)
    for cookie in COOKIES:
        try:
            driver.add_cookie(cookie)
        except Exception as e:
            print(f"  Cookie error: {e}")
    driver.refresh()
    time.sleep(4)
    print(f"✓ Login – {driver.current_url}")


def wait_load(driver, timeout=15):
    try:
        WebDriverWait(driver, timeout).until(
            lambda d: d.execute_script("return document.readyState") == "complete"
        )
        time.sleep(3)
    except Exception:
        pass


def scrape_faq(driver):
    """Scrape FAQ sections dari halaman tech cert"""
    faqs = []
    try:
        # Cari semua heading FAQ (h2, h3 yang berisi pertanyaan)
        headings = driver.find_elements(By.CSS_SELECTOR, 'h2, h3')
        for h in headings:
            text = h.text.strip()
            if len(text) > 10 and '?' in text:
                # Ambil konten di bawah heading ini
                try:
                    content = driver.execute_script("""
                        var el = arguments[0];
                        var next = el.nextElementSibling;
                        var text = '';
                        while (next && !['H2','H3'].includes(next.tagName)) {
                            text += next.innerText + '\\n';
                            next = next.nextElementSibling;
                        }
                        return text.trim();
                    """, h)
                    if content and len(content) > 20:
                        faqs.append({'q': text, 'a': content[:500]})
                except Exception:
                    pass
    except Exception as e:
        print(f"  FAQ error: {e}")
    return faqs


def scrape_detail(driver):
    """Scrape konten detail halaman tech cert"""
    detail = {
        'hero_title': '',
        'hero_subtitle': '',
        'what_is': '',
        'how_prepare': '',
        'who_for': '',
        'discount_info': '',
        'partner_logo': '',
    }
    
    try:
        # Hero title
        h1 = driver.find_elements(By.TAG_NAME, 'h1')
        if h1:
            detail['hero_title'] = h1[0].text.strip()
        
        # Ambil semua section content
        sections = {}
        headings = driver.find_elements(By.CSS_SELECTOR, 'h2, h3')
        for h in headings:
            text = h.text.strip()
            if not text or len(text) < 5:
                continue
            try:
                content = driver.execute_script("""
                    var el = arguments[0];
                    var next = el.nextElementSibling;
                    var text = '';
                    var count = 0;
                    while (next && !['H2','H3'].includes(next.tagName) && count < 5) {
                        text += next.innerText + '\\n';
                        next = next.nextElementSibling;
                        count++;
                    }
                    return text.trim();
                """, h)
                if content:
                    sections[text] = content[:800]
            except Exception:
                pass
        
        detail['sections'] = sections
        
    except Exception as e:
        print(f"  Detail error: {e}")
    
    return detail


def save_to_db(slug, faqs, detail):
    try:
        conn = pymysql.connect(**DB_CONFIG)
        cursor = conn.cursor()
        
        cursor.execute("""
            UPDATE sertifikasi 
            SET konten_faq = %s, konten_detail = %s
            WHERE slug = %s
        """, (
            json.dumps(faqs, ensure_ascii=False),
            json.dumps(detail, ensure_ascii=False),
            slug
        ))
        
        conn.commit()
        cursor.close()
        conn.close()
        print(f"  ✓ Saved to DB: {slug} ({len(faqs)} FAQs, {len(detail.get('sections', {}))} sections)")
    except Exception as e:
        print(f"  ✗ DB Error: {e}")


def main():
    print("=" * 55)
    print("  DataCamp Tech Certification Scraper")
    print("=" * 55)

    driver = setup_driver()
    inject_cookies(driver)

    for cert in TECH_CERTS:
        slug = cert['slug']
        url = cert['url']
        print(f"\n[{slug}]")
        
        try:
            driver.get(url)
            wait_load(driver)
            
            faqs = scrape_faq(driver)
            detail = scrape_detail(driver)
            
            print(f"  → {len(faqs)} FAQs, {len(detail.get('sections', {}))} sections")
            
            save_to_db(slug, faqs, detail)
            
        except Exception as e:
            print(f"  ✗ Error: {e}")
        
        time.sleep(2)

    driver.quit()
    print("\n✅ Selesai!")


if __name__ == '__main__':
    main()