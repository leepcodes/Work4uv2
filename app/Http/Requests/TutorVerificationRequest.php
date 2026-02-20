<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TutorVerificationRequest extends FormRequest
{
    public function authorize(): bool{
    
        return true;
    }

    public function rules(): array {
   
        return match($this->route()->getName()) {
            'tutor.verification-1.store' => $this->step1Rules(),
            'tutor.verification-2.store' => $this->step2Rules(),
            'tutor.verification-3.store' => $this->step3Rules(),
            default => [],
        };
    }

    private function step1Rules(): array {
    
        return [
            'firstname'   => 'required|string|max:100',
            'middlename'  => 'nullable|string|max:100',
            'lastname'    => 'required|string|max:100',
            'dob'         => 'required|date',
            'gender'      => 'required|string',
            'citizenship' => 'required|string',
            'country'     => 'required|string',
            'city'        => 'required|string|max:100',
            'contact'     => 'required|string',
            'email'       => 'required|email',
            // 'photo'       => 'nullable|image|max:2048', Wala pa bucket :)
        ];
    }

    private function step2Rules(): array{
    
        return [
            'languages' => 'required|array|min:1',
            'services'  => 'required|array|min:1',
            'subjects'  => 'nullable|string',
        ];
    }

    private function step3Rules(): array{
    
        return [
            'id_front'     => 'required|file|max:2048',
            'id_back'      => 'nullable|file|max:2048',
            'cv_resume'    => 'nullable|file|max:2048',
            'certificates' => 'nullable|array',
            'description'  => 'required|string|max:5000',
        ];
    }
}