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

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function progress() {
        return $this->hasMany(LessonProgress::class);
    }
}