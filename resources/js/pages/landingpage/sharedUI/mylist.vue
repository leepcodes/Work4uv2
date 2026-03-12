<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'
import Navbar from '@/components/interfaces/navbar.vue'
import Ads from '@/components/interfaces/ads.vue'

interface Person {
  id: number
  name: string
  photo: string | null
}

interface Schedule {
  id: number
  uuid?: string
  total_class_count: number
  complete_class_count: number
  remaining_class_count: number
  available_date: string | null
  student?: Person
  tutor?: Person
  subject: {
    title: string
  }
}

const props = defineProps<{
  items: Schedule[]
  role: 'tutor' | 'student'
  reloadKey: string
  backPath?: string
  package?: { uuid: string }
}>()

const search = ref('')

const personKey = computed(() => props.role === 'tutor' ? 'student' : 'tutor')
const searchPlaceholder = computed(() => props.role === 'tutor' ? 'Search student name' : 'Search tutor name')

const getPerson = (schedule: Schedule): Person => {
  return (schedule[personKey.value] as Person) ?? { id: 0, name: 'Unknown', photo: null }
}

const groupedItems = computed(() => {
  const map = new Map<string, { schedule: Schedule; subjects: string[]; activeCount: number }>()

  for (const c of props.items) {
    const person = getPerson(c)
    if (!person.name.toLowerCase().includes(search.value.toLowerCase())) continue

    const key = String(person.id)
    const isActive = c.total_class_count !== c.complete_class_count

    if (map.has(key)) {
      const entry = map.get(key)!
      entry.subjects.push(c.subject.title)
      if (isActive) entry.activeCount++
    } else {
      map.set(key, { schedule: c, subjects: [c.subject.title], activeCount: isActive ? 1 : 0 })
    }
  }

  return [...map.values()]
})

let pollingInterval: ReturnType<typeof setInterval> | null = null

const handleVisibility = () => {
  if (document.visibilityState === 'visible') {
    router.reload({ only: [props.reloadKey] })
  }
}

onMounted(() => {
  pollingInterval = setInterval(() => {
    router.reload({ only: [props.reloadKey] })
  }, 10000)
  document.addEventListener('visibilitychange', handleVisibility)
})

onUnmounted(() => {
  if (pollingInterval) clearInterval(pollingInterval)
  document.removeEventListener('visibilitychange', handleVisibility)
})

const handleCardClick = (schedule: Schedule) => {
  if (props.role === 'tutor' && schedule.uuid) {
    router.visit(`/tutor/studentsubj/${schedule.uuid}`)
  } else if (props.role === 'student' && schedule.uuid) {
    router.visit(`/student/tutorsandsubject/${schedule.uuid}`)
  }
}
</script>

<template>
  <Navbar />
  <SidebarWithBtn :breadcrumb="role === 'tutor' ? 'Tutoring &gt; My Students' : 'Tutoring &gt; My Tutors'" />

  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 px-6 py-2 bg-white text-xs text-slate-400">
    <span>{{ role === 'tutor' ? 'Students List' : 'Tutors List' }}</span>
    <span>-</span>
    <span class="text-slate-600 font-medium">{{ role === 'tutor' ? 'Student & Subjects' : 'Tutor & Subjects' }}</span>
  </div>

  <main class="flex w-full h-auto items-start justify-start bg-white">

    <!-- LEFT -->
    <div class="w-[90%] h-auto px-6 py-4 ">


      <div class="grid grid-cols-2 gap-4 w-180 ">
        <div
          v-for="{ schedule, subjects, activeCount } in groupedItems"
          :key="schedule.id"
          @click="handleCardClick(schedule)"
          class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
        >
          <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-gray-200 overflow-hidden shrink-0">
              <img
                :src="getPerson(schedule).photo ?? '/images/tutor.jpg'"
                :alt="getPerson(schedule).name"
                class="w-full h-full object-cover"
              />
            </div>
            <p class="text-sm font-semibold text-gray-800">{{ getPerson(schedule).name }}</p>
          </div>

        <div class="flex flex-wrap gap-1 mb-3">
          <span
            v-for="subject in [...new Set(subjects.map((s: any) => s.title ?? s))]"
            :key="subject"
            class="text-xs px-2 py-0.5 rounded-full font-medium bg-teal-100 text-teal-600"
          >
            {{ subject }}
          </span>
        </div>

          <div class="flex flex-col gap-0.5 text-xs text-gray-500">
            <p>Active Package <span class="font-bold text-gray-800">: {{ activeCount }}</span></p>
            <p>Remaining Classes <span class="font-bold text-gray-800">: {{ schedule.remaining_class_count }}</span></p>
            <p>Next Class <span class="font-bold text-gray-800">: {{ schedule.available_date ?? 'Not set' }}</span></p>
          </div>
        </div>
      </div>

    </div>

  
    <Ads />
  
   

  </main>
</template>