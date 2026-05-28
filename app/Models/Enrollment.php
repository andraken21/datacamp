<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model {
    protected $fillable = ['user_id', 'course_id', 'progress', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];


    public function user() {
<<<<<<< HEAD
        return $this->belongsTo(User::class, 'user_id');
=======
        return $this->belongsTo(User::class, 'user_id', 'user_id');
>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}