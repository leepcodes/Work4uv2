<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Navbar from '@/components/interfaces/navbar.vue'
import Ads from '@/components/interfaces/ads.vue'
import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'

interface ClassRow {
  id: number
  uuid: string  
  label: string
  date: string | null
  status: 'done' | 'upcoming' | 'to_be_scheduled' | 'schedulable' | 'failed' | 'completed'
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

interface Person {
  id: number
  uuid: string
  name: string
  photo: string | null
}

const props = defineProps<{
  role: 'tutor' | 'student'
  person: Person
  subject: Subject
  package: Package
  classes: ClassRow[]
}>()

const activeItem = ref(props.role === 'tutor' ? 'My Students' : 'Home')
const packageIndex = new URLSearchParams(window.location.search).get('pkg_index') ?? '1'

const backPath = computed(() =>
  props.role === 'tutor'
    ? `/tutor/studentsubj/${props.person.uuid}`
    : `/student/tutorsandsubject/${props.package.uuid}`
)

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
 <SidebarWithBtn breadcrumb="Tutoring &gt; Package Classes" />

  <!-- Breadcrumb -->
   <div class="flex items-center gap-2 px-6 py-2 bg-white text-xs text-slate-400">
    <span>{{ role === 'tutor' ? 'Students List' : 'Tutors List' }}</span>
    <span>-</span>
    <span>{{ role === 'tutor' ? 'Student & Subjects' : 'Tutor & Subjects' }}</span>
    <span>-</span>
    <span>Package Classes</span>
    
  </div>

  <main class="flex w-full h-full items-start justify-start bg-white">


    <div class="w-[25%] h-[100vh] px-6 py-4 " >
      <div class="flex items-center gap-3 mb-1">
        <div class="w-11 h-11 rounded-full bg-gray-200 overflow-hidden shrink-0">
          <img
            :src="person.photo ?? '/images/tutor.jpg'"
            :alt="person.name"
            class="w-full h-full object-cover"
          />
        </div>
        <button class="text-slate-400 hover:text-teal-500 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </button>
      </div> 
      
      <p class="text-sm font-bold text-slate-800 mt-2">{{ person.name }}</p>
      <p class="text-xs text-slate-400 mb-6">{{ role === 'tutor' ? 'STU' : 'TUT' }}-{{ person.id }}</p>

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



  
    <div class="w-[70%] h-[100vh] px-6 py-4">
      <div class="flex-1 px-6 py-5 flex flex-col gap-3">
      <p class="text-xs font-semibold text-slate-500 mb-1">Class Schedule</p>

      <div class="flex flex-col gap-2">
        <div
          v-for="cls in classes"
          :key="cls.id"
          @click="router.visit(`/${role}/classdetails/${package.uuid}/${cls.uuid}`)"
          :class="[
              'flex items-center justify-between px-4 py-3 rounded-xl border cursor-pointer hover:shadow-sm transition-shadow',
              cls.status === 'done' ? 'bg-green-50 border-green-100' :
              cls.status === 'upcoming' ? 'bg-orange-50 border-orange-100' :
              cls.status === 'to_be_scheduled' ? 'bg-[#EEEEFF] border-[#c7c7f5]' :
              cls.status === 'failed' ? 'bg-red-50 border-red-100' :
              cls.status === 'completed' ? 'bg-green-50 border-green-100' :
              'bg-white border-slate-100'
            ]"
        >
          <div class="flex items-center gap-3">
            <div class="shrink-0">
              <svg v-if="cls.status === 'done'" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00A63E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.801 10A10 10 0 1 1 17 3.335"/>
                <path d="m9 11 3 3L22 4"/>
              </svg>
              <svg v-else-if="cls.status === 'to_be_scheduled'" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#5759D1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
              <svg v-else-if="cls.status === 'upcoming'" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F54900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
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
    </div>

   
    <Ads/>
  </main>



</template>