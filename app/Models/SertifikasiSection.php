<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SertifikasiSection extends Model
{
    protected $table = 'sertifikasi_section';
    public $timestamps = false;

    protected $fillable = ['sertifikasi_id', 'judul_section', 'konten', 'urutan'];
}