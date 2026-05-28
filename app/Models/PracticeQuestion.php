<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PracticeQuestion extends Model
{
    protected $table = 'practice_question';
    protected $primaryKey = 'question_id';
    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'nomor_pertanyaan',
        'pertanyaan',
        'opsi_1',
        'opsi_2',
        'opsi_3',
        'jawaban_benar',
    ];

    public function session()
    {
        return $this->belongsTo(PracticeSession::class, 'session_id', 'session_id');
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class, 'question_id', 'question_id');
    }

    /**
     * Kembalikan semua opsi dalam bentuk array yang sudah diacak.
     * Gunakan ini di View supaya urutan opsi tidak selalu sama.
     */
    public function getOpsiAcakAttribute(): array
    {
        $opsi = [
            $this->opsi_1,
            $this->opsi_2,
            $this->opsi_3,
        ];
        shuffle($opsi);
        return $opsi;
    }
}