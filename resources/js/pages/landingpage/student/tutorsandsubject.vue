<script setup lang="ts">
import { ref,  onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Ads from '@/components/interfaces/ads.vue'
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'

interface Schedule {
  id: number
  class_count: number
  created_at: string
  available_date: string | null
  tutor: {
    name: string
    photo: string | null
  }
  subject: {
    title: string
  }
}

const props = defineProps<{
  classes: Schedule[]
}>()

const sidebarOpen = ref(false)
const activeItem = ref('Home')
const selectedTutor = ref<Schedule | null>(null)
const expandedSubjects = ref<number[]>([])
const activeTab = ref('tutors')

const toggleSubject = (id: number) => {
  if (expandedSubjects.value.includes(id)) {
    expandedSubjects.value = expandedSubjects.value.filter(s => s !== id)
  } else {
    expandedSubjects.value.push(id)
  }
}

let pollingInterval: ReturnType<typeof setInterval> | null = null

const handleVisibility = () => {
  if (document.visibilityState === 'visible') {
    router.reload({ only: ['classes'] })
  }
}

onMounted(() => {
  pollingInterval = setInterval(() => {
    router.reload({ only: ['classes'] })
  }, 10000)
  document.addEventListener('visibilitychange', handleVisibility)
})

onUnmounted(() => {
  if (pollingInterval) clearInterval(pollingInterval)
  document.removeEventListener('visibilitychange', handleVisibility)
})
</script>

<template>
  <Navbar />
  <TutoringSidebar v-model="sidebarOpen" v-model:activeItem="activeItem" />

  <!-- Top bar -->
  <div class="flex items-center bg-white gap-2 px-6 pt-5 pb-3">
    <button @click="sidebarOpen = true" class="text-[#139aa2] hover:text-teal-600 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
    <span class="text-sm font-bold text-[#139aa2]">Tutoring &gt; My Classes</span>
  </div>

  <main class="flex w-full h-full items-start justify-start bg-white">

    <!-- LEFT -->
    <div class="w-[30%] h-full px-6 py-4">

      <!-- Tabs -->
      <div class="flex items-center gap-6 mb-5">
        <button
          @click="activeTab = 'tutors'"
          class="text-xs font-semibold transition-colors flex items-center text-black gap-1"
          
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Tutors List
        </button>
        <span class="text-black text-xs">-</span>
        <button
          @click="activeTab = 'subjects'"
          class="text-xs font-semibold transition-colors text-black"
          
        >
          Tutors & Subjects
        </button>
      </div>

      <!-- Tutor List -->
      <div v-if="activeTab === 'tutors'" class="flex flex-col gap-3">
        <div v-if="classes.length === 0" class="text-center text-xs text-slate-400 mt-10">
          No classes found.
        </div>
        <div
          v-for="item in classes"
          :key="item.id"
          @click="selectedTutor = item; activeTab = 'subjects'"
          class="flex items-center gap-3 cursor-pointer hover:bg-slate-50 rounded-xl p-2 transition-colors"
          :class="selectedTutor?.id === item.id ? 'bg-slate-50' : ''"
        >
          <img
            :src="item.tutor.photo ?? '/images/tutor.jpg'"
            :alt="item.tutor.name"
            class="w-10 h-10 rounded-full object-cover border border-slate-200 flex-shrink-0"
          />
          <div>
            <p class="text-sm font-semibold text-slate-800">{{ item.tutor.name }}</p>
          </div>
        </div>
      </div>

      <!-- Tutor Detail (when selected) -->
      <div v-if="activeTab === 'subjects' && selectedTutor" class="flex flex-col gap-2">
        <div class="flex items-center gap-3 mb-4">
          <img
            :src="selectedTutor.tutor.photo ?? '/images/tutor.jpg'"
            :alt="selectedTutor.tutor.name"
            class="w-10 h-10 rounded-full object-cover border border-slate-200"
          />
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
          </div>
        </div>
        <p class="text-sm font-bold text-slate-800">{{ selectedTutor.tutor.name }}</p>
        <p class="text-xs text-slate-400 mb-4">ID{{ selectedTutor.id }}</p>
      </div>

    </div>

    <!-- MIDDLE -->
    <div class="w-[60%] h-full px-6 py-4">

      <div v-if="selectedTutor" class="flex flex-col gap-3">

        <!-- Subject Accordion -->
        <div class="border border-slate-200 rounded-xl overflow-hidden">
          <button
            @click="toggleSubject(selectedTutor.id)"
            class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-slate-50 transition-colors"
          >
            <div class="flex items-center gap-2">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-4 h-4 text-slate-400 transition-transform"
                :class="expandedSubjects.includes(selectedTutor.id) ? 'rotate-0' : '-rotate-90'"
                fill="none" viewBox="0 0 24 24" stroke="currentColor"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
              <span class="text-sm font-semibold text-slate-800">{{ selectedTutor.subject.title }}</span>
            </div>
            <span class="text-xs text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">1 Package</span>
          </button>

          <!-- Expanded Content -->
          <div v-if="expandedSubjects.includes(selectedTutor.id)" class="px-4 py-3 bg-white border-t border-slate-100">
            <div class="border border-slate-200 rounded-xl p-4 mb-2">
                
                <p class="text-sm font-semibold text-slate-700 mb-3">Package 1</p>
                
                <div class="flex items-center gap-6 mb-2">
                <p class="text-xs text-slate-500">{{ selectedTutor.class_count }} Classes Purchased</p>
                <p class="text-xs text-slate-500">— Classes Completed</p>
                <p class="text-xs text-slate-500">
                    Next Class: <span class="font-bold text-slate-800">{{ selectedTutor.available_date ?? 'Not set' }}</span>
                </p>
                <p class="text-xs text-teal-500">— days</p>
                </div>

                <div class="flex items-center gap-6">
                <p class="text-xs text-slate-400">Purchase Date: — {{ selectedTutor.created_at }}</p>
                <p class="text-xs text-slate-400">Package Price: —</p>
                </div>

            </div>
            </div>
        </div>

      </div>

      <div v-else class="flex items-center justify-center h-64 text-slate-300">
        <div class="text-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          <p class="text-sm">Select a tutor to view classes</p>
        </div>
      </div>

    </div>

    <!-- ADS SIDEBAR -->
    <Ads />

  </main>
</template>