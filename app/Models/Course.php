<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    protected $fillable = [
        'title', 'slug', 'description', 'category', 'difficulty',
        'duration_hours', 'total_lessons', 'rating', 'students_count',
        'instructor', 'thumbnail_color', 'icon_text', 'is_featured',
        'is_free', 'tool_id'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_free' => 'boolean',
    ];

    public function lessons() {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }

    public function tool() {
        return $this->belongsTo(Tool::class);
    }
}