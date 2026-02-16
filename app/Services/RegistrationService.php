<?php
    

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationService
{
    
    public function register(array $data): User
    {
        DB::beginTransaction();
        
        try {
            
            $user = $this->createUser($data);
            
           
            if ($data['user_type'] === 'student') {
                $this->createStudentProfile($user);
            } else {
                $this->createTutorProfile($user, $data);
            }
            
           
            $this->sendWelcomeEmail($user, $data['user_type']);
            $this->logRegistration($user, $data['user_type']);
            DB::commit();
            
            return $user->fresh();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
   
    protected function createUser(array $data): User
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['user_type'], // 'student' or 'tutor'
            'firstname' => $data['firstname'] ?? '',
            'lastname' => $data['lastname'] ?? '',
            'free_class' => $data['user_type'] === 'student' ? true : false,
        ]);
    }
    
    
    protected function createStudentProfile(User $user): Student
    {
        return Student::create([
            'user_id' => $user->id,
            'class_request_count' => 0,
            'subjects' => null,
            'class_schedule' => null,
        ]);
    }
    
    
    protected function createTutorProfile(User $user, array $data): Tutor
    {
        return Tutor::create([
            'user_id' => $user->id,
            'username' => $user->username,
            'student_count' => 0,
            'current_student' => 0,
            'publish_video' => 0,
            'subj_created' => 0,
            'skills' => $data['skills'] ?? null,
        ]);
    }
    
   
    protected function sendWelcomeEmail(User $user, string $userType): void
    {
        // TODO: Implement email sending
        // if ($userType === 'student') {
        //     Mail::to($user)->send(new StudentWelcomeEmail($user));
        // } else {
        //     Mail::to($user)->send(new TutorWelcomeEmail($user));
        // }
    }
    
   
    protected function logRegistration(User $user, string $userType): void
    {
        Log::info("New {$userType} registered", [
            'user_id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
        ]);
    }
    
   
    public function isUsernameAvailable(string $username): bool
    {
        return !User::where('username', $username)->exists();
    }
    
  
    public function isEmailAvailable(string $email): bool
    {
        return !User::where('email', $email)->exists();
    }
}