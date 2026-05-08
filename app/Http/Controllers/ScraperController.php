<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScraperController extends Controller {

    public function index() {
        return view('scraper');
    }

    public function run(Request $request) {
    $source = $request->source ?? 'all';
    
    // Kata-kata yang harus diblokir
    $blacklist = [
        'training', 'prerequisites', 'instructors', 'track suitable',
        'programming language', 'jobs will benefit', 'prepare me',
        'how long', 'difference between', 'create your free',
        'sign up', 'log in', 'get started', 'learn more',
        'cookie', 'privacy', 'terms', 'contact', 'about',
        'what is the', 'which jobs', 'how will', 'is this track',
        'whats the', 'what\'s the',
    ];

    try {
        $url = "http://127.0.0.1:8001/scrape/{$source}";
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (!$data || $data['status'] !== 'success') {
            return back()->with('error', 'Gagal mengambil data dari scraper.');
        }

        $saved = 0;
        $skipped = 0;
        $filtered = 0;

        foreach ($data['data'] as $item) {
            $nameLower = strtolower($item['name']);
            
            // Filter nama yang tidak relevan
            $isBlacklisted = false;
            foreach ($blacklist as $word) {
                if (str_contains($nameLower, $word)) {
                    $isBlacklisted = true;
                    break;
                }
            }

            // Skip kalau nama terlalu pendek atau di blacklist
            if ($isBlacklisted || strlen($item['name']) < 5) {
                $filtered++;
                continue;
            }

            $slug = $item['slug'];
            $existing = Tool::where('slug', $slug)->first();
            
            if ($existing) {
                $existing->update([
                    'stars_github' => $item['stars_github'],
                    'description'  => $item['description'],
                ]);
                $skipped++;
            } else {
                Tool::create([
                    'name'         => $item['name'],
                    'slug'         => $slug,
                    'description'  => $item['description'],
                    'category'     => $item['category'],
                    'language'     => $item['language'],
                    'difficulty'   => $item['difficulty'],
                    'rating'       => $item['rating'],
                    'stars_github' => $item['stars_github'],
                    'source_url'   => $item['source_url'],
                    'icon_text'    => $item['icon_text'],
                    'icon_color'   => $item['icon_color'],
                    'tags'         => $item['tags'],
                    'is_featured'  => $item['is_featured'],
                ]);
                $saved++;
            }
        }

        return back()->with('success', "Berhasil! {$saved} tool baru disimpan, {$skipped} diperbarui, {$filtered} difilter.");

    } catch (\Exception $e) {
        return back()->with('error', 'Error: '.$e->getMessage());
    }
}
}