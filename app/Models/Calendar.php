<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $table = 'calendar';

    protected $fillable = [
        'tutor_id',
        'type',
        'start_time',
        'end_time',
        'day_off_date',
        'reason',
    ];
}