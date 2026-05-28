<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model {
    protected $fillable = ['user_id', 'lesson_id', 'is_completed', 'completed_at'];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // ✅ FIX: explicit foreign key & local key karena User pakai user_id bukan id
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }
}