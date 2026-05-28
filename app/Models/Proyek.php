<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    protected $table      = 'proyek';
    protected $primaryKey = 'proyek_id';
    public    $timestamps = false;

    protected $fillable = [
        'slug', 'judul', 'level_id', 'durasi_menit',
        'tipe_proyek', 'prasyarat', 'topik_id',
        'tanggal_update', 'url', 'in_checkpoint_50', 'in_checkpoint_100',
    ];

    /* ── Relasi ── */
    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id', 'level_id');
    }

    public function topik()
    {
        return $this->belongsTo(Topik::class, 'topik_id', 'topik_id');
    }

    public function instruktur()
    {
        return $this->belongsToMany(
            Instruktur::class,
            'proyek_instruktur',
            'proyek_id',
            'instruktur_id'
        )->withPivot('urutan');
    }

    public function tools()
    {
        return $this->belongsToMany(
            ProyekTool::class,
            'proyek_tool_jct',
            'proyek_id',
            'tool_id'
        );
    }
}