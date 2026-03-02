<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
        protected $fillable = ['user_id', 'tutor_id', 'subject_id', 'class_count', 'tutor_custom_price', 'available_date'];

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
}