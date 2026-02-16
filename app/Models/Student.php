<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'class_request_count',
        'subjects',
        'class_schedule',
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}