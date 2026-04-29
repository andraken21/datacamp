<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller {
    public function index(Request $request) {
        $query = Tool::query();

        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
        }

        if ($request->category && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->language) {
            $query->where('language', $request->language);
        }

        $sort = $request->sort ?? 'rating';
        if ($sort === 'rating') $query->orderByDesc('rating');
        elseif ($sort === 'stars') $query->orderByDesc('stars_github');
        elseif ($sort === 'az') $query->orderBy('name');

        $tools = $query->paginate(12);
        $categories = Tool::distinct()->pluck('category');
        $languages = Tool::distinct()->pluck('language');

        return view('katalog', compact('tools', 'categories', 'languages'));
    }

    public function show($slug) {
        $tool = Tool::where('slug', $slug)->firstOrFail();
        return view('tool-detail', compact('tool'));
    }
}