<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model {
    protected $table = 'instruktur';
    protected $primaryKey = 'instruktur_id';
    public $timestamps = false;

    protected $fillable = ['nama_instruktur', 'jabatan', 'url_foto'];
}