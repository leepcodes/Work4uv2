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
        'message',
        'custom_class_count',
        'tutor_custom_price',
        'request_sched',
        'class_avail',
        'custom_class_price',
        'status',
    ];
}