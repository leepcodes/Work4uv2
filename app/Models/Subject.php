<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
        
    protected $fillable = [
         'uuid',
        'tutor_id',
        'subject_title',
        'class_start',
        'suitable_age',
        'currently_enrolled',
        'description',
        'image_file',
        'two_class',
        'three_class',
        'five_class',
    ];
    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }
}