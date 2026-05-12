import requests
from bs4 import BeautifulSoup
import csv
import time
import re

# ============================================================
# KONFIGURASI
# ============================================================

BASE_URL = 'https://app.datacamp.com/learn/career-tracks'
LIST_URL = 'https://app.datacamp.com/learn/career-tracks'
OUTPUT_FILE = 'career_tracks.csv'

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 '
                  '(KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Accept-Language': 'en-US,en;q=0.9',
}

# ============================================================


def get_page(url):
    try:
        res = requests.get(url, headers=HEADERS, timeout=15)
        res.raise_for_status()
        return BeautifulSoup(res.text, 'html.parser')
    except Exception as e:
        print(f"  ✗ Gagal fetch {url}: {e}")
        return None


def get_track_slugs(soup):
    """Ambil semua slug career track dari halaman list."""
    slugs = []
    seen = set()

    for a in soup.find_all('a', href=True):
        href = a['href']
        match = re.search(r'/learn/career-tracks/([a-z0-9-]+)', href)
        if match:
            slug = match.group(1)
            if slug not in seen:
                seen.add(slug)
                slugs.append(slug)

    return slugs


def scrape_track(slug):
    """Scrape detail satu career track."""
    url = f"{BASE_URL}/{slug}"
    print(f"  → {slug}")
    soup = get_page(url)
    if not soup:
        return None

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

    # ── NAMA TRACK ───────────────────────────────────────────
    h1 = soup.find('h1')
    if h1:
        data['name'] = h1.get_text(strip=True)

    # ── DESKRIPSI ────────────────────────────────────────────
    for sel in ['p[class*="description"]', '[class*="Description"] p', '[class*="track-description"]']:
        el = soup.select_one(sel)
        if el:
            data['description'] = el.get_text(strip=True)
            break

    # Fallback: cari paragraf setelah heading "Deskripsi Track"
    if not data['description']:
        for heading in soup.find_all(['h2', 'h3', 'h4']):
            if 'deskripsi' in heading.get_text(strip=True).lower() or 'description' in heading.get_text(strip=True).lower():
                next_p = heading.find_next('p')
                if next_p:
                    data['description'] = next_p.get_text(strip=True)
                break

    # ── STATS (durasi, kursus, proyek, peserta) ───────────────
    full_text = soup.get_text()

    # Durasi jam
    match = re.search(r'(\d+)\s*jam', full_text)
    if match:
        data['duration_hours'] = match.group(1)

    # Jumlah kursus
    match = re.search(r'(\d+)\s*[Kk]ursus', full_text)
    if match:
        data['total_courses'] = match.group(1)

    # Jumlah proyek
    match = re.search(r'(\d+)\s*[Pp]royek', full_text)
    if match:
        data['total_projects'] = match.group(1)

    # Jumlah tes kompetensi
    match = re.search(r'(\d+)\s*tes kompetensi', full_text)
    if match:
        data['total_assessments'] = match.group(1)

    # Jumlah peserta
    match = re.search(r'([\d.,]+)\s*peserta', full_text)
    if match:
        data['total_participants'] = match.group(1).replace('.', '').replace(',', '')

    # ── LIST KURSUS ───────────────────────────────────────────
    courses = []
    projects = []

    # Cari semua section bertipe KURSUS dan PROYEK
    for section in soup.find_all(['section', 'div']):
        label = section.get_text(strip=True).upper()

        # Cari heading dengan label KURSUS
        for label_el in section.find_all(string=re.compile(r'^KURSUS$', re.I)):
            parent = label_el.parent
            # Cari nama kursus di elemen berikutnya
            sibling = parent.find_next(['h2', 'h3', 'h4', 'h5'])
            if sibling:
                name = sibling.get_text(strip=True)
                # Hapus nomor di awal (misal "1 Pengantar Python" → "Pengantar Python")
                name = re.sub(r'^\d+\s*', '', name)
                if name and name not in courses:
                    courses.append(name)

        # Cari heading dengan label PROYEK
        for label_el in section.find_all(string=re.compile(r'^PROYEK$', re.I)):
            parent = label_el.parent
            sibling = parent.find_next(['h2', 'h3', 'h4', 'h5'])
            if sibling:
                name = sibling.get_text(strip=True)
                name = re.sub(r'^\d+\s*', '', name)
                if name and name not in projects:
                    projects.append(name)

    # Fallback: cari semua heading dengan angka di depan
    if not courses:
        for el in soup.find_all(['h2', 'h3', 'h4', 'h5']):
            text = el.get_text(strip=True)
            if re.match(r'^\d+\s+\w', text):
                name = re.sub(r'^\d+\s*', '', text)
                if name not in courses:
                    courses.append(name)

    data['courses'] = ' | '.join(courses)
    data['projects'] = ' | '.join(projects)

    # ── INSTRUKTUR ────────────────────────────────────────────
    instructors = []
    for el in soup.find_all(['h3', 'h4', 'strong', 'p']):
        # Cari nama instruktur (biasanya diikuti jabatan)
        parent_text = el.parent.get_text(strip=True) if el.parent else ''
        if any(keyword in parent_text.lower() for keyword in ['director', 'scientist', 'engineer', 'professor', 'instructor', 'analyst']):
            name = el.get_text(strip=True)
            if name and len(name) > 3 and len(name) < 60 and name not in instructors:
                # Filter bukan heading utama
                if not any(c.isdigit() for c in name):
                    instructors.append(name)

    data['instructors'] = ' | '.join(instructors[:10])  # max 10 instruktur

    return data


def main():
    print("=" * 50)
    print("  DataCamp Career Tracks Scraper")
    print("=" * 50)

    # Ambil halaman list
    print("\n→ Ambil daftar career tracks...")
    soup = get_page(LIST_URL)
    if not soup:
        print("✗ Gagal fetch halaman list")
        return

    slugs = get_track_slugs(soup)
    print(f"✓ {len(slugs)} career track ditemukan: {slugs}\n")

    if not slugs:
        print("✗ Tidak ada slug ditemukan — halaman mungkin render via JavaScript")
        print("  → Coba jalankan versi Selenium jika ini terjadi")
        return

    # Scrape tiap track
    results = []
    for i, slug in enumerate(slugs):
        print(f"[{i+1}/{len(slugs)}] Scraping {slug}...")
        data = scrape_track(slug)
        if data:
            results.append(data)
            print(f"  ✓ {data['name']} — {data['total_courses']} kursus, {data['duration_hours']} jam")
        time.sleep(1)

    if not results:
        print("\n✗ Tidak ada data yang berhasil di-scrape")
        return

    # Simpan ke CSV
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

    print(f"\n{'=' * 50}")
    print(f"  ✅ Selesai! {len(results)} track disimpan ke {OUTPUT_FILE}")
    print(f"{'=' * 50}")


if __name__ == '__main__':
    main()