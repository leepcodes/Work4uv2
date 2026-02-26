<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const role = (page.props.auth as any)?.role;

const showNotifications = ref(false);
const showProfileMenu = ref(false);

const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
    showProfileMenu.value = false;
};

const toggleProfileMenu = () => {
    showProfileMenu.value = !showProfileMenu.value;
    showNotifications.value = false;
};
</script>

<template>
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex  items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <Link href="/" class="flex items-center">
                        <!-- Layered Logo Design -->
                       <div class="flex items-center space-x-0.5">
                            <img src="/images/work4ututor.png" alt="Work4U Logo" class="h-12 w-auto">
                        </div>
                    </Link>
                </div>

                
                <div class="hidden  px-10 gap-10 md:flex items-center space-x-8 justify-start self-center">
                  <Link 
                        :href="role === 'tutor' ? '/tutor/landing' : '/student'"
                        class="font-inter text-[15px] font-normal text-black tracking-wider"
                    >
                        Home
                    </Link>

                     <Link 
                        :href="role === 'tutor' ? '/tutor/request' : '/student/request'"
                        class="font-inter text-[15px] font-normal text-black tracking-wider"
                    >
                        Tutoring
                    </Link>
                     <Link 
                        v-if="role === 'student'"
                        href="/student/questions"
                        class="font-inter text-[15px] font-normal text-black tracking-wider"
                    >
                        Questions
                    </Link>   
                    <Link 
                        v-if="role === 'tutor'"
                        href="/bookings" 
                        class="font-inter text-[15px] font-normal text-black tracking-wider"
                    >
                        Webinairs
                    </Link>

                    <Link 
                        v-if="role === 'tutor'"
                        href="/bookings" 
                        class="font-inter text-[15px] font-normal text-black tracking-wider"
                    >
                        E-learnings
                    </Link>

                    <Link 
                        v-if="role === 'tutor'"
                        href="/bookings" 
                        class="font-inter text-[15px] font-normal text-black tracking-wider"
                    >
                        Educational Videos
                    </Link>
                </div>

                
                <div class="flex ml-auto  space-x-4">
                    
                    <button class="relative p-2 text-gray-500 hover:text-gray-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </button>

                    
                    <button 
                        @click="toggleNotifications" 
                        class="relative p-2 text-gray-500 hover:text-gray-900 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                       
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                   
                   <button 
                        @click="toggleProfileMenu" 
                        class="relative"
                    >
                    
                       <img 
                        :src="(page.props.auth as any)?.photoUrl ?? '/images/tutor.jpg'" 
                        alt="Profile" 
                        class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-200 hover:ring-blue-400 transition-all"
                    />
                    </button>

                    
                    <div 
                        v-show="showProfileMenu" 
                        class="absolute right-4 top-20 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                    >
                        <Link href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            My Profile
                        </Link>
                        <Link href="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Settings
                        </Link>
                        <hr class="my-2">
                        <Link 
                            href="/logout" 
                            method="post" 
                            as="button"
                            class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100"
                        >
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>