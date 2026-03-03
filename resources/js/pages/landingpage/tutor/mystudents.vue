<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Ads from '@/components/interfaces/ads.vue'
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'

interface Schedule {
  id: number
  total_class_count: number
  complete_class_count: number
  remaining_class_count: number
  available_date: string | null
  student: {
    name: string
    photo: string | null
  }
  subject: {
    title: string
  }
}

const props = defineProps<{
  students: Schedule[]
}>()

const sidebarOpen = ref(false)
const activeItem = ref('Home')
const search = ref('')

const filteredStudents = computed(() =>
  props.students.filter(c =>
    c.student.name.toLowerCase().includes(search.value.toLowerCase())
  )
)

let pollingInterval: ReturnType<typeof setInterval> | null = null

const handleVisibility = () => {
  if (document.visibilityState === 'visible') {
    router.reload({ only: ['students'] })
  }
}

onMounted(() => {
  pollingInterval = setInterval(() => {
    router.reload({ only: ['students'] })
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

  <div class="flex items-center bg-white gap-2 px-6 pt-5 pb-3">
    <button @click="sidebarOpen = true" class="text-[#139aa2] hover:text-teal-600 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
    <span class="text-sm font-bold text-[#139aa2]">Tutoring &gt; My Students</span>
  </div>

  <main class="flex w-full h-full items-start justify-start bg-white">
    <div class="flex-1 h-full px-6 py-4">

      <div class="relative mb-5 w-full max-w-[220px]">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
        </svg>
        <input
          v-model="search"
          type="text"
          placeholder="Search student name"
          class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#139aa2]"
        />
      </div>

      <div v-if="filteredStudents.length === 0" class="text-center text-xs text-slate-400 mt-10">
        No students found.
      </div>

      <div class="grid grid-cols-3 gap-4">
        <div
          v-for="item in filteredStudents"
          :key="item.id"
          class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
        >
          <!-- Student row -->
          <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-gray-200 overflow-hidden shrink-0">
              <img
                :src="item.student.photo ?? '/images/tutor.jpg'"
                :alt="item.student.name"
                class="w-full h-full object-cover"
              />
            </div>
            <p class="text-sm font-semibold text-gray-800">{{ item.student.name }}</p>
          </div>

          <!-- Subject tag -->
          <div class="flex flex-wrap gap-1 mb-3">
            <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-teal-100 text-teal-600">
              {{ item.subject.title }}
            </span>
          </div>

          <!-- Info -->
          <div class="flex flex-col gap-0.5 text-xs text-gray-500">
            <p>Remaining Classes <span class="font-bold text-gray-800">{{ item.remaining_class_count }}</span></p>
            <p>Total Classes <span class="font-bold text-gray-800">{{ item.total_class_count }}</span></p>
            <p>Completed <span class="font-bold text-gray-800">{{ item.complete_class_count }}</span></p>
            <p>Next Class <span class="font-bold text-gray-800">{{ item.available_date ?? 'Not set' }}</span></p>
          </div>
        </div>
      </div>

    </div>
    <Ads />
  </main>
</template>