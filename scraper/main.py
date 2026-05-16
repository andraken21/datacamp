from fastapi import FastAPI, BackgroundTasks, Query, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from typing import Optional
import requests
from bs4 import BeautifulSoup
import re
import csv
import time
from pathlib import Path

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

# ============================================================
# KONFIGURASI TUTORIAL SCRAPER
# ============================================================
CSV_FILE      = "tutorials.csv"
MAX_TUTORIALS = 350
CSV_FIELDS    = ["slug", "url", "title", "category", "date_published",
                 "read_time", "author", "description", "content"]

COOKIES = [
    {"name": "dc_logged_in",         "value": "1",                    "domain": ".datacamp.com", "path": "/"},
    {"name": "authentication_token", "value": "nSdRtRhyKvesgG2ixLRz", "domain": ".datacamp.com", "path": "/"},
    {"name": "_dct",                 "value": "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og", "domain": ".datacamp.com", "path": "/"},
]

# State scraping (in-memory, reset saat server restart)
scrape_state = {
    "running": False, "done": False,
    "total": 0, "scraped": 0, "failed": 0,
    "message": "idle", "started_at": None,
}

# ============================================================
# HELPER LAMA (tidak diubah)
# ============================================================
def slugify(text):
    text = text.lower()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[\s_-]+', '-', text)
    return text.strip('-')

# ============================================================
# HELPER CSV TUTORIAL
# ============================================================
def read_csv() -> list[dict]:
    if not Path(CSV_FILE).exists():
        return []
    with open(CSV_FILE, "r", encoding="utf-8-sig") as f:
        return list(csv.DictReader(f))

def write_csv(rows: list[dict]):
    with open(CSV_FILE, "w", newline="", encoding="utf-8-sig") as f:
        writer = csv.DictWriter(f, fieldnames=CSV_FIELDS)
        writer.writeheader()
        writer.writerows(rows)

# ============================================================
# SCRAPER TUTORIAL (jalan di background thread)
# ============================================================
def run_scraper(max_tutorials: int):
    from selenium import webdriver
    from selenium.webdriver.chrome.service import Service
    from selenium.webdriver.common.by import By
    from selenium.webdriver.support.ui import WebDriverWait
    from selenium.webdriver.support import expected_conditions as EC
    from webdriver_manager.chrome import ChromeDriverManager

    scrape_state.update({
        "running": True, "done": False,
        "scraped": 0, "failed": 0,
        "message": "starting", "started_at": time.time()
    })

    def setup_driver():
        options = webdriver.ChromeOptions()
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        options.add_argument("--window-size=1280,900")
        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_argument("--disable-extensions")
        options.add_argument("--disable-gpu")
        options.add_argument("--remote-debugging-port=0")
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option("useAutomationExtension", False)
        options.page_load_strategy = "normal"
        driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()), options=options
        )
        driver.set_page_load_timeout(60)
        driver.set_script_timeout(30)
        return driver

    def safe_get(driver, url, retries=3):
        for i in range(retries):
            try:
                driver.get(url)
                return True
            except Exception:
                if i < retries - 1:
                    time.sleep(4)
        return False

    def inject_cookies(driver):
        safe_get(driver, "https://www.datacamp.com")
        time.sleep(3)
        driver.delete_all_cookies()
        for c in COOKIES:
            try:
                driver.add_cookie({k: v for k, v in c.items()
                                   if k in ("name", "value", "domain", "path")})
            except Exception:
                pass
        safe_get(driver, "https://app.datacamp.com/learn/tutorials")
        time.sleep(8)

    def get_slugs(driver, target):
        safe_get(driver, "https://app.datacamp.com/learn/tutorials")
        for _ in range(15):
            if driver.execute_script("return document.body.innerText.length") > 2000:
                break
            time.sleep(2)

        seen, slugs, no_new = set(), [], 0
        for _ in range(200):
            links = driver.execute_script("""
                return Array.from(document.querySelectorAll('a[href]'))
                    .map(a => a.href)
                    .filter(h => h && h.includes('/tutorials/'));
            """)
            prev = len(seen)
            for href in links:
                m = re.search(r'/tutorials/([^/?#]+)', href)
                if m and m.group(1) not in seen:
                    seen.add(m.group(1))
                    slugs.append(m.group(1))
            if len(slugs) >= target:
                break
            driver.execute_script("window.scrollTo(0, document.body.scrollHeight)")
            time.sleep(2.5)
            try:
                btn = driver.find_element(
                    By.XPATH,
                    "//*[contains(translate(text(),'LOADMRE','loadmre'),'load more')]"
                )
                driver.execute_script("arguments[0].click()", btn)
                time.sleep(3)
                no_new = 0
                continue
            except Exception:
                pass
            if len(seen) == prev:
                no_new += 1
                if no_new >= 5:
                    break
            else:
                no_new = 0
        return slugs[:target]

    def scrape_one(driver, slug):
        url = f"https://app.datacamp.com/learn/tutorials/{slug}"
        if not safe_get(driver, url, retries=2):
            return None
        try:
            WebDriverWait(driver, 20).until(
                EC.presence_of_element_located((By.TAG_NAME, "h1"))
            )
        except Exception:
            pass
        time.sleep(2)
        try:
            return driver.execute_script("""
                var d = {
                    slug: arguments[0], url: arguments[1],
                    title:'', category:'', date_published:'',
                    read_time:'', author:'', description:'', content:''
                };
                var h1 = document.querySelector('h1');
                d.title = h1 ? h1.innerText.trim() : '';
                var bt = document.body.innerText;
                var dm = bt.match(/(January|February|March|April|May|June|July|August|September|October|November|December)\\s+\\d{4}/);
                d.date_published = dm ? dm[0] : '';
                var tm = bt.match(/(\\d+)\\s*min\\s*read/i);
                d.read_time = tm ? tm[1] + ' min' : '';
                var ae = document.querySelector('[class*="author"],[data-testid*="author"]');
                if (ae) d.author = ae.innerText.trim().split('\\n')[0].trim();
                var badges = document.querySelectorAll('[class*="tag"],[class*="badge"],[class*="category"]');
                for (var b of badges) {
                    var t = b.innerText.trim();
                    if (t && t.length < 50 && t !== 'TUTORIAL') { d.category = t; break; }
                }
                var main = document.querySelector('main, article') || document.body;
                var noise = new Set(['Share','Contents','Load More','TUTORIAL','Dashboard','Tutorials','Courses']);
                var parts = [];
                for (var el of main.querySelectorAll('h2, h3, p')) {
                    var tx = el.innerText.trim();
                    if (!tx || tx.length < 5 || noise.has(tx)) continue;
                    parts.push((el.tagName==='H2'||el.tagName==='H3') ? '## '+tx : tx);
                    if (parts.join('\\n').length > 5000) break;
                }
                d.content = parts.join('\\n').substring(0, 5000);
                for (var p of parts) {
                    if (!p.startsWith('##') && p.length > 80) { d.description = p.substring(0, 500); break; }
                }
                return d;
            """, slug, url)
        except Exception:
            return None

    driver = None
    all_rows = []
    try:
        scrape_state["message"] = "login"
        driver = setup_driver()
        inject_cookies(driver)

        scrape_state["message"] = "collecting slugs"
        slugs = get_slugs(driver, max_tutorials)
        scrape_state["total"] = len(slugs)
        scrape_state["message"] = f"scraping {len(slugs)} tutorials"

        for i, slug in enumerate(slugs):
            data = scrape_one(driver, slug)
            if data and data.get("title"):
                all_rows.append(data)
                scrape_state["scraped"] += 1
            else:
                scrape_state["failed"] += 1

            if (i + 1) % 100 == 0 and i + 1 < len(slugs):
                try: driver.quit()
                except Exception: pass
                driver = setup_driver()
                inject_cookies(driver)

            time.sleep(1.5)

        write_csv(all_rows)
        scrape_state["message"] = "done"

    except Exception as e:
        scrape_state["message"] = f"error: {str(e)}"
    finally:
        if driver:
            try: driver.quit()
            except Exception: pass
        scrape_state["running"] = False
        scrape_state["done"]    = True


# ============================================================
# ROUTES LAMA — tetap tidak diubah
# ============================================================

@app.get("/")
def root():
    return {"status": "DataCamp Scraper aktif"}


@app.get("/scrape/datacamp")
def scrape_datacamp():
    results = []
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36",
        "Accept": "text/html,application/xhtml+xml",
        "Accept-Language": "en-US,en;q=0.9",
    }

    urls = [
        "https://www.datacamp.com/courses-all",
        "https://www.datacamp.com/tracks/data-scientist-with-python",
        "https://www.datacamp.com/tracks/machine-learning-scientist-with-python",
    ]

    colors     = ["#1a1060","#0d2b20","#1a1a40","#2a1a00","#0a2020","#2a1010","#102030","#0d1a30"]
    categories = ["Framework","Multi-Agent","Memory","Planning","Tool Use","Monitoring","Framework","Memory"]

    for url in urls:
        try:
            res  = requests.get(url, headers=headers, timeout=15)
            soup = BeautifulSoup(res.text, "html.parser")

            selectors = [
                "h4", "h3", ".course-title",
                "[class*='course']", "[class*='track']",
                "a[href*='/courses/']", "a[href*='/tracks/']"
            ]

            found = []
            for selector in selectors:
                items = soup.select(selector)
                if items:
                    found.extend(items[:10])
                    break

            for i, item in enumerate(found[:15]):
                title = item.get_text().strip()
                if len(title) < 5 or len(title) > 150:
                    continue

                href       = item.get("href", "")
                source_url = f"https://www.datacamp.com{href}" if href.startswith("/") else url

                results.append({
                    "name": title[:100],
                    "slug": slugify(title[:100]),
                    "description": f"Kursus DataCamp: {title}. Pelajari skill data science dan AI secara interaktif.",
                    "category": categories[i % len(categories)],
                    "language": "Python",
                    "difficulty": "Menengah",
                    "rating": 4.5,
                    "stars_github": 0,
                    "source_url": source_url,
                    "icon_text": title[:2].upper(),
                    "icon_color": colors[i % len(colors)],
                    "tags": ["datacamp", "data-science", "python"],
                    "is_featured": i < 3,
                })

        except Exception as e:
            print(f"Error scraping {url}: {e}")

    seen   = set()
    unique = []
    for item in results:
        if item["slug"] not in seen and item["name"]:
            seen.add(item["slug"])
            unique.append(item)

    return {"status": "success", "count": len(unique), "data": unique}


@app.get("/scrape/github")
def scrape_github():
    queries = [
        ("langchain",   "Framework",   "#1a1060"),
        ("crewai",      "Multi-Agent", "#0d2b20"),
        ("autogen",     "Multi-Agent", "#1a1a40"),
        ("llamaindex",  "Memory",      "#2a1a00"),
        ("langgraph",   "Planning",    "#1a1060"),
        ("haystack-ai", "Memory",      "#0d2020"),
        ("flowise",     "Tool Use",    "#0a2020"),
        ("autogpt",     "Planning",    "#1a2c10"),
    ]

    headers = {"Accept": "application/vnd.github.v3+json"}
    results = []

    for query, category, color in queries:
        try:
            url  = f"https://api.github.com/search/repositories?q={query}+ai&sort=stars&per_page=1"
            res  = requests.get(url, headers=headers, timeout=10)
            data = res.json()

            if data.get("items"):
                repo  = data["items"][0]
                name  = repo["name"].replace("-", " ").title()
                stars = repo.get("stargazers_count", 0)

                results.append({
                    "name": name,
                    "slug": slugify(name),
                    "description": repo.get("description") or f"AI tool: {name}",
                    "category": category,
                    "language": repo.get("language") or "Python",
                    "difficulty": "Menengah",
                    "rating": round(min(5.0, 4.0 + (stars / 100000)), 1),
                    "stars_github": stars,
                    "source_url": repo.get("html_url", ""),
                    "icon_text": name[:2].upper(),
                    "icon_color": color,
                    "tags": [query, "ai-agent", category.lower()],
                    "is_featured": stars > 10000,
                })
        except Exception as e:
            print(f"Error {query}: {e}")

    return {"status": "success", "count": len(results), "data": results}


@app.get("/scrape/all")
def scrape_all():
    github   = scrape_github()
    datacamp = scrape_datacamp()
    all_data = github["data"] + datacamp["data"]

    seen   = set()
    unique = []
    for item in all_data:
        if item["slug"] not in seen:
            seen.add(item["slug"])
            unique.append(item)

    return {"status": "success", "count": len(unique), "data": unique}


# ============================================================
# ROUTES BARU — TUTORIAL SCRAPER
# ============================================================

@app.post("/tutorials/scrape")
def start_tutorial_scrape(
    background_tasks: BackgroundTasks,
    max_tutorials: int = Query(default=350, ge=1, le=500)
):
    """Mulai scraping tutorial DataCamp (jalan di background, tidak blocking)."""
    if scrape_state["running"]:
        return {"success": False, "message": "Scraper sedang berjalan, tunggu selesai dulu"}
    background_tasks.add_task(run_scraper, max_tutorials)
    return {"success": True, "message": f"Scraping {max_tutorials} tutorial dimulai di background"}


@app.get("/tutorials/status")
def tutorial_scrape_status():
    """Cek progress scraping tutorial."""
    elapsed = None
    if scrape_state["started_at"]:
        elapsed = round(time.time() - scrape_state["started_at"])
    return {**scrape_state, "elapsed_seconds": elapsed}


@app.get("/tutorials/categories")
def tutorial_categories():
    """Ambil daftar kategori unik dari CSV."""
    rows = read_csv()
    cats = sorted({r.get("category", "").strip() for r in rows if r.get("category", "").strip()})
    return {"categories": cats}


@app.get("/tutorials")
def list_tutorials(
    page:     int           = Query(default=1,  ge=1),
    per_page: int           = Query(default=20, ge=1, le=100),
    search:   Optional[str] = Query(default=None),
    category: Optional[str] = Query(default=None),
    author:   Optional[str] = Query(default=None),
):
    """Ambil daftar tutorial dari CSV dengan filter dan pagination."""
    rows = read_csv()

    if search:
        q    = search.lower()
        rows = [r for r in rows if q in r.get("title", "").lower()
                or q in r.get("description", "").lower()]
    if category:
        rows = [r for r in rows if category.lower() in r.get("category", "").lower()]
    if author:
        rows = [r for r in rows if author.lower() in r.get("author", "").lower()]

    total = len(rows)
    start = (page - 1) * per_page
    items = rows[start:start + per_page]

    return {
        "total":       total,
        "page":        page,
        "per_page":    per_page,
        "total_pages": -(-total // per_page),
        "data":        items,
    }


@app.get("/tutorials/{slug}")
def get_tutorial(slug: str):
    """Ambil detail satu tutorial berdasarkan slug."""
    rows = read_csv()
    for r in rows:
        if r.get("slug") == slug:
            return r
    raise HTTPException(status_code=404, detail="Tutorial tidak ditemukan")