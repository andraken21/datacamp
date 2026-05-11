import requests
import pymysql
import json
import re
from datetime import datetime

# Koneksi database
conn = pymysql.connect(
    host='127.0.0.1',
    port=3306,
    user='root',
    password='',
    database='datacamp',
    charset='utf8mb4'
)
cursor = conn.cursor()

DCT_TOKEN = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og'

HEADERS = {
    'Authorization': f'Bearer {DCT_TOKEN}',
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
    'Accept': 'application/json',
    'Referer': 'https://app.datacamp.com/learn/courses',
    'Origin': 'https://app.datacamp.com',
}

colors = ["#1a1060","#0d2b20","#1a1a40","#2a1a00","#0a2020","#2a1010","#102030","#0d1a30"]
difficulties_map = {1: 'Pemula', 2: 'Menengah', 3: 'Expert'}

def slugify(text):
    text = text.lower()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[\s_-]+', '-', text)
    return text.strip('-')

def save_course(course, index):
    try:
        instructor = course['instructors'][0]['name'] if course['instructors'] else 'DataCamp Instructor'
        difficulty = difficulties_map.get(course['difficultyLevel'], 'Pemula')
        duration_hours = round(course['durationMinutes'] / 60, 1)
        category = course['technologies'][0]['name'] if course['technologies'] else 'Python'
        description = course.get('shortDescription', '') or f"Kursus DataCamp: {course['title']}"
        now = datetime.now()

        sql = """
        INSERT INTO courses 
        (title, slug, description, category, difficulty, duration_hours, total_lessons,
         rating, students_count, instructor, thumbnail_color, icon_text, is_featured, is_free, created_at, updated_at)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE
        slug=VALUES(slug),
        description=VALUES(description),
        instructor=VALUES(instructor),
        duration_hours=VALUES(duration_hours),
        updated_at=VALUES(updated_at)
        """
        cursor.execute(sql, (
            course['title'][:150],
            course['slug'][:150],
            description[:500],
            category[:50],
            difficulty,
            duration_hours,
            5,
            round(4.0 + (index % 10) * 0.1, 1),
            1000 + (index * 100),
            instructor[:100],
            colors[index % len(colors)],
            course['title'][:2].upper(),
            index < 10,
            course['difficultyLevel'] == 1,
            now,
            now
        ))
        conn.commit()
        print(f"✓ [{index+1}] {course['title']}")
    except Exception as e:
        print(f"✗ Error {course['title']}: {e}")

def scrape_all_courses():
    print("=== Scraping DataCamp API ===")
    cursor_id = None
    total_saved = 0
    page = 1

    while True:
        # Build URL
        url = 'https://learn-hub-api.datacamp.com/courses?first=48&caseStudies=false'
        if cursor_id:
            url += f'&after={cursor_id}'

        print(f"\nHalaman {page} - {url}")
        
        try:
            res = requests.get(url, headers=HEADERS, timeout=15)
            if res.status_code != 200:
                print(f"Error status: {res.status_code}")
                break

            data = res.json()
            items = data.get('items', [])
            
            if not items:
                print("Tidak ada data lagi.")
                break

            for i, course in enumerate(items):
                save_course(course, total_saved + i)

            total_saved += len(items)
            print(f"Total tersimpan: {total_saved}")

            # Cek apakah ada halaman berikutnya
            if not data.get('hasNextChunk'):
                print("Semua data sudah di-scrape!")
                break

            cursor_id = data.get('endCursor')
            page += 1

        except Exception as e:
            print(f"Error: {e}")
            break

    print(f"\n✅ Selesai! Total {total_saved} courses tersimpan.")

if __name__ == '__main__':
    scrape_all_courses()
    cursor.close()
    conn.close()