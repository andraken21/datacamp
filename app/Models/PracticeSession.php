<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeSession extends Model
{
    protected $table = 'practice_session';
    protected $primaryKey = 'session_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_session',
        'topik_id',
    ];

    public function topik()
    {
        return $this->belongsTo(Topik::class, 'topik_id', 'topik_id');
    }

    public function questions()
    {
        return $this->hasMany(PracticeQuestion::class, 'session_id', 'session_id');
    }

    public function userSessions()
    {
        return $this->hasMany(UserSession::class, 'session_id', 'session_id');
    }
}