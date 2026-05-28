<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    protected $table = 'courses';
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'nama_course', 'slug', 'deskripsi', 'topik_id', 'level_id',
        'durasi', 'ai_credits', 'ai_native', 'jumlah_video',
        'jumlah_latihan', 'xp', 'cpe_credits', 'total_learners',
        'instruktur_id', 'prasyarat', 'is_featured', 'is_free',
        'difficulty', 'duration_hours', 'total_lessons', 'rating',
        'students_count', 'instructor', 'thumbnail_color', 'icon_text',
        'title', 'description', 'category',
    ];

    public function getTitleAttribute() {
        return $this->attributes['title'] ?? $this->attributes['nama_course'] ?? '';
    }

    public function track() {
    return $this->belongsTo(Track::class, 'track_id', 'track_id');
    }

    public function level() {
    return $this->belongsTo(\App\Models\Level::class, 'level_id', 'level_id');
    }

    public function instruktur() {
    return $this->belongsTo(\App\Models\Instruktur::class, 'instruktur_id', 'instruktur_id');
    }

    public function getDescriptionAttribute() {
        return $this->attributes['description'] ?? $this->attributes['deskripsi'] ?? '';
    }

    public function lessons() {
        return $this->hasMany(Lesson::class, 'course_id', 'course_id')->orderBy('order');
    }
    public function enrollments() {
        return $this->hasMany(Enrollment::class, 'course_id', 'course_id');
    }
    public function tool() {
        return $this->belongsTo(Tool::class);
    }
}