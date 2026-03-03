<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

   protected $fillable = [
        'uuid',
        'username',
        'email',
        'password',
        'role',
        'firstname',
        'middlename',
        'lastname',
        'birthday',
        'gender',
        'citizenship',
        'country',
        'city',
        'contact_number',
        'photo',
        'service',
        'languages',
        'subjects',      
        'certificates',
        'documents',
        'free_class',
        'description',   
        'verification_step',
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
}