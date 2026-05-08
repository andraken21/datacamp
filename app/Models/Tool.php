<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model {
   public function comments()
    {
    return $this->morphMany(Comment::class, 'commentable')->latest();
    }
    protected $fillable = [
        'name', 'slug', 'description', 'category',
        'language', 'difficulty', 'rating', 'stars_github',
        'source_url', 'icon_text', 'icon_color', 'tags', 'is_featured'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
    ];
}