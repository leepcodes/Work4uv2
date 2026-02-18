<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/interfaces/navbar.vue';

defineProps<{
    tutors: Array<{
        id: number;
        firstname: string;
        lastname: string;
        avatar: string;
        tag: string;
    }>;
}>();
</script>

<template>
    <Head title="Student Dashboard - Work4U Tutor" />

    <div class="w-full bg-white min-h-screen">
        <Navbar />

        <div class="mt-15 w-full px-10">
            <div class="flex w-[100%] h-10 justify-between">
                 <h2 class="text-lg font-bold text-gray-800 mb-4">Tutors Updates</h2>
                 <div class="flex gap-10">
                     <button class="cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg></button>
                     <button class="cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg></button>
                 </div>
            </div>
           

            <div class="flex gap-4 overflow-x-auto pb-2">
                <div
                    v-for="tutor in tutors"
                    :key="tutor.id"
                    class="flex-shrink-0 flex flex-col items-center gap-2 p-4 bg-white rounded-2xl shadow-sm border border-gray-100 w-36 cursor-pointer hover:shadow-md transition-shadow"
                >
                    <!-- Circular Avatar -->
                    <div class="w-20 h-20 rounded-full overflow-hidden">
                        <img
                            :src="tutor.avatar || '/images/tutor.jpg'"
                            :alt="tutor.firstname"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Name -->
                    <span class="text-sm font-semibold text-gray-800 text-center leading-tight">
                        {{ tutor.firstname }} {{ tutor.lastname }}
                    </span>

                    <!-- Tag pill -->
                    <span class="text-[11px] font-semibold text-cyan-500 border border-cyan-400 px-3 py-1 rounded-full flex items-center gap-1 whitespace-nowrap">
                        <!-- Wala pa Function --> ✦ {{ tutor.tag || 'NEW WEBINAR' }}
                    </span>
                </div>
            </div>
            
        </div>
    </div>
</template>