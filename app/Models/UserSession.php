<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    protected $table = 'user_session';
    protected $primaryKey = 'user_session_id';
    public $timestamps = false;

    protected $fillable = [
    'user_id', 
    'session_id', 
    'attempt', 
    'status', 
    'waktu_mulai', 
    'waktu_selesai', 
    'skor'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function practiceSession()
    {
        return $this->belongsTo(PracticeSession::class, 'session_id', 'session_id');
    }

    public function answers()
    {
        return $this->hasMany(UserAnswer::class, 'user_session_id', 'user_session_id');
    }

    /**
     * Hitung skor langsung dari jawaban yang tersimpan.
     * Gunakan ini untuk verifikasi atau update kolom skor saat Finish.
     */
    public function hitungSkor(): int
    {
        return $this->answers()->where('is_correct', 1)->count();
    }

    public function isSelesai(): bool
    {
        return $this->status === 'Finish';
    }
}