<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Classes extends Model
{
    use HasUuids;

    protected $table = 'classes';

    protected $fillable = [
        'uuid',
        'request_id',
        'student_id',
        'tutor_id',
        'schedule_id',
        'class_number',
        'scheduled_at',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
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