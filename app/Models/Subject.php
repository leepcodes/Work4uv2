<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 
class Subject extends Model
{
    use HasUuids;

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
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