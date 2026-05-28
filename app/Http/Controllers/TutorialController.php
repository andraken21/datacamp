<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TutorialController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('tutorial as t')
                   ->leftJoin('tutorial_author as a', 't.author_id', '=', 'a.author_id')
                   ->leftJoin('topik as tp', 't.topik_id', '=', 'tp.topik_id')
                   ->select('t.*', 'a.nama_author as author', 'tp.nama_topik as category');

        if ($request->search) {
            $query->where('t.judul', 'like', '%'.$request->search.'%');
        }

        if ($request->category) {
            $query->where('tp.nama_topik', $request->category);
        }

        $total = $query->count();
        $perPage = 20;
        $currentPage = (int) $request->get('page', 1);

        $tutorials = $query->orderByDesc('t.tanggal_terbit')
                           ->offset(($currentPage - 1) * $perPage)
                           ->limit($perPage)
                           ->get()
                           ->map(fn($t) => [
                               'slug'        => $t->slug,
                               'title'       => $t->judul,
                               'category'    => $t->category,
                               'author'      => $t->author,
                               'read_time'   => $t->waktu_baca_menit . ' min read',
                               'description' => '',
                           ])->toArray();

        return view('tutorials.index', [
            'tutorials'   => $tutorials,
            'total'       => $total,
            'currentPage' => $currentPage,
            'totalPages'  => ceil($total / $perPage),
            'filters'     => $request->only(['search', 'category']),
        ]);
    }

    public function show(string $slug)
{
    $tutorial = DB::table('tutorial as t')
                  ->leftJoin('tutorial_author as a', 't.author_id', '=', 'a.author_id')
                  ->leftJoin('topik as tp', 't.topik_id', '=', 'tp.topik_id')
                  ->select('t.*', 'a.nama_author as author', 'tp.nama_topik as category')
                  ->where('t.slug', $slug)
                  ->first();

    if (!$tutorial) abort(404);
    $tutorial = (array) $tutorial;

    try {
        $response = \Illuminate\Support\Facades\Http::timeout(10)
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                    ->get($tutorial['url']);

        $html = $response->body();
        preg_match('/<article[^>]*>(.*?)<\/article>/si', $html, $match);
        $content = $match[1] ?? '';
        $content = strip_tags($content, '<h1><h2><h3><p><ul><ol><li><strong><em><code><pre><a><table><tr><td><th>');
        $tutorial['content'] = trim($content);
    } catch (\Exception $e) {
        $tutorial['content'] = '';
    }

    return view('tutorials.show', compact('tutorial'));
}

    public function scrape(Request $request) { return back(); }
    public function status() { return response()->json([]); }
}