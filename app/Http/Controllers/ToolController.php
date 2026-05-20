<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller {

    public function index(Request $request) {
    $query = Tool::query();

    if ($request->search) {
        $query->where('nama_sandbox', 'like', '%'.$request->search.'%')
              ->orWhere('deskripsi_singkat', 'like', '%'.$request->search.'%');
    }

    $sort = $request->sort ?? 'az';
    if ($sort === 'token') $query->orderByDesc('token_per_menit');
    else $query->orderBy('nama_sandbox');

    $tools = $query->paginate(12);

    return view('katalog', compact('tools'));
}

public function show($id) {
    $tool = Tool::findOrFail($id);
    return view('tool-detail', compact('tool'));
}

    public function save(Request $request, $id) {
        $user = Auth::user();
        $existing = \App\Models\SavedTool::where('user_id', $user->id)
                    ->where('tool_id', $id)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('message', 'Tool dihapus dari simpanan.');
        } else {
            \App\Models\SavedTool::create([
                'user_id' => $user->id,
                'tool_id' => $id,
            ]);
            return back()->with('message', 'Tool berhasil disimpan!');
        }
    }
}