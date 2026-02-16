<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'student_count',
        'current_student',
        'publish_video',
        'subj_created',
        'skills',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
