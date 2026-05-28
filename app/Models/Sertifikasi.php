<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    protected $table = 'sertifikasi';

    protected $fillable = [
        'sertifikasi_id',
        'jenis',
        'nama',
        'tipe',
        'promo',
        'deskripsi',
        'panduan',
        'url',
        'slug',
        'nama_peran',
        'dibuat_oleh',
        'topik_tercakup',
        'konten_faq',
        'konten_detail',
    ];

    protected $casts = [
        'topik_tercakup' => 'array',
        'konten_faq'     => 'array',
        'konten_detail'  => 'array',
    ];

    // ─── Relasi ─────────────────────────────────────

    public function topik()
    {
        return $this->hasMany(SertifikasiTopik::class, 'sertifikasi_id', 'id');
    }

    public function sections()
    {
        return $this->hasMany(SertifikasiSection::class, 'sertifikasi_id', 'id')
                    ->orderBy('urutan');
    }

    public function faqs()
    {
        return $this->hasMany(SertifikasiFaq::class, 'sertifikasi_id', 'id')
                    ->orderBy('urutan');
    }
}