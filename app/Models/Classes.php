<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Classes extends Model
{
    use HasUuids;

    protected $table = 'classes';


    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'user_id',
        'subject_id',
        'student_id',
        'tutor_id',
        'schedule_id',
        'class_number',
        'schedule',
        'video_link',
        'notes',
        'documents',
        'scheduled_at',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'schedule'     => 'date',
        'scheduled_at' => 'datetime',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function packageSchedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}