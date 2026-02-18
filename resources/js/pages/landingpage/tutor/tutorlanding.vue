<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
const currentStep = ref(1);
const photoPreview = ref<string | null>(null);

const form = ref({
    firstname: '',
    middlename: '',
    lastname: '',
    dob: '',
    gender: '',
    citizenship: '',
    country: '',
    city: '',
    contact: '',
    email: '',
});

const handlePhoto = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => { photoPreview.value = ev.target?.result as string; };
        reader.readAsDataURL(file);
    }
};
</script>

<template>
    <div class="min-h-screen bg-white font-inter">

 <nav class="w-full bg-white  px-10 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <!-- Dots -->
                <div class="flex gap-1 items-center">
                    <div class="w-2 h-2 rounded-full bg-gray-800"></div>
                    <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                </div>
                <!-- Number -->
                <span class="text-2xl font-bold text-gray-800">4</span>
                <!-- Logo -->
                <img src="/images/work4ututor.png" alt="Work4U Tutor" class="h-8 object-contain">
            </div>

            <!-- Logout -->
            <Link 
                href="/logout" 
                method="post" 
                as="button"
                class="text-black text-sm font-medium hover:text-gray-800 transition-colors"
            >
                Logout
            </Link>
        </nav>
```

            
    
        <div class="flex gap-12 px-16 py-10">

            <!-- Step Indicator -->
            <div class="flex flex-col items-center gap-0 pt-2">
                <!-- Step 1 -->
                <div class="w-9 h-9 rounded-full bg-[#007582] flex items-center justify-center shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
                <div class="w-[2px] h-16 bg-gray-200"></div>

                <!-- Step 2 -->
                <div class="w-9 h-9 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center text-gray-400 text-sm font-semibold">
                    2
                </div>
                <div class="w-[2px] h-16 bg-gray-200"></div>

                <!-- Step 3 -->
                <div class="w-9 h-9 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center text-gray-400 text-sm font-semibold">
                    3
                </div>
            </div>

            <!-- Form Content -->
            <div class="flex-1 max-w-2xl">

                <!-- Photo Upload -->
                <label class="block w-36 h-36 mb-6 cursor-pointer">
                    <input type="file" accept="image/*" class="hidden" @change="handlePhoto">
                    <div class="w-full h-full rounded-2xl border-2 border-dashed border-[#007582] flex flex-col items-center justify-center gap-2 hover:bg-teal-50 transition-colors">
                        <div v-if="photoPreview" class="w-full h-full rounded-2xl overflow-hidden">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </div>
                        <template v-else>
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#007582" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                            <span class="text-xs text-[#007582] font-medium">Add Photo</span>
                        </template>
                    </div>
                </label>

                <!-- Tutor ID -->
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        Tutor Identification Number
                    </label>
                    <input
                        type="text"
                        value="TTR***********"
                        disabled
                        class="w-72 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-400 text-sm tracking-widest"
                    >
                    <p class="text-xs text-red-400 mt-1">• Auto generated once Country of Residence is selected</p>
                </div>

                <!-- Personal Information -->
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Personal Information</span>
                        <span class="text-xs text-red-400 font-medium">• Required</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <!-- Row 1 -->
                        <div class="flex gap-3">
                            <input v-model="form.firstname" type="text" placeholder="First Name"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors">
                            <input v-model="form.middlename" type="text" placeholder="Middle Name"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors">
                            <input v-model="form.lastname" type="text" placeholder="Last Name"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors">
                        </div>

                        <!-- Row 2 -->
                        <div class="flex gap-3">
                            <input v-model="form.dob" type="text" placeholder="Date of Birth"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors">
                            <select v-model="form.gender"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-400 focus:outline-none focus:border-[#007582] transition-colors appearance-none">
                                <option value="" disabled selected>Gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                            <select v-model="form.citizenship"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-400 focus:outline-none focus:border-[#007582] transition-colors appearance-none">
                                <option value="" disabled selected>Citizenship</option>
                                <option>Filipino</option>
                                <option>American</option>
                                <option>Other</option>
                            </select>
                        </div>

                        <!-- Row 3 -->
                        <div class="flex gap-3">
                            <select v-model="form.country"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-400 focus:outline-none focus:border-[#007582] transition-colors appearance-none">
                                <option value="" disabled selected>Country of Residence</option>
                                <option>Philippines</option>
                                <option>United States</option>
                                <option>Other</option>
                            </select>
                            <input v-model="form.city" type="text" placeholder="City of Residence"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors">
                        </div>

                        <!-- Row 4 -->
                        <div class="flex gap-3">
                            <select v-model="form.contact"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-400 focus:outline-none focus:border-[#007582] transition-colors appearance-none">
                                <option value="" disabled selected>Contact Number</option>
                                <option>+63</option>
                                <option>+1</option>
                            </select>
                            <input v-model="form.email" type="email" placeholder="Email"
                                class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors">
                        </div>
                    </div>
                </div>

                <!-- Next Button -->
                <div class="flex justify-end">
                    <button class="bg-[#007582] hover:bg-[#005f6b] text-white font-semibold text-sm px-10 py-3 rounded-xl transition-colors shadow-md">
                        NEXT
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>