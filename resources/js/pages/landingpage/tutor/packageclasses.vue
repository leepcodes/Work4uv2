<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Ads from '@/components/interfaces/ads.vue'
import Navbar from '@/components/interfaces/navbar.vue'
import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'

interface ClassRow {
  id: number
  label: string
  date: string | null
  status: 'done' | 'upcoming' | 'to_be_scheduled' | 'schedulable'
}

interface Package {
  id: number
  uuid: string
  total_class_count: number
  complete_class_count: number
  remaining_class_count: number
  available_date: string | null
  purchase_date: string | null
  tutor_custom_price: number
  status: 'active' | 'completed'
}

interface Subject {
  id: number
  title: string
}

interface Student {
  id: number
  uuid: string
  name: string
  photo: string | null
}

const props = defineProps<{
  student: Student
  subject: Subject
  package: Package
}>()

const activeItem = ref('My Students')

const packageIndex = new URLSearchParams(window.location.search).get('pkg_index') ?? '1'

const classes = computed<ClassRow[]>(() => {
  const rows: ClassRow[] = []
  for (let i = 1; i <= props.package.total_class_count; i++) {
    let status: ClassRow['status']
    if (i <= props.package.complete_class_count) {
      status = 'done'
    } else {
      status = 'to_be_scheduled'
    }
    rows.push({
      id: i,
      label: `${props.subject.title} - Package ${packageIndex} - Class ${i}`,
      date: null,
      status,
    })
  }
  return rows
})
</script>

<template>
  <Navbar />
  <SidebarWithBtn
    v-model:activeItem="activeItem"
    breadcrumb="Tutoring &gt; My Students"
  />

  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 px-6 py-2 bg-white text-xs text-slate-400">
    <button @click="router.visit('/tutor/mystudents')" class="hover:text-teal-600 transition-colors flex items-center gap-1">
      <span>&lt;</span> Students List
    </button>
    <span>-</span>
    <button @click="router.visit(`/tutor/studentsubj/${student.uuid}`)" class="hover:text-teal-600 transition-colors">
      Student &amp; Subjects
    </button>
    <span>-</span>
    <span class="text-slate-600 font-medium">Package Classes</span>
  </div>

  <main class="flex w-full min-h-screen items-start justify-start bg-white">

    <!-- LEFT - Student + Package Info -->
    <div class="w-[22%] h-full px-6 py-5 border-r border-slate-100 flex flex-col">

      <div class="flex items-center gap-3 mb-1">
        <div class="w-11 h-11 rounded-full bg-gray-200 overflow-hidden shrink-0">
          <img
            :src="student.photo ?? '/images/tutor.jpg'"
            :alt="student.name"
            class="w-full h-full object-cover"
          />
        </div>
        <button class="text-slate-400 hover:text-teal-500 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </button>
      </div>

      <p class="text-sm font-bold text-slate-800 mt-2">{{ student.name }}</p>
      <p class="text-xs text-slate-400 mb-6">STU-{{ student.id }}</p>

      <p class="text-sm font-bold text-slate-800 mb-3">
        {{ subject.title }} - Package {{ packageIndex }}
      </p>
      <p class="text-xs text-slate-500">
        Purchase Date <span class="font-semibold text-slate-700">{{ package.purchase_date ?? '—' }}</span>
      </p>
      <p class="text-xs text-slate-500 mt-1">
        Package Price <span class="font-semibold text-slate-700">{{ package.tutor_custom_price }} US$</span>
      </p>
    </div>

    <!-- RIGHT - Class Schedule -->
    <div class="flex-1 px-6 py-5 flex flex-col gap-3">
      <p class="text-xs font-semibold text-slate-500 mb-1">Class Schedule</p>

      <div class="flex flex-col gap-2">
        <div
          v-for="cls in classes"
          :key="cls.id"
          :class="[
            'flex items-center justify-between px-4 py-3 rounded-xl border',
            cls.status === 'done' ? 'bg-green-50 border-green-100' :
            cls.status === 'upcoming' ? 'bg-orange-50 border-orange-100' :
            cls.status === 'to_be_scheduled' ? 'bg-[#EEEEFF] border-[#c7c7f5]' :
            'bg-white border-slate-100'
          ]"
        >
          <div class="flex items-center gap-3">
            <div class="shrink-0">
              <!-- Done -->
              <svg v-if="cls.status === 'done'" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00A63E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.801 10A10 10 0 1 1 17 3.335"/>
                <path d="m9 11 3 3L22 4"/>
              </svg>
              <!-- To Be Scheduled -->
              <svg v-else-if="cls.status === 'to_be_scheduled'" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#5759D1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
              <!-- Upcoming -->
              <svg v-else-if="cls.status === 'upcoming'" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F54900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
              <!-- Schedulable -->
              <svg v-else xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
            </div>

            <div>
              <p :class="['text-xs font-semibold', cls.status === 'schedulable' ? 'text-slate-400' : 'text-slate-700']">
                {{ cls.label }}
              </p>
              <p v-if="cls.date" class="text-xs text-slate-400">{{ cls.date }}</p>
            </div>
          </div>

          <span :class="[
            'text-xs px-3 py-0.5 rounded-full font-medium whitespace-nowrap',
            cls.status === 'done' ? 'bg-green-100 text-green-600' :
            cls.status === 'upcoming' ? 'bg-orange-100 text-orange-500' :
            cls.status === 'to_be_scheduled' ? 'bg-[#EEEEFF] text-[#5759D1]' :
            'bg-slate-100 text-slate-400'
          ]">
            {{ cls.status === 'done' ? 'Done' :
               cls.status === 'upcoming' ? 'Upcoming' :
               cls.status === 'to_be_scheduled' ? 'To Be Scheduled' :
               'Schedule' }}
          </span>
        </div>
      </div>
    </div>

    <Ads />
  </main>
</template>