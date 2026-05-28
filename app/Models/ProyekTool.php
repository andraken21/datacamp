<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyekTool extends Model
{
    protected $table      = 'proyek_tool';
    protected $primaryKey = 'tool_id';
    public    $timestamps = false;

    protected $fillable = ['nama_tool'];

    public function proyeks()
    {
        return $this->belongsToMany(
            Proyek::class,
            'proyek_tool_jct',
            'tool_id',
            'proyek_id'
        );
    }
}