<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    protected $table = 'user_reviews';

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}