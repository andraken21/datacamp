<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SertifikasiPowerBISeeder extends Seeder
{
    public function run(): void
    {
        // ── Cari data lama jika ada ─────────────────────────────────
        $existing = DB::table('sertifikasi')->where('slug', 'power-bi-pl-300')->first();

        if ($existing) {
            // Hapus child tables dulu (urutan penting!)
            DB::table('sertifikasi_faq')    ->where('sertifikasi_id', $existing->id)->delete();
            DB::table('sertifikasi_section')->where('sertifikasi_id', $existing->id)->delete();
            DB::table('sertifikasi_topik')  ->where('sertifikasi_id', $existing->id)->delete();
            // Baru hapus parent
            DB::table('sertifikasi')->where('id', $existing->id)->delete();
        }

        // ── Cari id berikutnya secara manual ────────────────────────
        $nextId = (DB::table('sertifikasi')->max('id') ?? 0) + 1;

        // ── 1. Insert sertifikasi ───────────────────────────────────
        DB::table('sertifikasi')->insert([
            'id'             => $nextId,
            'sertifikasi_id' => null,
            'jenis'          => 'Technology',
            'nama'           => 'Exam PL-300: Microsoft Power BI Data Analyst',
            'tipe'           => 'Exam',
            'promo'          => '50% off Microsoft Exam through DataCamp & Microsoft partnership',
            'deskripsi'      => 'This exam measures your ability to accomplish the following technical tasks in Power BI: prepare the data; model the data; visualize and analyze the data; and deploy and maintain assets.',
            'panduan'        => '30 days',
            'url'            => 'https://learn.microsoft.com/en-us/certifications/exams/pl-300',
            'slug'           => 'power-bi-pl-300',
            'nama_peran'     => 'Data Analyst',
            'dibuat_oleh'    => 'DataCamp',
            'topik_tercakup' => json_encode(['Data Analysis', 'SQL', 'Python']),
            'konten_faq'     => null,
            'konten_detail'  => json_encode([
                'certifications_awarded' => 16769,
                'shareable_certificate'  => true,
                'microsoft_partnership'  => true,
                'career_track_url'       => '/learn/career-tracks/data-analyst-in-power-bi',
            ]),
        ]);

        $id = $nextId;

        // ── 2. Insert sertifikasi_topik ─────────────────────────────
        foreach (['Data Analysis', 'SQL', 'Python'] as $topik) {
            DB::table('sertifikasi_topik')->insert([
                'sertifikasi_id' => $id,
                'topik'          => $topik,
            ]);
        }

        // ── 3. Insert sertifikasi_section ───────────────────────────
        $gains = [
            'Prepare and model data using Power Query and DAX',
            'Create and format visualizations and reports',
            'Deploy and maintain assets in Power BI service',
            'Perform data analysis and identify patterns',
        ];
        foreach ($gains as $i => $konten) {
            DB::table('sertifikasi_section')->insert([
                'sertifikasi_id' => $id,
                'judul_section'  => 'what_you_gain',
                'konten'         => $konten,
                'urutan'         => $i + 1,
            ]);
        }

        // ── 4. Insert sertifikasi_faq ───────────────────────────────
        $faqs = [
            [1, 'What is the Microsoft PL-300 Data Analyst Certification?',
                'The PL-300 examination is an official paid certification from Microsoft. This exam measures your ability to accomplish the following technical tasks in Power BI: prepare the data; model the data; visualize and analyze the data; and deploy and maintain assets. Microsoft and DataCamp offer learners a discount code for the official PL-300 certification.'],
            [2, 'How does the Data Analyst in Power BI track prepare you for the certification?',
                'The Data Analyst In Power BI track has been developed in collaboration with Microsoft to help you pass the PL-300 certification. It prepares you for the certification by teaching you hands-on skills related to Power Query, DAX, data modelling, and more.'],
            [3, 'Who is the certification for?',
                'PL-300 is a professional certification in Microsoft Power BI for people working as (or wishing to work as) a data analyst or business analyst. It\'s a great certification to show off your Power BI skills to employers!'],
            [4, 'How can I earn the 50% discount?',
                'Once you complete the full <a href="#" style="color:#05c46b">Data Analyst In Power BI</a> career track, you will become eligible to receive a 50% discount code via email. The email will come from DataCamp and include information provided by Microsoft. For context, the track consists of 17 courses with an estimated completion time of 48 hours.<br><br><em>Please note that this offer is exclusively available to premium DataCamp subscribers.</em>'],
            [5, 'How will I receive the discount?',
                'Microsoft sends codes in batches, which DataCamp distributes via email. Once you are eligible for the discount, you will be included in the next batch. It can take up to 3 days after completing the final course of the track to receive your discount code. Please contact DataCamp Support if you haven\'t received your code within 3 days.'],
            [6, 'How long before the discount expires?',
                'Microsoft will list an expiration date in the email that you receive. You will have between 3 and 6 months to use the discount code.'],
            [7, 'How can I redeem the voucher?',
                'You can review the article from the Microsoft team <a href="#" style="color:#05c46b">here</a>.'],
        ];
        foreach ($faqs as [$urutan, $pertanyaan, $jawaban]) {
            DB::table('sertifikasi_faq')->insert([
                'sertifikasi_id' => $id,
                'pertanyaan'     => $pertanyaan,
                'jawaban'        => $jawaban,
                'urutan'         => $urutan,
            ]);
        }

        $this->command->info("✅ Power BI PL-300 seeded! (id: $id, slug: power-bi-pl-300)");
    }
}