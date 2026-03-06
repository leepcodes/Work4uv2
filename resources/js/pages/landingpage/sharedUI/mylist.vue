<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'

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
  <div class="relative mb-5 w-full max-w-[220px]">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
    </svg>
    <input
      v-model="search"
      type="text"
      :placeholder="searchPlaceholder"
      class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#139aa2]"
    />
  </div>

  <div v-if="groupedItems.length === 0" class="text-center text-xs text-slate-400 mt-10">
    No {{ role === 'tutor' ? 'students' : 'classes' }} found.
  </div>

  <div class="grid grid-cols-3 gap-4">
    <div
      v-for="{ schedule, subjects, activeCount } in groupedItems"
      :key="schedule.id"
      @click="handleCardClick(schedule)"
      class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow"
      :class="role === 'tutor' ? 'cursor-pointer' : ''"
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
          v-for="subject in subjects"
          :key="subject"
          class="text-xs px-2 py-0.5 rounded-full font-medium bg-teal-100 text-teal-600"
        >
          {{ subject }}
        </span>
      </div>

      
      <div class="flex flex-col gap-0.5 text-xs text-gray-500">
        <p>
          Active Package
          <span class="font-bold text-gray-800"> : {{ activeCount }}</span>
        </p>
        <p>Remaining Classes <span class="font-bold text-gray-800"> : {{ schedule.remaining_class_count }}</span></p>
        <p>Next Class <span class="font-bold text-gray-800"> : {{ schedule.available_date ?? 'Not set' }}</span></p>
      </div>
    </div>
  </div>
</template>