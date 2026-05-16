<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder {
    public function run(): void {
        $courses = [
            [
                'title' => 'Pengantar LangChain untuk Pemula',
                'category' => 'Framework',
                'difficulty' => 'Pemula',
                'duration_hours' => 4,
                'rating' => 4.8,
                'students_count' => 12500,
                'instructor' => 'Dr. Budi Santoso',
                'thumbnail_color' => '#1a1060',
                'icon_text' => 'LC',
                'is_featured' => true,
                'is_free' => true,
                'description' => 'Pelajari dasar-dasar LangChain dan cara membangun aplikasi AI pertama kamu.',
                'lessons' => [
                    ['title' => 'Apa itu LangChain?', 'duration_minutes' => 10, 'type' => 'video', 'is_free_preview' => true],
                    ['title' => 'Instalasi dan Setup', 'duration_minutes' => 15, 'type' => 'video', 'is_free_preview' => true],
                    ['title' => 'Membuat Chain Pertama', 'duration_minutes' => 20, 'type' => 'video'],
                    ['title' => 'Memory dan Context', 'duration_minutes' => 25, 'type' => 'video'],
                    ['title' => 'Quiz: LangChain Dasar', 'duration_minutes' => 10, 'type' => 'quiz'],
                ]
            ],
            [
                'title' => 'Multi-Agent dengan CrewAI',
                'category' => 'Multi-Agent',
                'difficulty' => 'Menengah',
                'duration_hours' => 6,
                'rating' => 4.7,
                'students_count' => 8300,
                'instructor' => 'Sari Dewi, M.Kom',
                'thumbnail_color' => '#0d2b20',
                'icon_text' => 'CR',
                'is_featured' => true,
                'is_free' => false,
                'description' => 'Bangun sistem multi-agent profesional menggunakan CrewAI dengan pendekatan role-based.',
                'lessons' => [
                    ['title' => 'Konsep Multi-Agent', 'duration_minutes' => 15, 'type' => 'video', 'is_free_preview' => true],
                    ['title' => 'Setup CrewAI', 'duration_minutes' => 20, 'type' => 'video'],
                    ['title' => 'Membuat Agent dan Role', 'duration_minutes' => 30, 'type' => 'video'],
                    ['title' => 'Workflow Automation', 'duration_minutes' => 35, 'type' => 'video'],
                    ['title' => 'Proyek: Tim DataCamp', 'duration_minutes' => 60, 'type' => 'video'],
                ]
            ],
            [
                'title' => 'RAG dengan LlamaIndex',
                'category' => 'Memory',
                'difficulty' => 'Menengah',
                'duration_hours' => 5,
                'rating' => 4.9,
                'students_count' => 9100,
                'instructor' => 'Ahmad Rizki',
                'thumbnail_color' => '#2a1a00',
                'icon_text' => 'LI',
                'is_featured' => true,
                'is_free' => false,
                'description' => 'Kuasai teknik Retrieval Augmented Generation menggunakan LlamaIndex untuk aplikasi AI yang akurat.',
                'lessons' => [
                    ['title' => 'Apa itu RAG?', 'duration_minutes' => 12, 'type' => 'video', 'is_free_preview' => true],
                    ['title' => 'Setup LlamaIndex', 'duration_minutes' => 18, 'type' => 'video'],
                    ['title' => 'Indexing Dokumen', 'duration_minutes' => 25, 'type' => 'video'],
                    ['title' => 'Query Engine', 'duration_minutes' => 30, 'type' => 'video'],
                    ['title' => 'Proyek: Chatbot Dokumen', 'duration_minutes' => 45, 'type' => 'video'],
                ]
            ],
            [
                'title' => 'AutoGen: Conversational Agents',
                'category' => 'Multi-Agent',
                'difficulty' => 'Expert',
                'duration_hours' => 8,
                'rating' => 4.6,
                'students_count' => 5200,
                'instructor' => 'Prof. Hendra Wijaya',
                'thumbnail_color' => '#1a1a40',
                'icon_text' => 'AG',
                'is_featured' => false,
                'is_free' => false,
                'description' => 'Bangun sistem multi-agent conversational tingkat lanjut menggunakan Microsoft AutoGen.',
                'lessons' => [
                    ['title' => 'Pengantar AutoGen', 'duration_minutes' => 15, 'type' => 'video', 'is_free_preview' => true],
                    ['title' => 'UserProxy dan AssistantAgent', 'duration_minutes' => 25, 'type' => 'video'],
                    ['title' => 'Group Chat', 'duration_minutes' => 30, 'type' => 'video'],
                    ['title' => 'Tool Integration', 'duration_minutes' => 40, 'type' => 'video'],
                    ['title' => 'Proyek Akhir', 'duration_minutes' => 60, 'type' => 'video'],
                ]
            ],
            [
                'title' => 'Monitoring DataCamp dengan LangSmith',
                'category' => 'Monitoring',
                'difficulty' => 'Pemula',
                'duration_hours' => 3,
                'rating' => 4.7,
                'students_count' => 6800,
                'instructor' => 'Nina Putri',
                'thumbnail_color' => '#2a1010',
                'icon_text' => 'LS',
                'is_featured' => false,
                'is_free' => true,
                'description' => 'Pelajari cara monitoring, debugging, dan evaluasi aplikasi LLM menggunakan LangSmith.',
                'lessons' => [
                    ['title' => 'Setup LangSmith', 'duration_minutes' => 10, 'type' => 'video', 'is_free_preview' => true],
                    ['title' => 'Tracing dan Logging', 'duration_minutes' => 20, 'type' => 'video'],
                    ['title' => 'Evaluasi Model', 'duration_minutes' => 25, 'type' => 'video'],
                    ['title' => 'Dashboard Monitoring', 'duration_minutes' => 20, 'type' => 'video'],
                ]
            ],
        ];

        foreach ($courses as $courseData) {
            $lessons = $courseData['lessons'];
            unset($courseData['lessons']);

            $course = Course::create(array_merge($courseData, [
                'slug' => Str::slug($courseData['title']),
                'total_lessons' => count($lessons),
            ]));

            foreach ($lessons as $index => $lesson) {
                Lesson::create(array_merge($lesson, [
                    'course_id' => $course->id,
                    'order' => $index + 1,
                    'content' => 'Konten untuk ' . $lesson['title'],
                ]));
            }
        }
    }
}