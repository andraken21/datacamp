<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $table = 'user_answer';
    protected $primaryKey = 'user_answer_id';
    public $timestamps = false;

    protected $fillable = [
        'user_session_id',
        'question_id',
        'jawaban_dipilih',
        'is_correct',
        'answered_at',
    ];

    protected $casts = [
        'is_correct'   => 'boolean',
        'answered_at'  => 'datetime',
    ];

    public function userSession()
    {
        return $this->belongsTo(UserSession::class, 'user_session_id', 'user_session_id');
    }

    public function question()
    {
        return $this->belongsTo(PracticeQuestion::class, 'question_id', 'question_id');
    }
}