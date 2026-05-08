from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
import requests
from bs4 import BeautifulSoup
import re

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

def slugify(text):
    text = text.lower()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[\s_-]+', '-', text)
    return text.strip('-')

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

    # Scraping halaman courses publik DataCamp
    urls = [
        "https://www.datacamp.com/courses-all",
        "https://www.datacamp.com/tracks/data-scientist-with-python",
        "https://www.datacamp.com/tracks/machine-learning-scientist-with-python",
    ]

    colors = ["#1a1060","#0d2b20","#1a1a40","#2a1a00","#0a2020","#2a1010","#102030","#0d1a30"]
    categories = ["Framework","Multi-Agent","Memory","Planning","Tool Use","Monitoring","Framework","Memory"]

    for url in urls:
        try:
            res = requests.get(url, headers=headers, timeout=15)
            soup = BeautifulSoup(res.text, "html.parser")

            # Cari semua elemen yang kemungkinan berisi kursus
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

                href = item.get("href", "")
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

    # Hapus duplikat berdasarkan slug
    seen = set()
    unique = []
    for item in results:
        if item["slug"] not in seen and item["name"]:
            seen.add(item["slug"])
            unique.append(item)

    return {"status": "success", "count": len(unique), "data": unique}

@app.get("/scrape/github")
def scrape_github():
    queries = [
        ("langchain", "Framework", "#1a1060"),
        ("crewai", "Multi-Agent", "#0d2b20"),
        ("autogen", "Multi-Agent", "#1a1a40"),
        ("llamaindex", "Memory", "#2a1a00"),
        ("langgraph", "Planning", "#1a1060"),
        ("haystack-ai", "Memory", "#0d2020"),
        ("flowise", "Tool Use", "#0a2020"),
        ("autogpt", "Planning", "#1a2c10"),
    ]

    headers = {"Accept": "application/vnd.github.v3+json"}
    results = []

    for query, category, color in queries:
        try:
            url = f"https://api.github.com/search/repositories?q={query}+ai&sort=stars&per_page=1"
            res = requests.get(url, headers=headers, timeout=10)
            data = res.json()

            if data.get("items"):
                repo = data["items"][0]
                name = repo["name"].replace("-", " ").title()
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
    github = scrape_github()
    datacamp = scrape_datacamp()
    all_data = github["data"] + datacamp["data"]
    
    # Hapus duplikat
    seen = set()
    unique = []
    for item in all_data:
        if item["slug"] not in seen:
            seen.add(item["slug"])
            unique.append(item)

    return {"status": "success", "count": len(unique), "data": unique}