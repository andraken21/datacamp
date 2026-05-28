<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikasiFaq extends Model
{
    protected $table = 'sertifikasi_faq';
    public $timestamps = false;

    protected $fillable = ['sertifikasi_id', 'pertanyaan', 'jawaban', 'urutan'];
}