<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TranslateQuestions extends Command
{
    protected $signature   = 'questions:translate {--batch=20 : Jumlah soal per batch} {--dry-run : Preview tanpa update DB}';
    protected $description = 'Translate opsi_2 dan opsi_3 practice_question dari Indonesia ke Inggris';

    // Kata-kata Indonesia yang menandakan perlu ditranslate
    private array $idWords = [
        'ini', 'yang', 'untuk', 'dengan', 'dari', 'tidak', 'dalam',
        'atau', 'dan', 'adalah', 'lebih', 'akan', 'pada', 'juga',
        'dapat', 'karena', 'namun', 'tetapi', 'sehingga', 'opsi',
        'jawaban', 'pilihan', 'pendekatan', 'metode', 'solusi',
        'menggunakan', 'menghasilkan', 'memerlukan', 'mengandung',
        'berbeda', 'efisien', 'relevan', 'optimal', 'alternatif',
    ];

    public function handle(): int
    {
        $batchSize = (int) $this->option('batch');
        $dryRun    = $this->option('dry-run');

        $this->info('=== Practice Question Translator ===');
        if ($dryRun) $this->warn('[DRY RUN - tidak ada perubahan ke DB]');

        // Ambil semua soal
        $questions = DB::table('practice_question')
            ->select('question_id', 'opsi_1', 'opsi_2', 'opsi_3')
            ->get();

        // Filter yang perlu ditranslate
        $needsTranslation = $questions->filter(function ($q) {
            return $this->isIndonesian($q->opsi_2) || $this->isIndonesian($q->opsi_3);
        });

        $total = $needsTranslation->count();
        $this->info("Total soal: {$questions->count()}");
        $this->info("Perlu ditranslate: {$total}");

        if ($total === 0) {
            $this->info('Semua soal sudah dalam bahasa Inggris!');
            return 0;
        }

        if (!$this->confirm("Lanjutkan translate {$total} soal? (batch size: {$batchSize})")) {
            return 0;
        }

        $chunks    = $needsTranslation->chunk($batchSize);
        $processed = 0;
        $failed    = 0;

        foreach ($chunks as $chunkIndex => $chunk) {
            $this->info("\nBatch " . ($chunkIndex + 1) . "/" . $chunks->count() . " ...");

            // Buat payload untuk Claude
            $payload = $chunk->map(function ($q) {
                return [
                    'id'     => $q->question_id,
                    'opsi_2' => $this->isIndonesian($q->opsi_2) ? $q->opsi_2 : null,
                    'opsi_3' => $this->isIndonesian($q->opsi_3) ? $q->opsi_3 : null,
                ];
            })->values()->toArray();

            $translated = $this->translateBatch($payload);

            if (!$translated) {
                $this->error("Batch {$chunkIndex} gagal, skip...");
                $failed += $chunk->count();
                continue;
            }

            // Update DB
            foreach ($translated as $item) {
                if ($dryRun) {
                    $this->line("  [DRY] ID {$item['id']}: opsi_2={$item['opsi_2']} | opsi_3={$item['opsi_3']}");
                    continue;
                }

                $update = [];
                if ($item['opsi_2']) $update['opsi_2'] = $item['opsi_2'];
                if ($item['opsi_3']) $update['opsi_3'] = $item['opsi_3'];

                if (!empty($update)) {
                    DB::table('practice_question')
                        ->where('question_id', $item['id'])
                        ->update($update);
                }
                $processed++;
            }

            $this->line("  ✓ {$chunk->count()} soal diproses");

            // Delay antar batch agar tidak kena rate limit
            if ($chunkIndex < $chunks->count() - 1) {
                sleep(1);
            }
        }

        $this->newLine();
        $this->info("=== Selesai ===");
        $this->info("Berhasil diupdate : {$processed}");
        if ($failed > 0) $this->warn("Gagal            : {$failed}");

        return 0;
    }

    /**
     * Kirim batch ke Claude API dan minta translate JSON
     */
    private function translateBatch(array $items): ?array
    {
        $json = json_encode($items, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $prompt = <<<PROMPT
You are a professional translator. Translate ONLY the Indonesian text to English.
Keep English text exactly as-is (do not modify it).
Return ONLY valid JSON array, no markdown, no explanation.

Input JSON array (each item has id, opsi_2, opsi_3 — null means already English, keep null):
{$json}

Rules:
- Translate naturally, keep technical/programming terms as-is
- Return same JSON structure with translated values
- null stays null
- Return ONLY the JSON array, nothing else
PROMPT;

        try {
            $response = Http::withHeaders([
                'Content-Type'      => 'application/json',
                'x-api-key'         => config('services.anthropic.key', env('ANTHROPIC_API_KEY')),
                'anthropic-version' => '2023-06-01',
            ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-haiku-4-5-20251001',
                'max_tokens' => 4096,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if (!$response->successful()) {
                $this->error('API error: ' . $response->body());
                return null;
            }

            $content = $response->json('content.0.text', '');

            // Bersihkan kalau ada markdown code block
            $content = preg_replace('/```json\s*|\s*```/', '', trim($content));

            return json_decode($content, true);

        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Deteksi apakah string mengandung kata Indonesia
     */
    private function isIndonesian(?string $text): bool
    {
        if (!$text) return false;
        $lower = strtolower($text);
        foreach ($this->idWords as $word) {
            if (str_contains($lower, ' ' . $word . ' ')
                || str_starts_with($lower, $word . ' ')
                || str_ends_with($lower, ' ' . $word)) {
                return true;
            }
        }
        return false;
    }
}