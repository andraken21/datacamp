<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model {
    protected $fillable = [
        'course_id', 'title', 'content', 'video_url',
        'duration_minutes', 'order', 'type', 'is_free_preview'
    ];

    protected $casts = [
        'is_free_preview' => 'boolean',
    ];

    // ✅ FIX: explicit foreign key & owner key karena Course pakai course_id bukan id
    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function progress() {
        return $this->hasMany(LessonProgress::class, 'lesson_id', 'id');
    }
}