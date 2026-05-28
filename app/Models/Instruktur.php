<?php
<<<<<<< HEAD
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model {
    protected $table = 'instruktur';
    protected $primaryKey = 'instruktur_id';
    public $timestamps = false;

    protected $fillable = ['nama_instruktur', 'jabatan', 'url_foto'];
=======

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    protected $table      = 'instruktur';
    protected $primaryKey = 'instruktur_id';
    public    $timestamps = false;
>>>>>>> 2c5e302968a92d75e9cf8376b18037551c61b9b4
}