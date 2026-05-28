<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel FAQ
        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->string('seksi', 100)->default('The basics');
                $table->string('pertanyaan', 500);
                $table->text('jawaban');
                $table->integer('urutan')->default(0);
                $table->timestamps();
            });
        }

        // Tabel Feedback - tanpa foreign key constraint
        if (!Schema::hasTable('feedbacks')) {
            Schema::create('feedbacks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable(); // tanpa FK constraint
                $table->string('halaman', 100)->default('certification');
                $table->text('isi_feedback');
                $table->timestamps();
            });
        }

        // Seed FAQ data (hanya jika belum ada data)
        if (DB::table('faqs')->count() === 0) {
            $faqs = [
                ['seksi' => 'The basics', 'pertanyaan' => 'What is Datacamp Certification?', 'jawaban' => 'DataCamp Certification is an official recognition that you\'ve achieved the required skill level in a role or with a technology. We measure your abilities through timed exams and a practical exam based on scenarios you\'re likely to find in the workplace.', 'urutan' => 1],
                ['seksi' => 'The basics', 'pertanyaan' => 'Do I need a subscription to access certification?', 'jawaban' => 'Yes, DataCamp Certification is currently only available to individual subscribers and members of business subscriptions.', 'urutan' => 2],
                ['seksi' => 'The basics', 'pertanyaan' => 'Who is eligible for certification?', 'jawaban' => 'DataCamp Certification is available to all DataCamp subscribers. You need an active subscription to attempt any certification exam.', 'urutan' => 3],
                ['seksi' => 'The basics', 'pertanyaan' => 'How long does the certification take?', 'jawaban' => 'The certification process consists of two parts: a timed exam (90 minutes) and a practical exam (24 hours to complete).', 'urutan' => 4],

                ['seksi' => 'Expectations', 'pertanyaan' => 'What does the certification process look like?', 'jawaban' => 'The certification process has two stages: first a timed theory exam with multiple choice questions, followed by a practical exam where you analyze a real dataset and answer questions based on your findings.', 'urutan' => 1],
                ['seksi' => 'Expectations', 'pertanyaan' => 'How is the practical exam graded?', 'jawaban' => 'Your practical exam is reviewed by our team of data experts. They assess your ability to apply skills in realistic scenarios, the quality of your analysis, and how well you communicate your findings.', 'urutan' => 2],
                ['seksi' => 'Expectations', 'pertanyaan' => 'What happens if I fail the exam?', 'jawaban' => 'If you don\'t pass, you\'ll receive feedback on areas to improve. You can retake the exam after a waiting period. We recommend reviewing the relevant DataCamp courses before attempting again.', 'urutan' => 3],

                ['seksi' => 'Preparing for exams', 'pertanyaan' => 'How should I prepare for the certification exam?', 'jawaban' => 'We recommend completing the relevant DataCamp career tracks and skill tracks before attempting the exam. Each certification page has a recommended preparation path.', 'urutan' => 1],
                ['seksi' => 'Preparing for exams', 'pertanyaan' => 'Are there practice exams available?', 'jawaban' => 'Yes, DataCamp offers practice exams and assessments that help you gauge your readiness. You can find these in the Assessments section of the platform.', 'urutan' => 2],
                ['seksi' => 'Preparing for exams', 'pertanyaan' => 'How long should I study before attempting the exam?', 'jawaban' => 'This varies by individual. If you\'re new to the subject, we recommend completing the full career track (typically 60-100 hours of content).', 'urutan' => 3],

                ['seksi' => 'Results & credentials', 'pertanyaan' => 'How do I receive my certification?', 'jawaban' => 'Once you pass both parts of the exam, you\'ll receive a digital certificate that you can share on LinkedIn and other professional networks.', 'urutan' => 1],
                ['seksi' => 'Results & credentials', 'pertanyaan' => 'Do certifications expire?', 'jawaban' => 'DataCamp certifications are currently valid for 2 years from the date of issue. After expiration, you\'ll need to retake the exam to renew your certification.', 'urutan' => 2],
                ['seksi' => 'Results & credentials', 'pertanyaan' => 'Can I share my certification on LinkedIn?', 'jawaban' => 'Yes! You can add your DataCamp certification directly to your LinkedIn profile. Each certificate comes with a unique verification link that employers can use to confirm your credential.', 'urutan' => 3],
            ];

            foreach ($faqs as $faq) {
                DB::table('faqs')->insert(array_merge($faq, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('faqs');
    }
};