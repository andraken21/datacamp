import requests
import pymysql
import json
import time
from datetime import datetime

conn = pymysql.connect(
    host='127.0.0.1',
    port=3306,
    user='root',
    password='',
    database='datacamp',
    charset='utf8mb4'
)
cursor = conn.cursor()

# ⚠️ Update token kalau expired
DCT_TOKEN = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJneUZvOVJGU21lTUIzcGZRNk1oMkhFa182dksySDYxR05tbF9aSENZZzdnIn0.eyJleHAiOjE3ODYwOTE1NTMsImlhdCI6MTc3ODMxNTU1MywianRpIjoiNDgwMjczODYtMjYyMC00Y2Y4LTk1ZmMtZTJjOGU3MzNkMzViIiwiaXNzIjoiaHR0cDovL2tleWNsb2FrLWh0dHAuaWRlbnRpdHktbWFuYWdlbWVudC9yZWFsbXMvZGF0YWNhbXAtdXNlcnMiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiNjU2ZDY1YTItMmFhMy00NDhiLWEzYjAtZTcyYWZkZjM3ZTRlIiwidHlwIjoiQmVhcmVyIiwiYXpwIjoiQjgxMTAyRkUtQzdEQS00MjIyLUE2MEMtRUExRkVCRkQ3ODc4Iiwic2lkIjoiMGFkZjQzOWYtZTFlMC00OWJlLTk3MTQtMjE0OTkyMjVlZTc1IiwiYWNyIjoiMSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwiZGVmYXVsdC1yb2xlcy1kYXRhY2FtcC11c2VycyJdfSwicmVzb3VyY2VfYWNjZXNzIjp7ImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoiZW1haWwgZGMtdXNlci1pZCBkYy11c2VyLXNlZ21lbnQgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJ1c2VyX2lkIjoyMTM0NDAyOSwiZGNfc2VnbWVudCI6ImIyYyIsInByZWZlcnJlZF91c2VybmFtZSI6ImVjaXJpY2hpZTAxQGdtYWlsLmNvbSIsImVtYWlsIjoiZWNpcmljaGllMDFAZ21haWwuY29tIn0.GqGJBe9DVkHL-5HFWabbWgI1aHvv_Ajfboblbx4lRSXOmlJH0dKNQQGIJjRxvA4q8jHH_Dr5u8NLAxq3S55SyedSumqpU4RcmOqsnF7db0z3zNf9OfIoveXXyP3lJ0Ko-miV9NImpyqn5uyPqgWlrYtYl9z9Xx6vdFeJBek0u1HwtwFkrnC7U-wkpcjtLGTFKo44BfCvhm_r1blURz5lDlhgOO4x7lTjCrFgmcIf_RQ2LBERUI0OUpRMwPyIIppTSdzWMfXYbGjIVcTR43FYcYnaEVG4O4NbCmyxS1_tmdTDwyxbCrQ5saB7CaQWgspW_ySg5RQVg47oIXSccvi7Og'

HEADERS = {
    'Authorization': f'Bearer {DCT_TOKEN}',
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
    'Accept': 'application/json',
    'Referer': 'https://app.datacamp.com',
    'Origin': 'https://app.datacamp.com',
}

# =====================
# CARI ENDPOINT YANG WORKS
# =====================
def get_course_data(slug):
    endpoints = [
        f'https://learn-hub-api.datacamp.com/courses/{slug}',
        f'https://learn-hub-api.datacamp.com/courses/v2/{slug}',
        f'https://learn-hub-api.datacamp.com/courses/{slug}?practiceContext=course-detail',
    ]
    for url in endpoints:
        try:
            res = requests.get(url, headers=HEADERS, timeout=15)
            if res.status_code == 200:
                return res.json()
            elif res.status_code == 401:
                raise Exception("❌ TOKEN EXPIRED! Update token dulu.")
        except Exception as e:
            if 'TOKEN EXPIRED' in str(e):
                raise
            continue
    return None

# =====================
# PARSE EXERCISES
# =====================
def parse_exercises(course_data, slug):
    exercises_list = []

    # Coba berbagai struktur JSON yang mungkin
    chapters = (
        course_data.get('chapters') or
        course_data.get('course', {}).get('chapters') or
        course_data.get('course', {}).get('traditional', {}).get('chapters') or
        []
    )

    order = 1
    for chapter in chapters:
        chapter_title = chapter.get('title', '')
        exercises = chapter.get('exercises', [])

        if not exercises:
            # Kalau tidak ada exercises, jadikan chapter sebagai 1 lesson
            exercises_list.append({
                'title': chapter_title,
                'description': chapter.get('description', ''),
                'instructions': None,
                'sample_code': None,
                'solution_code': None,
                'transcript': None,
                'exercise_id': str(chapter.get('id', '')),
                'type': 'video',
                'duration_minutes': max(5, round(chapter.get('durationMinutes', 15))),
                'order': order,
            })
            order += 1
        else:
            for ex in exercises:
                ex_type_raw = ex.get('type', 'VideoExercise')

                # Map type DataCamp ke type kita
                type_map = {
                    'VideoExercise': 'video',
                    'NormalExercise': 'exercise',
                    'MultipleChoiceExercise': 'quiz',
                    'PureMultipleChoiceExercise': 'quiz',
                    'TabExercise': 'exercise',
                    'BulletExercise': 'exercise',
                }
                lesson_type = type_map.get(ex_type_raw, 'exercise')

                # Ambil transcript dari video
                transcript = None
                if ex.get('video'):
                    transcript = ex['video'].get('transcript') or ex['video'].get('description')

                exercises_list.append({
                    'title': ex.get('title') or f"{chapter_title} - {order}",
                    'description': ex.get('description', '') or chapter_title,
                    'instructions': ex.get('instructions') or ex.get('assignment'),
                    'sample_code': ex.get('sample_code') or ex.get('pre_exercise_code'),
                    'solution_code': ex.get('solution'),
                    'transcript': transcript,
                    'exercise_id': str(ex.get('id', '')),
                    'type': lesson_type,
                    'duration_minutes': max(1, round(ex.get('durationMinutes', 5))),
                    'order': order,
                })
                order += 1

    return exercises_list

# =====================
# SIMPAN KE DB
# =====================
def save_lesson(course_id, lesson):
    try:
        now = datetime.now()
        sql = """
        INSERT INTO lessons 
        (course_id, title, content, instructions, sample_code, solution_code,
         transcript, exercise_id, video_url, duration_minutes, `order`, 
         type, is_free_preview, created_at, updated_at)
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE
        title=VALUES(title),
        content=VALUES(content),
        instructions=VALUES(instructions),
        sample_code=VALUES(sample_code),
        solution_code=VALUES(solution_code),
        transcript=VALUES(transcript),
        updated_at=VALUES(updated_at)
        """
        cursor.execute(sql, (
            course_id,
            lesson['title'][:255],
            lesson['description'][:1000] if lesson['description'] else '',
            lesson['instructions'],
            lesson['sample_code'],
            lesson['solution_code'],
            lesson['transcript'],
            lesson['exercise_id'],
            None,  # video_url
            lesson['duration_minutes'],
            lesson['order'],
            lesson['type'],
            lesson['order'] <= 2,  # 2 lesson pertama gratis
            now,
            now
        ))
        conn.commit()
    except Exception as e:
        print(f"    ✗ Error save: {e}")

# =====================
# MAIN
# =====================
def main():
    print("=== Scraping DataCamp Lessons (versi baru) ===")

    # Ambil courses yang belum punya lessons
    cursor.execute("""
        SELECT c.id, c.slug 
        FROM courses c
        WHERE c.id NOT IN (SELECT DISTINCT course_id FROM lessons)
        ORDER BY c.id
    """)
    courses = cursor.fetchall()
    print(f"Courses belum di-scrape: {len(courses)}")

    total_lessons = 0
    for i, (course_id, slug) in enumerate(courses):
        print(f"[{i+1}/{len(courses)}] {slug}...", end=' ')
        try:
            data = get_course_data(slug)
            if not data:
                print("✗ Semua endpoint gagal")
                continue

            lessons = parse_exercises(data, slug)
            for lesson in lessons:
                save_lesson(course_id, lesson)

            print(f"✓ {len(lessons)} lessons")
            total_lessons += len(lessons)

        except Exception as e:
            print(f"\n❌ STOP: {e}")
            break

        time.sleep(0.5)

    print(f"\n✅ Selesai! Total {total_lessons} lessons tersimpan.")

if __name__ == '__main__':
    main()
    cursor.close()
    conn.close()