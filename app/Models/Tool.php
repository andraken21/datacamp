<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tool extends Model {
    protected $table = 'sandbox';
    protected $primaryKey = 'sandbox_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_sandbox',
        'deskripsi_singkat',
        'deskripsi_lengkap',
        'token_per_menit',
        'akses_perangkat',
        'url',
    ];
}