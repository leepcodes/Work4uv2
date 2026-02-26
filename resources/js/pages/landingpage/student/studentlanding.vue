<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/components/interfaces/navbar.vue';

defineProps<{
    tutors: Array<{
        uuid : string;
        id: number;
        firstname: string;
        lastname: string;
        avatar: string;
      
    }>;
}>();
</script>

<template>
    <Head title="Student Dashboard - Work4U Tutor" />
    <link rel="stylesheet" href="resources\css\global.css">
    <div class="w-full bg-white min-h-screen">
        <Navbar />

        <div class="mt-15 w-full px-10">
            <div class="flex w-[100%] h-10 justify-between">
                 <h2 class="text-lg font-bold text-gray-800 mb-4">Tutors Updates</h2>
                 <div class="flex gap-10">
                    <button class="cursor-pointer rounded-[8px] shadow-md p-2 bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
                    </button> 
                    <button class="cursor-pointer rounded-[8px] shadow-md p-2 bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
                    </button>                   
                 </div>
            </div>
           

            <div class="flex  gap-10 overflow-x-auto pb-2 px-10 pt-10 pb-10 custom-scroll  ">
                <Link
                    v-for="tutor in tutors"
                    :key="tutor.id"
                    :href="`/tutor/profile/${tutor.uuid}`"
                    class="tutor-card flex-shrink-0 flex flex-col items-center gap-2 p-4 rounded-2xl w-45 cursor-pointer"
                    >
                    <!-- Circular Avatar -->
                    <div class="w-20 h-20 rounded-full overflow-hidden">
                        <img
                        :src="tutor.avatar || '/images/tutor.jpg'"
                        :alt="tutor.firstname"
                        class="w-full h-full object-cover"
                        />
                    </div>

                    <span class="text-sm font-semibold text-gray-800 text-center leading-tight">
                        {{ tutor.firstname }} {{ tutor.lastname }}
                    </span>

                    <span class="text-[11px] font-semibold text-cyan-500 border border-cyan-400 px-3 py-1 rounded-full flex items-center gap-1 whitespace-nowrap">
                        <!-- ✦ {{ tutor.tag || 'NEW WEBINAR' }} --> ✦ 'NEW WEBINAR'
                    </span>
                </Link>
            </div>

            <!-- ads section -->
            <div class="flex justify-between w-[100%] h-70 mt-10 bg-none">

                <div class="w-[30%] h-55 rounded-3xl flex-shrink-0" 
                    style="background-image: url('/images/ads 3.png'); background-size: cover; background-position: center;">
                </div>

                <div class="w-[30%] h-55 rounded-3xl flex-shrink-0" 
                    style="background-image: url('/images/ads 2.jpg'); background-size: cover; background-position: center;">
                </div>
                
                <div class="w-[30%] h-55 rounded-3xl flex-shrink-0" 
                    style="background-image: url('/images/ads 1.jpg'); background-size: cover; background-position: center;">
                </div>  

            </div>

           <div class="w-[100%] h-90">
    <h2 class="font-nunito text-lg font-bold text-gray-800 mb-1">Your Next Classes</h2>
    <h2 class="font-inter text-sm font-bold text-[#007582] mb-4">Upcoming classes you've enrolled in</h2>

    <div class="flex gap-6 overflow-x-auto scrollbar-none">

        <div class="flex-shrink-0 w-72 rounded-2xl overflow-hidden shadow-md bg-white cursor-pointer hover:shadow-lg transition-shadow">
            <!-- Thumbnail -->
            <div class="relative w-full h-40">
                <img src="/images/banner.png" alt="Class" class="w-full h-full object-cover">
                <!-- Icon overlay -->
                <div class="absolute bottom-2 right-2 bg-white rounded-lg p-1 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#007582" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m10 9 5 3-5 3Z"/></svg>
                </div>
            </div>

            <!-- Info -->
            <div class="p-4 flex flex-col gap-2">
                <h3 class="font-nunito font-bold text-gray-800 text-base">Mathematic</h3>

                <div class="flex items-center gap-2 text-gray-500 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>11:30 PM</span>
                    <span>•</span>
                    <span>22/03/2025</span>
                </div>

                <!-- Tutor -->
                <div class="flex items-center gap-2 mt-1">
                    <img src="/images/banner.png" alt="Tutor" class="w-7 h-7 rounded-full object-cover">
                    <span class="text-sm font-inter text-gray-700">Sarah Johnson</span>
                </div>
            </div>
        </div>

    </div>
</div>

        </div>
    </div>
</template>

