<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Topik extends Model
{
    protected $table = 'topik';
    protected $primaryKey = 'topik_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_topik',
    ];

    public function practiceSessions()
    {
        return $this->hasMany(PracticeSession::class, 'topik_id', 'topik_id');
    }
}