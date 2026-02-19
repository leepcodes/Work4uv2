<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import NoNav from '@/components/interfaces/nonav.vue';

const props = defineProps<{
    savedData: {
        description: string;
    }
}>();

const form = useForm({
    id_front:     null as File | null,
    id_back:      null as File | null,
    cv_resume:    null as File | null,
    certificates: [] as File[],
    description:  props.savedData?.description ?? '',
});

const handleFile = (e: Event, field: 'id_front' | 'id_back' | 'cv_resume') => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (file) form[field] = file;
};

const handleCerts = (e: Event) => {
    const files = (e.target as HTMLInputElement).files;
    if (files) form.certificates = [...form.certificates, ...Array.from(files)];
};

const removeCert = (index: number) => {
    form.certificates.splice(index, 1);
};

const formatSize = (bytes: number) => {
    const kb = bytes / 1024;
    return kb < 1024 ? `${kb.toFixed(0)} Kb` : `${(kb / 1024).toFixed(1)} Mb`;
};

const isFormValid = computed(() =>
    form.id_front !== null &&
    form.description.trim().length > 0
);

const goBack = () => window.location.href = '/verification-step-2';

const submit = () => {
    if (isFormValid.value) {
        form.post('/verification-step-3', {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <div class="min-h-screen bg-white font-inter">

        <NoNav />

        <div class="flex px-10 py-10">

            <!-- Steps -->
            <div class="flex flex-col items-center gap-0 pt-2 shrink-0">
                <div class="w-9 h-9 rounded-full bg-[#007582] flex items-center justify-center shadow-md text-white text-sm font-semibold">
                    1
                </div>
                <div class="w-[2px] h-16 bg-[#007582]"></div>
                <div class="w-9 h-9 rounded-full bg-[#007582] flex items-center justify-center shadow-md text-white text-sm font-semibold">
                    2
                </div>
                <div class="w-[2px] h-16 bg-[#007582]"></div>
                <div class="w-9 h-9 rounded-full bg-[#007582] flex items-center justify-center shadow-md text-white text-sm font-semibold">
                    3
                </div>
            </div>

            <div class="flex-1 flex justify-center">
                <div class="w-full max-w-2xl">

                    <form @submit.prevent="submit">

                        <!-- Header -->
                        <div class="flex items-start justify-between mb-6">
                            <h2 class="text-xs font-bold text-gray-700 uppercase tracking-widest">Upload Your Documents</h2>
                            <p class="text-xs text-red-400 italic">ID and Picture required, CV, certification and presentation recommended*</p>
                        </div>

                        <!-- Upload Fields -->
                        <div class="flex flex-col gap-6 mb-8">

                            <!-- ID Front -->
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">ID Card (FRONT)</p>
                                <p class="text-xs text-gray-400 mb-2">Upload clear photo of the front side of your ID card.</p>
                                <label class="cursor-pointer inline-block">
                                    <input type="file" class="hidden" accept="image/*,.pdf" @change="(e) => handleFile(e, 'id_front')">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#007582" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                            <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                                        </svg>
                                        <span v-if="form.id_front" class="text-xs text-[#007582] font-medium">{{ form.id_front.name }}</span>
                                    </div>
                                </label>
                                <p v-if="form.errors.id_front" class="text-xs text-red-400 mt-1">{{ form.errors.id_front }}</p>
                            </div>

                            <!-- ID Back -->
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">ID Card (Back) <span class="text-gray-400 font-normal">Optional</span></p>
                                <p class="text-xs text-gray-400 mb-2">Upload clear photo of the back side of your ID card.</p>
                                <label class="cursor-pointer inline-block">
                                    <input type="file" class="hidden" accept="image/*,.pdf" @change="(e) => handleFile(e, 'id_back')">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#007582" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                            <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                                        </svg>
                                        <span v-if="form.id_back" class="text-xs text-[#007582] font-medium">{{ form.id_back.name }}</span>
                                    </div>
                                </label>
                            </div>

                            <!-- CV / Resume -->
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">CV/ Resume</p>
                                <p class="text-xs text-gray-400 mb-2">Upload your CV or Resume.</p>
                                <label class="cursor-pointer inline-block">
                                    <input type="file" class="hidden" accept=".pdf,.doc,.docx" @change="(e) => handleFile(e, 'cv_resume')">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#007582" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                            <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                                        </svg>
                                        <span v-if="form.cv_resume" class="text-xs text-[#007582] font-medium">{{ form.cv_resume.name }}</span>
                                    </div>
                                </label>
                            </div>

                            <!-- Certifications -->
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Certifications/Presentations</p>
                                <p class="text-xs text-gray-400 mb-2">Upload your certifications or presentations.</p>
                                <label class="cursor-pointer inline-block mb-3">
                                    <input type="file" class="hidden" accept=".pdf,.doc,.docx,image/*" multiple @change="handleCerts">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#007582" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                        <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                                    </svg>
                                </label>

                                <div class="flex flex-col gap-2">
                                    <div
                                        v-for="(file, i) in form.certificates"
                                        :key="i"
                                        class="flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 bg-white w-full max-w-md"
                                    >
                                        <div class="w-8 h-8 rounded-md bg-red-500 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-700 font-medium truncate">{{ file.name }}</p>
                                            <p class="text-xs text-gray-400">{{ formatSize(file.size) }}</p>
                                        </div>
                                        <button type="button" @click="removeCert(i)" class="text-gray-300 hover:text-red-400 transition-colors shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Description -->
                        <div class="mb-10">
                            <div class="flex items-center gap-3 mb-3">
                                <p class="text-sm font-semibold text-gray-700">Describe your skills, approach, your teaching method and tell us why a student should you! (max 5000 characters)</p>
                                <span class="text-xs text-red-400 italic whitespace-nowrap">Required *</span>
                            </div>
                            <textarea
                                v-model="form.description"
                                maxlength="5000"
                                rows="6"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-300 focus:outline-none focus:border-[#007582] transition-colors resize-none"
                                :class="{ 'border-red-400': form.errors.description }"
                            ></textarea>
                            <p v-if="form.errors.description" class="text-xs text-red-400 mt-1">{{ form.errors.description }}</p>
                        </div>

                        <!-- Buttons -->
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