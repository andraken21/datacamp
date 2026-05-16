<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TutorialController extends Controller
{
    // URL FastAPI — sesuaikan kalau beda port/host
    private string $apiBase = 'http://127.0.0.1:8000';

    // ── Halaman daftar tutorial ──────────────────────────────
    public function index(Request $request)
    {
        $params = [
            'page'     => $request->get('page', 1),
            'per_page' => $request->get('per_page', 20),
            'search'   => $request->get('search'),
            'category' => $request->get('category'),
            'author'   => $request->get('author'),
        ];

        // hapus param kosong supaya tidak dikirim ke API
        $params = array_filter($params, fn($v) => $v !== null && $v !== '');

        try {
            $response = Http::timeout(10)->get("{$this->apiBase}/tutorials", $params);
            $data     = $response->json();
        } catch (\Exception $e) {
            $data = ['total' => 0, 'data' => [], 'total_pages' => 0, 'page' => 1];
        }

        // ambil kategori untuk dropdown filter
        try {
            $categories = Http::timeout(5)->get("{$this->apiBase}/categories")->json()['categories'] ?? [];
        } catch (\Exception $e) {
            $categories = [];
        }

        return view('tutorials.index', [
            'tutorials'   => $data['data']        ?? [],
            'total'       => $data['total']        ?? 0,
            'currentPage' => $data['page']         ?? 1,
            'totalPages'  => $data['total_pages']  ?? 0,
            'categories'  => $categories,
            'filters'     => $request->only(['search', 'category', 'author']),
        ]);
    }

    // ── Halaman detail tutorial ──────────────────────────────
    public function show(string $slug)
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiBase}/tutorials/{$slug}");

            if ($response->status() === 404) {
                abort(404, 'Tutorial tidak ditemukan');
            }

            $tutorial = $response->json();
        } catch (\Exception $e) {
            abort(503, 'Gagal terhubung ke API');
        }

        return view('tutorials.show', compact('tutorial'));
    }

    // ── Trigger scraping (dari tombol admin) ─────────────────
    public function scrape(Request $request)
    {
        $max = $request->input('max_tutorials', 350);

        try {
            $response = Http::timeout(10)->post("{$this->apiBase}/scrape", [
                'max_tutorials' => (int) $max,
            ]);
            $result = $response->json();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke Python API: ' . $e->getMessage());
        }

        if ($result['success'] ?? false) {
            return back()->with('success', 'Scraping dimulai! Cek status di halaman status.');
        }

        return back()->with('error', $result['message'] ?? 'Gagal memulai scraping');
    }

    // ── Cek status scraping (AJAX polling) ──────────────────
    public function status()
    {
        try {
            $response = Http::timeout(5)->get("{$this->apiBase}/status");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'API tidak bisa dihubungi'], 503);
        }
    }
}