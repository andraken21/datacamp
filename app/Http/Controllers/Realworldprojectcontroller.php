<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\ProyekTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RealWorldProjectController extends Controller
{
    /**
     * Halaman daftar Real World Projects
     * GET /real-world-projects
     */
    public function index(Request $request)
    {
        // Ambil semua tool untuk filter bar
        $tools = ProyekTool::orderBy('nama_tool')->get();

        // Query dasar dengan relasi
        $query = Proyek::with(['level', 'tools', 'instruktur'])
            ->orderBy('tanggal_update', 'desc');

        // Filter by tool (nama_tool)
        if ($request->filled('tool')) {
            $query->whereHas('tools', function ($q) use ($request) {
                $q->where('nama_tool', $request->tool);
            });
        }

        // Filter by tipe proyek
        if ($request->filled('tipe')) {
            $query->where('tipe_proyek', $request->tipe);
        }

        // Search
        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $proyeks = $query->paginate(12)->withQueryString();
        $total   = $query->toBase()->getCountForPagination();

        return view('realworld.index', compact('proyeks', 'tools', 'total'));
    }

    /**
     * Halaman detail proyek
     * GET /real-world-projects/{slug}
     */
    public function show(string $slug)
    {
        $proyek = Proyek::with(['level', 'tools', 'instruktur' => function ($q) {
            $q->orderBy('proyek_instruktur.urutan');
        }])->where('slug', $slug)->firstOrFail();

        // Proyek terkait (sama topik, exclude diri sendiri)
        $related = Proyek::with(['level', 'tools'])
            ->where('topik_id', $proyek->topik_id)
            ->where('proyek_id', '!=', $proyek->proyek_id)
            ->limit(3)
            ->get();

        return view('realworld.show', compact('proyek', 'related'));
    }

    /**
     * Mulai / enroll proyek (tombol Start)
     * POST /real-world-projects/{slug}/start
     */
    public function start(string $slug)
    {
        $proyek = Proyek::where('slug', $slug)->firstOrFail();

        // Jika proyek punya URL eksternal langsung redirect
        if ($proyek->url) {
            return redirect()->away($proyek->url);
        }

        // Jika tidak, redirect ke halaman detail
        return redirect()->route('realworld.show', $slug)
            ->with('info', 'Proyek dimulai!');
    }
}