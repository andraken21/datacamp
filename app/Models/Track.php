<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model {
    protected $table = 'track';
    protected $primaryKey = 'track_id';

    protected $fillable = [
        'jenis_track', 'nama_track', 'slug', 'teknologi',
        'durasi_jam', 'total_kursus', 'total_proyek',
        'total_asesmen', 'total_peserta', 'url', 'deskripsi'
    ];

    public function courses() {
    return $this->belongsToMany(Course::class, 'track_course', 'track_id', 'course_id')
                ->orderBy('track_course.urutan_kursus');
}
}