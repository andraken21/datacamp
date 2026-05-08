<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tool;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller {

    public function storeTool(Request $request, $slug) {
        $request->validate([
            'body' => 'required|min:3|max:500',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $tool = Tool::where('slug', $slug)->firstOrFail();

        Comment::create([
            'user_id' => Auth::id(),
            'body' => $request->body,
            'rating' => $request->rating,
            'commentable_id' => $tool->id,
            'commentable_type' => Tool::class,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function storeCourse(Request $request, $slug) {
        $request->validate([
            'body' => 'required|min:3|max:500',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $course = Course::where('slug', $slug)->firstOrFail();

        Comment::create([
            'user_id' => Auth::id(),
            'body' => $request->body,
            'rating' => $request->rating,
            'commentable_id' => $course->id,
            'commentable_type' => Course::class,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy($id) {
        $comment = Comment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'Tidak bisa hapus komentar orang lain!');
        }

        $comment->delete();
        return back()->with('success', 'Komentar dihapus.');
    }
}