<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'firstname',
        'lastname',
        'free_class',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'free_class' => 'boolean',
        ];
    }

    // Relationship to Student
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // Relationship to Tutor
    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }
}