from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
import requests
from bs4 import BeautifulSoup
import pymysql
import json
import re
from datetime import datetime

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

conn = pymysql.connect(
    host='127.0.0.1',
    port=3306,
    user='root',
    password='',
    database='datacamp',
    charset='utf8mb4'
)
cursor = conn.cursor()

def slugify(text):
    text = text.lower()
    text = re.sub(r'[^\w\s-]', '', text)
    text = re.sub(r'[\s_-]+', '-', text)
    return text.strip('-')

def insert_tool(data):
    try:
        sql = """
        INSERT INTO tools 
        (name, slug, description, category, language, difficulty, rating, 
         stars_github, source_url, icon_text, icon_color, tags, is_featured, created_at, updated_at)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE
        description=VALUES(description),
        stars_github=VALUES(stars_github),
        updated_at=VALUES(updated_at)
        """
        now = datetime.now()
        cursor.execute(sql, (
            data['name'], data['slug'], data['description'], data['category'],
            data['language'], data['difficulty'], data['rating'], data['stars_github'],
            data['source_url'], data['icon_text'], data['icon_color'],
            json.dumps(data['tags']), data['is_featured'], now, now
        ))
        conn.commit()
        print(f"✓ Berhasil: {data['name']}")
    except Exception as e:
        print(f"✗ Gagal {data['name']}: {e}")

@app.get("/")
def root():
    return {"status": "Scraper aktif"}

@app.get("/scrape/lessons/{course_id}")
def scrape_lessons(course_id: int):
    lesson_templates = [
        {"title": "Pengenalan dan Setup Environment", "duration_minutes": 10, "type": "video", "is_free_preview": True},
        {"title": "Konsep Dasar dan Teori", "duration_minutes": 15, "type": "video", "is_free_preview": True},
        {"title": "Praktik Pertama", "duration_minutes": 20, "type": "video", "is_free_preview": False},
        {"title": "Latihan Soal", "duration_minutes": 10, "type": "quiz", "is_free_preview": False},
        {"title": "Studi Kasus", "duration_minutes": 25, "type": "video", "is_free_preview": False},
    ]

    results = []
    for i, lesson in enumerate(lesson_templates):
        results.append({
            "course_id": course_id,
            "title": lesson["title"],
            "content": f"Materi pembelajaran untuk lesson {i+1}.",
            "video_url": "https://www.youtube.com/embed/dQw4w9WgXcQ",
            "duration_minutes": lesson["duration_minutes"],
            "order": i + 1,
            "type": lesson["type"],
            "is_free_preview": lesson["is_free_preview"],
        })

    return {"status": "success", "count": len(results), "data": results}