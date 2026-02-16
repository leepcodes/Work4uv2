<?php
// app/Actions/Fortify/CreateNewUser.php

namespace App\Actions\Fortify;

use App\Services\RegistrationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    protected $registrationService;
    
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }
    
    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input)
    {
        // Validate the input
        Validator::make($input, [
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users',
                'alpha_dash', // Only letters, numbers, dashes, underscores
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'user_type' => [
                'required',
                'in:student,tutor',
            ],
        ])->validate();
        
        // Use service to handle registration logic
        return $this->registrationService->register($input);
    }
}