<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
});

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

// 👇 Add this
const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login - Work4U Tutor" />

    <div class="min-h-screen relative flex items-center justify-center p-4">
        <div 
            class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('/images/loginbg1.jpeg'); filter: blur(8px);"
        ></div>
        
       
        <div class="absolute inset-0 bg-black/20"></div>

        
        <div class="absolute top-6 left-6 z-10">
            <Link href="/">
                <img 
                    src="/images/work4ututor.png" 
                    alt="Work4U Tutor Logo" 
                    class="h-10 w-auto"
                >
            </Link>
        </div>

      
        <div class="relative z-10 bg-white rounded-3xl shadow-2xl p-10 w-full max-w-md">
            
           <div class="flex justify-center mb-8">
                <img 
                    src="/images/work4ututor-logo1.png" 
                    alt="Work4U Tutor Logo" 
                    class="h-16 w-auto"
                >
            </div>


            
            
                <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="Email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:border-transparent outline-none transition-all text-sm text-gray-900"
                        required
                        autofocus
                    />
                    <div v-if="form.errors.email" class="text-red-600 text-sm mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>

               
                <div>
                    <div class="relative">
                        <input
                            id="password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'"
                            placeholder="Password"
                            class="w-full px-4 py-3 pr-10 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-400 focus:border-transparent outline-none transition-all text-sm"
                            required
                        />
                        <button
                            type="button"
                            @click="togglePasswordVisibility"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                        >
                            <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <div v-if="form.errors.password" class="text-red-600 text-sm mt-1">
                        {{ form.errors.password }}
                    </div>
                </div>

              
                <div class="text-left">
                    <Link 
                        href="/forgot-password" 
                        class="text-sm text-cyan-500 hover:text-cyan-600 hover:underline font-medium"
                    >
                        Forgot your password?
                    </Link>
                </div>

                
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-cyan-400 hover:bg-cyan-500 text-white font-semibold py-3.5 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Sign In
                </button>
                 <p class="text-center text-xs text-gray-600 pt-2">
                    No Accounts yet?
                    <Link href="/register" class="text-cyan-500 text-bold hover:underline">Register Now</Link>
                   
                </p>

              
                <p class="text-center text-xs text-gray-600 pt-2">
                    By signing up, you agree to Work4uTutor 
                    <Link href="/terms" class="text-cyan-500 hover:underline">Terms of Service</Link>
                    and that you have read our 
                    <Link href="/privacy" class="text-cyan-500 hover:underline">Privacy Policy</Link>
                </p>
            </form>
        </div>
    </div>
</template>