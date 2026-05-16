<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lessons')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        $lessons = [
            // Course 1: Pengantar LangChain untuk Pemula
            1 => [
                [
                    'title' => 'Apa itu LangChain?',
                    'content' => 'LangChain adalah framework open-source yang memudahkan pengembangan aplikasi berbasis Large Language Model (LLM). Dengan LangChain, kamu bisa menghubungkan model AI seperti GPT dengan sumber data eksternal, tools, dan memory. Framework ini mendukung Python dan JavaScript, dan menjadi standar industri untuk membangun aplikasi AI modern.',
                    'video_url' => 'https://www.youtube.com/embed/1bUy-1hGZpI',
                    'duration_minutes' => 10,
                    'order' => 1,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Instalasi dan Setup Environment',
                    'content' => 'Pada lesson ini kita akan menginstall LangChain dan dependensinya menggunakan pip. Kita juga akan setup API key OpenAI dan mengkonfigurasi environment variable menggunakan file .env. Pastikan Python 3.8+ sudah terinstall di komputer kamu sebelum memulai.',
                    'video_url' => 'https://www.youtube.com/embed/aywZrzNaKjs',
                    'duration_minutes' => 15,
                    'order' => 2,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Membuat Chain Pertama',
                    'content' => 'Chain adalah konsep inti di LangChain — yaitu rangkaian komponen yang bekerja secara berurutan. Kita akan membuat LLMChain sederhana yang menerima input pengguna, memprosesnya dengan prompt template, lalu mengirimkan ke model GPT dan menampilkan hasilnya.',
                    'video_url' => 'https://www.youtube.com/embed/lG7Uxts9SXs',
                    'duration_minutes' => 20,
                    'order' => 3,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Prompt Template dan Output Parser',
                    'content' => 'Prompt Template memungkinkan kita membuat prompt yang dinamis dan dapat digunakan ulang. Output Parser membantu mengubah respons model menjadi format yang terstruktur seperti JSON atau list. Kombinasi keduanya membuat aplikasi AI lebih robust dan mudah dipelihara.',
                    'video_url' => 'https://www.youtube.com/embed/RoHBFbMFMTI',
                    'duration_minutes' => 20,
                    'order' => 4,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Quiz: Dasar-dasar LangChain',
                    'content' => 'Uji pemahaman kamu tentang konsep dasar LangChain meliputi: pengertian Chain, cara kerja Prompt Template, fungsi Output Parser, dan instalasi environment. Kerjakan dengan teliti!',
                    'video_url' => null,
                    'duration_minutes' => 10,
                    'order' => 5,
                    'type' => 'quiz',
                    'is_free_preview' => false,
                ],
            ],

            // Course 2: Multi-Agent dengan CrewAI
            2 => [
                [
                    'title' => 'Pengenalan Multi-Agent System',
                    'content' => 'Multi-Agent System adalah arsitektur di mana beberapa DataCamp bekerja sama untuk menyelesaikan tugas kompleks. Setiap agent memiliki peran, tujuan, dan kemampuan yang berbeda. CrewAI mengimplementasikan konsep ini dengan pendekatan role-playing yang intuitif dan mudah dikonfigurasi.',
                    'video_url' => 'https://www.youtube.com/embed/tnejrr-0a94',
                    'duration_minutes' => 12,
                    'order' => 1,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Instalasi CrewAI dan Konfigurasi',
                    'content' => 'Kita akan menginstall CrewAI menggunakan pip dan mengkonfigurasi model LLM yang akan digunakan. CrewAI mendukung berbagai model seperti GPT-4, Claude, dan model lokal via Ollama. Kita juga akan membuat struktur project yang baik untuk pengembangan multi-agent.',
                    'video_url' => 'https://www.youtube.com/embed/sPzc6hMg7So',
                    'duration_minutes' => 15,
                    'order' => 2,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Membuat Agent dan Mendefinisikan Role',
                    'content' => 'Setiap agent di CrewAI memiliki role (peran), goal (tujuan), dan backstory (latar belakang). Ketiga elemen ini membentuk kepribadian dan kemampuan agent. Kita akan membuat beberapa agent dengan peran berbeda seperti Researcher, Writer, dan Analyst.',
                    'video_url' => 'https://www.youtube.com/embed/Jl6nCRVEdLk',
                    'duration_minutes' => 25,
                    'order' => 3,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Task dan Crew Orchestration',
                    'content' => 'Task adalah unit kerja yang diberikan kepada agent. Crew adalah kumpulan agent yang bekerja bersama. Kita akan belajar cara mendefinisikan task dengan deskripsi yang jelas, menentukan agent yang bertanggung jawab, dan mengatur alur kerja antar agent secara sequential maupun parallel.',
                    'video_url' => 'https://www.youtube.com/embed/TA9GhDCBsv8',
                    'duration_minutes' => 25,
                    'order' => 4,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Studi Kasus: Tim Riset AI',
                    'content' => 'Pada lesson ini kita akan membangun sistem multi-agent lengkap yang mensimulasikan tim riset. Ada agent Researcher yang mencari informasi, agent Analyst yang menganalisis data, dan agent Writer yang menulis laporan. Proyek ini menggabungkan semua konsep yang telah dipelajari.',
                    'video_url' => 'https://www.youtube.com/embed/kJvXT25LkwA',
                    'duration_minutes' => 30,
                    'order' => 5,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
            ],

            // Course 3: RAG dengan LlamaIndex
            3 => [
                [
                    'title' => 'Apa itu RAG (Retrieval Augmented Generation)?',
                    'content' => 'RAG adalah teknik yang menggabungkan kemampuan retrieval (pencarian) dengan generasi teks oleh LLM. Alih-alih hanya mengandalkan pengetahuan model, RAG memungkinkan model mengakses dokumen eksternal secara real-time. Hasilnya adalah jawaban yang lebih akurat, terkini, dan dapat diverifikasi sumbernya.',
                    'video_url' => 'https://www.youtube.com/embed/T-D1OfcDW1M',
                    'duration_minutes' => 12,
                    'order' => 1,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Setup LlamaIndex dan Vector Store',
                    'content' => 'LlamaIndex adalah framework khusus untuk membangun aplikasi RAG. Kita akan menginstall LlamaIndex, mengkonfigurasi embedding model, dan menyiapkan vector store menggunakan ChromaDB. Vector store adalah tempat menyimpan representasi numerik dari dokumen kita.',
                    'video_url' => 'https://www.youtube.com/embed/cNMYeW2mpBs',
                    'duration_minutes' => 18,
                    'order' => 2,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Indexing Dokumen dan Chunking Strategy',
                    'content' => 'Indexing adalah proses mengubah dokumen menjadi vektor yang dapat dicari. Chunking adalah cara memecah dokumen menjadi potongan-potongan kecil. Kita akan mempelajari berbagai strategi chunking seperti fixed-size, semantic, dan recursive chunking untuk hasil RAG yang optimal.',
                    'video_url' => 'https://www.youtube.com/embed/8OJC21T2SL4',
                    'duration_minutes' => 22,
                    'order' => 3,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Query Engine dan Retrieval',
                    'content' => 'Query Engine adalah komponen yang menerima pertanyaan pengguna, mencari dokumen yang relevan di vector store, lalu menggabungkannya dengan LLM untuk menghasilkan jawaban. Kita akan mengkonfigurasi similarity threshold, top-k retrieval, dan re-ranking untuk meningkatkan kualitas jawaban.',
                    'video_url' => 'https://www.youtube.com/embed/TRjq7t2Ms5I',
                    'duration_minutes' => 25,
                    'order' => 4,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Membangun Chatbot dengan RAG',
                    'content' => 'Di lesson terakhir ini kita akan membangun chatbot yang dapat menjawab pertanyaan berdasarkan dokumen PDF yang kita upload. Chatbot ini menggunakan conversation memory sehingga bisa mengingat konteks percakapan sebelumnya. Ini adalah implementasi RAG yang siap digunakan di dunia nyata.',
                    'video_url' => 'https://www.youtube.com/embed/hW2KBMcS5wk',
                    'duration_minutes' => 30,
                    'order' => 5,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
            ],

            // Course 4: AutoGen: Conversational Agents
            4 => [
                [
                    'title' => 'Pengenalan Microsoft AutoGen',
                    'content' => 'AutoGen adalah framework dari Microsoft Research untuk membangun sistem multi-agent conversational. Keunggulan AutoGen adalah kemampuan agent untuk berdiskusi satu sama lain secara otomatis hingga mencapai solusi terbaik. Framework ini sangat cocok untuk tugas-tugas kompleks yang membutuhkan reasoning mendalam.',
                    'video_url' => 'https://www.youtube.com/embed/vU2S6dVf79M',
                    'duration_minutes' => 15,
                    'order' => 1,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'AssistantAgent dan UserProxyAgent',
                    'content' => 'AutoGen memiliki dua jenis agent utama: AssistantAgent yang berperan sebagai AI asisten, dan UserProxyAgent yang mewakili pengguna. UserProxyAgent juga dapat mengeksekusi kode secara otomatis. Kita akan membuat kedua jenis agent ini dan melihat bagaimana mereka berinteraksi.',
                    'video_url' => 'https://www.youtube.com/embed/V2qZ_lgxTzg',
                    'duration_minutes' => 20,
                    'order' => 2,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Group Chat dan Agent Orchestration',
                    'content' => 'GroupChat memungkinkan lebih dari dua agent berdiskusi dalam satu sesi. GroupChatManager bertugas mengatur giliran bicara setiap agent. Kita akan membangun sistem dengan tiga agent: Planner, Coder, dan Critic yang bekerja sama menyelesaikan tugas programming.',
                    'video_url' => 'https://www.youtube.com/embed/4ZqJSfV4818',
                    'duration_minutes' => 25,
                    'order' => 3,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Code Execution dan Tool Integration',
                    'content' => 'Salah satu fitur paling powerful AutoGen adalah kemampuan mengeksekusi kode Python secara otomatis. Agent dapat menulis kode, menjalankannya, melihat hasilnya, lalu memperbaiki jika ada error — semua tanpa intervensi manusia. Kita juga akan mengintegrasikan tools eksternal seperti web search.',
                    'video_url' => 'https://www.youtube.com/embed/RLwyXRVvlNk',
                    'duration_minutes' => 30,
                    'order' => 4,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Studi Kasus: Automated Data Analysis',
                    'content' => 'Kita akan membangun sistem AutoGen yang dapat melakukan analisis data secara otomatis. Pengguna cukup mengupload dataset CSV, lalu sistem akan menganalisis, membuat visualisasi, dan menghasilkan laporan secara otomatis menggunakan kolaborasi beberapa agent.',
                    'video_url' => 'https://www.youtube.com/embed/pAFnGFpGtzE',
                    'duration_minutes' => 35,
                    'order' => 5,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
            ],

            // Course 5: Monitoring DataCamp dengan LangSmith
            5 => [
                [
                    'title' => 'Mengapa Monitoring DataCamp Penting?',
                    'content' => 'Aplikasi AI yang sudah di-deploy perlu dimonitor secara berkelanjutan. Tanpa monitoring, kita tidak tahu apakah model memberikan jawaban yang akurat, berapa biaya yang dikeluarkan, atau di mana bottleneck performa terjadi. LangSmith hadir sebagai solusi observability khusus untuk aplikasi LLM.',
                    'video_url' => 'https://www.youtube.com/embed/tFXm5ijih98',
                    'duration_minutes' => 10,
                    'order' => 1,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Setup LangSmith dan Tracing',
                    'content' => 'Kita akan membuat akun LangSmith, mendapatkan API key, dan mengintegrasikannya ke aplikasi LangChain yang sudah ada. Dengan beberapa baris kode, semua aktivitas agent akan otomatis ter-trace dan dapat dilihat di dashboard LangSmith secara real-time.',
                    'video_url' => 'https://www.youtube.com/embed/Hab2CV_0hpQ',
                    'duration_minutes' => 15,
                    'order' => 2,
                    'type' => 'video',
                    'is_free_preview' => true,
                ],
                [
                    'title' => 'Evaluasi dan Testing dengan LangSmith',
                    'content' => 'LangSmith menyediakan fitur evaluasi untuk mengukur kualitas output model secara sistematis. Kita akan membuat dataset evaluasi, mendefinisikan kriteria penilaian, dan menjalankan evaluasi otomatis. Hasilnya dapat digunakan untuk membandingkan performa antar versi model.',
                    'video_url' => 'https://www.youtube.com/embed/pinBvQpFeFc',
                    'duration_minutes' => 20,
                    'order' => 3,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
                [
                    'title' => 'Debugging dan Optimasi Performa',
                    'content' => 'Dengan LangSmith, kita dapat melihat detail setiap langkah yang dilakukan agent — mulai dari prompt yang dikirim, respons yang diterima, hingga tools yang dipanggil. Informasi ini sangat berguna untuk debugging masalah dan mengoptimasi prompt agar lebih efisien dan hemat biaya.',
                    'video_url' => 'https://www.youtube.com/embed/SW4nYhJpFBY',
                    'duration_minutes' => 20,
                    'order' => 4,
                    'type' => 'video',
                    'is_free_preview' => false,
                ],
            ],
        ];

        foreach ($lessons as $courseId => $courseLessons) {
            foreach ($courseLessons as $lesson) {
                DB::table('lessons')->insert([
                    'course_id' => $courseId,
                    'title' => $lesson['title'],
                    'content' => $lesson['content'],
                    'video_url' => $lesson['video_url'],
                    'duration_minutes' => $lesson['duration_minutes'],
                    'order' => $lesson['order'],
                    'type' => $lesson['type'],
                    'is_free_preview' => $lesson['is_free_preview'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $this->command->info('✅ Lessons berhasil di-seed!');
    }
}