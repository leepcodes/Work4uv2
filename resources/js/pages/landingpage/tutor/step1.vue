<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import NoNav from '@/components/interfaces/nonav.vue';

const photoPreview = ref<string | null>(null);

const props = defineProps<{ user: any }>();

const form = useForm({
    firstname:   props.user.firstname   ?? '',
    middlename:  props.user.middlename  ?? '',
    lastname:    props.user.lastname    ?? '',
    dob:         props.user.birthday    ?? '',
    gender:      props.user.gender      ?? '',
    citizenship: props.user.citizenship ?? '',
    country:     props.user.country     ?? '',
    city:        props.user.city        ?? '',
    contact:     props.user.contact_number ?? '',
    email:       props.user.email       ?? '',
    photo:       null as File | null,
});

const handlePhoto = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) {
        form.photo = file;
        const reader = new FileReader();
        reader.onload = (ev) => { photoPreview.value = ev.target?.result as string; };
        reader.readAsDataURL(file);
    }
};

const isFormValid = computed(() =>
    form.firstname.trim() !== '' &&
    form.lastname.trim() !== '' &&
    form.dob.trim() !== '' &&
    form.gender !== '' &&
    form.citizenship !== '' &&
    form.country !== '' &&
    form.city.trim() !== '' &&
    form.contact !== '' &&
    form.email.trim() !== ''
);

const submit = () => {
    if (isFormValid.value) {
        form.post('/verification-step-1');
    }
};

</script>

<template>
    <div class="min-h-screen bg-white font-inter">

        <NoNav />

        <div class="flex px-10 py-10">

            <!-- Step Indicator -->
            <div class="flex flex-col items-center gap-0 pt-2 shrink-0">
                <!-- Step 1 - Active -->
                <div class="w-9 h-9 rounded-full bg-[#007582] flex items-center justify-center shadow-md text-white text-sm font-semibold">
                    1
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

            <!-- Centered Form -->
            <div class="flex-1 flex justify-center">
                <div class="w-full max-w-2xl">

                    <form @submit.prevent="submit">

                        <!-- Photo Upload -->
                        <label class="block w-36 h-36 mb-6 cursor-pointer">
                            <input type="file" accept="image/*" class="hidden" @change="handlePhoto">
                            <div class="w-full h-full rounded-2xl border-2 border-dashed border-[#007582] flex flex-col items-center justify-center gap-2 hover:bg-teal-50 transition-colors">
                                <div v-if="photoPreview" class="w-full h-full rounded-2xl overflow-hidden">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </div>
                                <template v-else>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#007582" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                                        <circle cx="12" cy="13" r="3"/>
                                    </svg>
                                    <span class="text-xs text-[#007582] font-bold">Add Photo</span>
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
                                    <div class="flex-1">
                                        <input v-model="form.firstname" type="text" placeholder="First Name"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors"
                                            :class="{ 'border-red-400': form.errors.firstname }">
                                        <p v-if="form.errors.firstname" class="text-xs text-red-400 mt-1">{{ form.errors.firstname }}</p>
                                    </div>
                                    <div class="flex-1">
                                        <input v-model="form.middlename" type="text" placeholder="Middle Name"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors">
                                    </div>
                                    <div class="flex-1">
                                        <input v-model="form.lastname" type="text" placeholder="Last Name"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors"
                                            :class="{ 'border-red-400': form.errors.lastname }">
                                        <p v-if="form.errors.lastname" class="text-xs text-red-400 mt-1">{{ form.errors.lastname }}</p>
                                    </div>
                                </div>

                                <!-- Row 2 -->
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <input v-model="form.dob" type="date"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 focus:outline-none focus:border-[#007582] transition-colors"
                                            :class="{ 'border-red-400': form.errors.dob }">
                                        <p v-if="form.errors.dob" class="text-xs text-red-400 mt-1">{{ form.errors.dob }}</p>
                                    </div>
                                    <div class="flex-1">
                                        <select v-model="form.gender"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-400 focus:outline-none focus:border-[#007582] transition-colors appearance-none"
                                            :class="{ 'border-red-400': form.errors.gender }">
                                            <option value="" disabled selected>Gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>
                                        <p v-if="form.errors.gender" class="text-xs text-red-400 mt-1">{{ form.errors.gender }}</p>
                                    </div>
                                    <div class="flex-1">
                                        <select v-model="form.citizenship"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-400 focus:outline-none focus:border-[#007582] transition-colors appearance-none"
                                            :class="{ 'border-red-400': form.errors.citizenship }">
                                            <option value="" disabled selected>Citizenship</option>
                                            <option>Filipino</option>
                                            <option>American</option>
                                            <option>Other</option>
                                        </select>
                                        <p v-if="form.errors.citizenship" class="text-xs text-red-400 mt-1">{{ form.errors.citizenship }}</p>
                                    </div>
                                </div>

                                <!-- Row 3 -->
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <select v-model="form.country"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-400 focus:outline-none focus:border-[#007582] transition-colors appearance-none"
                                            :class="{ 'border-red-400': form.errors.country }">
                                            <option value="" disabled selected>Country of Residence</option>
                                            <option>Philippines</option>
                                            <option>United States</option>
                                            <option>Other</option>
                                        </select>
                                        <p v-if="form.errors.country" class="text-xs text-red-400 mt-1">{{ form.errors.country }}</p>
                                    </div>
                                    <div class="flex-1">
                                        <input v-model="form.city" type="text" placeholder="City of Residence"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors"
                                            :class="{ 'border-red-400': form.errors.city }">
                                        <p v-if="form.errors.city" class="text-xs text-red-400 mt-1">{{ form.errors.city }}</p>
                                    </div>
                                </div>

                                <!-- Row 4 -->
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <input
                                            v-model="form.contact"
                                            type="tel"
                                            placeholder="Contact Number"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors"
                                            :class="{ 'border-red-400': form.errors.contact }">
                                        <p v-if="form.errors.contact" class="text-xs text-red-400 mt-1">{{ form.errors.contact }}</p>
                                        <p v-if="form.errors.contact" class="text-xs text-red-400 mt-1">{{ form.errors.contact }}</p>
                                    </div>
                                    <div class="flex-1">
                                        <input v-model="form.email" type="email" placeholder="Email"
                                            class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:border-[#007582] transition-colors"
                                            :class="{ 'border-red-400': form.errors.email }">
                                        <p v-if="form.errors.email" class="text-xs text-red-400 mt-1">{{ form.errors.email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Next Button -->
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                :disabled="!isFormValid || form.processing"
                                :class="[
                                    'font-semibold text-sm px-10 py-3 rounded-xl transition-colors shadow-md',
                                    isFormValid && !form.processing
                                        ? 'bg-[#007582] hover:bg-[#005f6b] text-white cursor-pointer'
                                        : 'bg-gray-200 text-gray-400 cursor-not-allowed'
                                ]"
                            >
                                {{ form.processing ? 'Saving...' : 'NEXT' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</template>