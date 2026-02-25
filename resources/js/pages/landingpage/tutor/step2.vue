<script setup lang="ts">
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import NoNav from '@/components/interfaces/nonav.vue';

const availableLanguages = [
    'Arabic', 'English', 'French', 'Spanish', 'Mandarin',
    'Japanese', 'German', 'Filipino', 'Korean', 'Italian',
];

const dropdownOpen = ref(false);

const props = defineProps<{
    savedData: {
        languages: string[];
        services: string[];
        subjects: string;
    }
}>();

const form = useForm({
    languages: props.savedData?.languages ?? [],
    subjects:  props.savedData?.subjects  ?? '',
    services:  props.savedData?.services  ?? [],
});

const serviceOptions = ref([
    { id: 'recovery', label: 'Recovery Lessons' },
    { id: 'kids',     label: 'Kids with Learning Difficulties' },
    { id: 'preexam',  label: 'Pre Exam Classes' },
    { id: 'deaf',     label: 'Deaf Language' },
    { id: 'own',      label: 'Own Program' },
]);

const toggleLanguage = (lang: string) => {
    const idx = form.languages.indexOf(lang);
    if (idx === -1) {
        form.languages.push(lang);
    } else {
        form.languages.splice(idx, 1);
    }
};

const removeLanguage = (lang: string) => {
    form.languages = form.languages.filter(l => l !== lang);
};

const toggleService = (id: string) => {
    const idx = form.services.indexOf(id);
    if (idx === -1) {
        form.services.push(id);
    } else {
        form.services.splice(idx, 1);
    }
};

const isFormValid = computed(() =>
    form.languages.length > 0 &&
    form.services.length > 0
);

const goBack = () => window.location.href = '/verification-step-1';

const submit = () => {
    if (isFormValid.value) {
        form.post('/verification-step-2');
    }
};
</script>

<template>
    <div class="min-h-screen bg-white font-inter" @click.self="dropdownOpen = false">

        <NoNav />

        <div class="flex px-10 py-10">

            <!-- Step Indicator -->
            <div class="flex flex-col items-center gap-0 pt-2 shrink-0">
                <div class="w-9 h-9 rounded-full bg-[#007582] flex items-center justify-center shadow-md text-white text-sm font-semibold">
                    1
                </div>
                <div class="w-[2px] h-16 bg-[#007582]"></div>
                <div class="w-9 h-9 rounded-full bg-[#007582] flex items-center justify-center shadow-md text-white text-sm font-semibold">
                    2
                </div>
                <div class="w-[2px] h-16 bg-gray-200"></div>
                <div class="w-9 h-9 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center text-gray-400 text-sm font-semibold">
                    3
                </div>
            </div>

            
            <div class="flex-1 flex justify-center">
                <div class="w-full max-w-2xl">

                    <form @submit.prevent="submit">

                        
                        <div class="mb-6">
                            <h2 class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-4">Languages</h2>

                           
                            <div class="relative w-72 mb-1">
                                <button
                                    type="button"
                                    @click.stop="dropdownOpen = !dropdownOpen"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-sm text-gray-500 flex items-center justify-between focus:outline-none focus:border-[#007582] transition-colors"
                                    :class="{ 'border-red-400': form.errors.languages }"
                                >
                                    <span>Choose your languages</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="['transition-transform', dropdownOpen ? 'rotate-180' : '']">
                                        <polyline points="6 9 12 15 18 9"/>
                                    </svg>
                                </button>

                                
                                <div v-if="dropdownOpen" class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                                    <div
                                        v-for="lang in availableLanguages"
                                        :key="lang"
                                        @click="toggleLanguage(lang)"
                                        class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-teal-50 cursor-pointer transition-colors"
                                    >
                                        <span>{{ lang }}</span>
                                        <svg v-if="form.languages.includes(lang)" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#007582" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <p v-if="form.errors.languages" class="text-xs text-red-400 mt-1">{{ form.errors.languages }}</p>
                            <p class="text-xs text-[#007582] italic mb-3">(You can select more then one language)</p>

                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span
                                    v-for="lang in form.languages"
                                    :key="lang"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-[#007582] bg-[#e6f7f8] text-[#007582] text-xs font-medium"
                                >
                                    {{ lang }}
                                    <button type="button" @click="removeLanguage(lang)" class="hover:text-red-400 transition-colors font-bold leading-none">✕</button>
                                </span>
                            </div>

                            
                            <textarea
                                v-model="form.subjects"
                                placeholder="French, Math, x y z"
                                rows="4"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:border-[#007582] transition-colors resize-none"
                            ></textarea>
                        </div>

                        
                        <div class="mb-10">
                            <h2 class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-4">What Services Are You Going to Provide</h2>

                            <div class="grid grid-cols-2 gap-y-3 gap-x-6 mb-2">
                                <label
                                    v-for="service in serviceOptions"
                                    :key="service.id"
                                    class="flex items-center gap-3 cursor-pointer group"
                                >
                                    <div
                                        @click="toggleService(service.id)"
                                        :class="[
                                            'w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors shrink-0',
                                            form.services.includes(service.id)
                                                ? 'bg-[#007582] border-[#007582]'
                                                : 'bg-white border-gray-300 group-hover:border-[#007582]'
                                        ]"
                                    >
                                        <svg v-if="form.services.includes(service.id)" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ service.label }}</span>
                                </label>
                            </div>

                            <p v-if="form.errors.services" class="text-xs text-red-400 mt-1">{{ form.errors.services }}</p>
                            <p class="text-xs text-red-400 italic mt-2">Required, you can select more then one.*</p>
                        </div>

                       
                        <div class="flex justify-end gap-3">
                            <button
                                type="button"
                                @click="goBack"
                                class="border border-gray-300 text-gray-600 font-semibold text-sm px-10 py-3 rounded-xl hover:bg-gray-50 transition-colors"
                            >
                                BACK
                            </button>
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