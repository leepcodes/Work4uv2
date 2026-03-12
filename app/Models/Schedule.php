<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Schedule extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'tutor_id',
        'subject_id',
        'tutor_custom_price',
        'total_class_count',
        'complete_class_count',
        'remaining_class_count',
        'available_date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function classes()
    {
        return $this->hasMany(Classes::class, 'schedule_id');
    }
    
}