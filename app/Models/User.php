<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function practiceSessions()
    {
    return $this->hasMany(\App\Models\UserSession::class, 'user_id', 'user_id');
    }

    public function savedTools() {
        return $this->belongsToMany(Tool::class, 'saved_tools');
    }

    public function enrollments()
    {
    return $this->hasMany(\App\Models\Enrollment::class, 'user_id', 'user_id');
    }


    public function lessonProgress() {
    return $this->hasMany(LessonProgress::class, 'user_id', 'user_id');
    }

    
    
}