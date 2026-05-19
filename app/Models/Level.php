<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Level extends Model {
    protected $table = 'level';
    protected $primaryKey = 'level_id';
    public $timestamps = false;

    protected $fillable = ['nama_level'];
}