<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const userType = ref('student');
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    user_type: 'student',
});

const selectUserType = (type: string) => {
    userType.value = type;
    form.user_type = type;
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const toggleConfirmPasswordVisibility = () => {
    showConfirmPassword.value = !showConfirmPassword.value;
};

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register - Work4U Tutor" />

    <!-- Add this wrapper for centering with white space -->
    <div class="min-h-screen bg-gray-100 flex items-center justify-center p-8">
        
        <!-- Main Card Container -->
        <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex">
            
           
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
                <!-- Background Image - No Overlay -->
                <div 
                    class="absolute inset-0 bg-cover bg-center"
                    style="background-image: url('/images/register.jpg')"
                ></div>
                
                <!-- Content -->
                <div class="relative z-10 flex items-center justify-center w-full px-12 py-16">
                    <div class="max-w-sm text-center text-white">
                        <h1 class="text-4xl font-bold mb-4 leading-tight">
                            Get 30% OFF on<br>
                            Your First<br>
                            Learning
                        </h1>
                        <p class="text-base leading-relaxed opacity-90">
                            Enjoy a flat 30% discount on courses, live webinars, and premium e-learning content. 
                            Learn from top tutors, access high-quality educational videos, and unlock exclusive 
                            learning resources designed to help you grow faster and smarter.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center px-8 py-12">
                <div class="w-full max-w-md">
                    <!-- Logo -->
                    <div class="flex justify-center mb-6">
                        <Link href="/">
                            <img 
                                src="/images/work4ututor-logo1.png" 
                                alt="Work4U Tutor Logo" 
                                class="h-10 w-auto"
                            >
                        </Link>
                    </div>

                    <!-- User Type Toggle -->
                    <div class="flex gap-3 mb-6">
                        <button
                            @click="selectUserType('student')"
                            :class="[
                                'flex-1 py-2.5 px-4 rounded-lg font-medium transition-all text-sm',
                                userType === 'student' 
                                    ? 'bg-white text-gray-900 shadow-md border-2 border-gray-300' 
                                    : 'bg-transparent text-gray-600 border-2 border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            I'm a Student
                        </button>
                        <button
                            @click="selectUserType('tutor')"
                            :class="[
                                'flex-1 py-2.5 px-4 rounded-lg font-medium transition-all text-sm',
                                userType === 'tutor' 
                                    ? 'bg-white text-gray-900 shadow-md border-2 border-gray-300' 
                                    : 'bg-transparent text-gray-600 border-2 border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            I'm a Tutor
                        </button>
                    </div>

                    <!-- Form Title -->
                    <h2 class="text-xl font-semibold text-gray-900 mb-5">
                        Start Your Learning Journey
                    </h2>

                   
                    <form @submit.prevent="submit" class="space-y-4">
                        
                        <div>
                            <label for="username" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Username
                            </label>
                            <input
                                id="username"
                                v-model="form.username"
                                type="text"
                                placeholder="Choose a username"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:border-transparent outline-none transition-all text-sm text-gray-900"
                                required
                            />
                            <div v-if="form.errors.username" class="text-red-600 text-xs mt-1">
                                {{ form.errors.username }}
                            </div>
                        </div>

                        
                        <div>
                            <label for="email" class="block text-xs font-medium text-gray-700 mb-1.5">
                                Email
                            </label>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="youremail@example.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:border-transparent outline-none transition-all text-sm text-gray-900"
                                required
                            />
                            <div v-if="form.errors.email" class="text-red-600 text-xs mt-1">
                                {{ form.errors.email }}
                            </div>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-3">
                           
                            <div>
                                <label for="password" class="block text-xs font-medium text-gray-700 mb-1.5">
                                    Create Password
                                </label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        placeholder="Password"
                                        class="w-full px-3 py-2.5 pr-9 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition-all"                                        required
                                    />
                                    <button
                                        type="button"
                                        @click="togglePasswordVisibility"
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    >
                                        <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                                <div v-if="form.errors.password" class="text-red-600 text-xs mt-1">
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1.5">
                                    Confirm Password
                                </label>
                                <div class="relative">
                                    <input
                                        id="password_confirmation"
                                        v-model="form.password_confirmation"
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        placeholder="Confirm Password"
                                        class="w-full px-3 py-2.5 pr-9 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent outline-none transition-all"
                                        required
                                    />
                                    <button
                                        type="button"
                                        @click="toggleConfirmPasswordVisibility"
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                                    >
                                        <svg v-if="!showConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-cyan-400 hover:bg-cyan-500 text-white font-semibold py-2.5 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed text-sm"
                        >
                            Join as {{ userType === 'student' ? 'Student' : 'Tutor' }}
                        </button>

                        
                        <p class="text-center text-xs text-gray-600">
                            By signing up, you agree to our 
                            <Link href="/terms" class="text-cyan-500 hover:underline">Terms of Service</Link>
                            and 
                            <Link href="/privacy" class="text-cyan-500 hover:underline">Privacy Policy</Link>
                        </p>

                        
                        <p class="text-center text-xs text-gray-600 mt-3">
                            Already have an account? 
                            <Link href="/login" class="text-cyan-500 hover:underline font-bold">Log in</Link>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>