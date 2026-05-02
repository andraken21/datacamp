<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedTool extends Model {
    protected $fillable = ['user_id', 'tool_id'];
}