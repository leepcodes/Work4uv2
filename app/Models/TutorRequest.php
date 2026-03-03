<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorRequest extends Model
{
    protected $table = 'requests';

    protected $fillable = [
        'student_id',
        'subject_id',
        'tutor_id',
        'requestid',          
        'message',
        'custom_class_count',
        'tutor_custom_price',
        'request_sched',
        'class_avail',
        'custom_class_price',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }
    public function tutor()
    {
        return $this->belongsTo(\App\Models\User::class, 'tutor_id');
    }
}