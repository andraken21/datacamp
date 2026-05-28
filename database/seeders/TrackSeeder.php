<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TrackSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTracks(base_path('career_tracks.csv'), 'Career Track');
        $this->seedTracks(base_path('skill_tracks.csv'),  'Skill Track');
        $this->seedTrackCourse(base_path('career_tracks.csv'));
        $this->seedTrackCourse(base_path('skill_tracks.csv'));
    }
    private function seedTracks(string $file, string $jenis): void
    {
        if (!file_exists($file)) { $this->command->warn("File tidak ditemukan: $file"); return; }
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            DB::table('track')->updateOrInsert(
                ['slug' => $data['slug']],
                [
                    'jenis_track'   => $jenis,
                    'nama_track'    => $data['name'] ?? '',
                    'slug'          => $data['slug'] ?? '',
                    'url'           => $data['url'] ?? null,
                    'deskripsi'     => $data['description'] ?? null,
                    'teknologi'     => $data['technology'] ?? null,
                    'durasi_jam'    => is_numeric($data['duration_hours'] ?? '') ? (int)$data['duration_hours'] : null,
                    'total_kursus'  => is_numeric($data['total_courses'] ?? '') ? (int)$data['total_courses'] : null,
                    'total_proyek'  => is_numeric($data['total_projects'] ?? '') ? (int)$data['total_projects'] : null,
                    'total_asesmen' => is_numeric($data['total_assessments'] ?? '') ? (int)$data['total_assessments'] : null,
                    'total_peserta' => is_numeric($data['total_participants'] ?? '') ? (int)$data['total_participants'] : null,
                ]
            );
        }
        fclose($handle);
        $this->command->info("Selesai seed '$jenis'.");
    }
    private function seedTrackCourse(string $file): void
    {
        if (!file_exists($file)) return;
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle);
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            $track = DB::table('track')->where('slug', $data['slug'])->first();
            if (!$track) continue;
            $courseNames = array_map('trim', explode('|', $data['courses'] ?? ''));
            foreach ($courseNames as $urutan => $nama) {
                if (empty($nama)) continue;
                $course = DB::table('courses')->where('nama_course', $nama)->orWhere('title', $nama)->first();
                if (!$course) continue;
                DB::table('track_course')->updateOrInsert(
                    ['track_id' => $track->track_id, 'course_id' => $course->course_id],
                    ['urutan_kursus' => $urutan + 1]
                );
            }
        }
        fclose($handle);
    }
}
