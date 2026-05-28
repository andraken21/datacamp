<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikasiTopik extends Model
{
    protected $table = 'sertifikasi_topik';
    public $timestamps = false;

    protected $fillable = ['sertifikasi_id', 'topik'];
}