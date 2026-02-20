<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
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
        'service',
        'languages',
        'certificates',
        'documents',
        'free_class',
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