<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;

class TrackController extends Controller {

    public function career(Request $request) {
    $filter = $request->get('filter', 'all');

    $query = Track::where('jenis_track', 'Career Track');

    if ($filter !== 'all') {
        $query->where('slug', 'like', '%' . $filter . '%');
    }

    $tracks = $query->get();

    return view('tracks-career', compact('tracks', 'filter'));
}

public function skill(Request $request) {
    $filter = $request->get('filter', 'all');

    $query = Track::where('jenis_track', 'Skill Track');

    if ($filter !== 'all') {
        $query->where('slug', 'like', '%' . $filter . '%');
    }

    $tracks = $query->get();

    return view('tracks-skill', compact('tracks', 'filter'));
}

    public function show($slug) {
        $track = Track::with('courses')->where('slug', $slug)->firstOrFail();
        return view('track-detail', compact('track'));
    }
}
