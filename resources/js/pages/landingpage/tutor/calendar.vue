<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Navbar from '@/components/interfaces/navbar.vue'
import Ads from '@/components/interfaces/ads.vue'
import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'

interface TimeSlot {
  id: number
  start_time: string
  end_time: string
}

interface DayOff {
  id: number
  day_off_date: string
  reason?: string
}

const props = defineProps<{
  timeSlots: TimeSlot[]
  daysOff: DayOff[]
}>()

const activeItem = ref('Schedule')
const showCalendarSetup = ref(false)
const setupTab = ref<'slots' | 'daysoff'>('slots')
const selectedSlot = ref('')
const selectedDayOff = ref('')

const timeOptions = [
  '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
  '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
  '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
  '18:00', '18:30', '19:00', '19:30',
]

const dayOptions = [
  'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
]

const addSlot = () => {
  if (!selectedSlot.value) return
  const [h, m] = selectedSlot.value.split(':').map(Number)
  const endMin = m + 50
  const endH = endMin >= 60 ? h + 1 : h
  const endM = endMin >= 60 ? endMin - 60 : endMin
  const end = `${String(endH).padStart(2, '0')}:${String(endM).padStart(2, '0')}`

  router.post('/tutor/calendar/slot', {
    start_time: selectedSlot.value,
    end_time: end,
  }, {
    preserveState: true,
    onSuccess: () => { selectedSlot.value = '' },
  })
}

const addDayOff = () => {
  if (!selectedDayOff.value) return

  router.post('/tutor/calendar/dayoff', {
    day_off_date: selectedDayOff.value,
  }, {
    preserveState: true,
    onSuccess: () => { selectedDayOff.value = '' },
  })
}

const deleteSlot = (id: number) => {
  router.delete(`/tutor/calendar/${id}`, { preserveState: true })
}

const deleteDayOff = (id: number) => {
  router.delete(`/tutor/calendar/${id}`, { preserveState: true })
}

const currentDate = ref(new Date())

const monthName = computed(() =>
  currentDate.value.toLocaleString('default', { month: 'long', year: 'numeric' })
)

const prevMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1)
}

const nextMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1)
}

const daysInMonth = computed(() => {
  const year = currentDate.value.getFullYear()
  const month = currentDate.value.getMonth()
  const firstDay = new Date(year, month, 1).getDay()
  const totalDays = new Date(year, month + 1, 0).getDate()
  const prevDays = new Date(year, month, 0).getDate()
  const startOffset = firstDay === 0 ? 6 : firstDay - 1
  const days = []

  for (let i = startOffset - 1; i >= 0; i--) {
    days.push({ day: prevDays - i, current: false, isDayOff: false, dots: [] as string[] })
  }

  for (let d = 1; d <= totalDays; d++) {
    const dateObj = new Date(year, month, d)
    const dayName = dateObj.toLocaleString('default', { weekday: 'long' }) // e.g. "Monday", "Sunday"
    const isDayOff = props.daysOff.some(o => o.day_off_date === dayName)
    const dots: string[] = []

    if (isDayOff) {
      dots.push('red')
    } else if (props.timeSlots.length > 0) {
      dots.push('green')
    }

    days.push({ day: d, current: true, isDayOff, dots })
  }

  const remaining = 42 - days.length
  for (let i = 1; i <= remaining; i++) {
    days.push({ day: i, current: false, isDayOff: false, dots: [] as string[] })
  }

  return days
})

const classes = ref([
  { avatar: '/images/tutor.jpg', name: 'John Brown', course: 'PHP crash course (1/3)', date: '12.5.2025 • 13:00' },
  { avatar: '/images/tutor.jpg', name: 'John Brown', course: 'PHP crash course (1/3)', date: '12.5.2025 • 13:00' },
  { avatar: '/images/tutor.jpg', name: 'John Brown', course: 'PHP crash course (1/3)', date: '12.5.2025 • 13:00' },
  { avatar: '/images/tutor.jpg', name: 'John Brown', course: 'PHP crash course (1/3)', date: '12.5.2025 • 13:00' },
])
</script>

<template>
  <div class="min-h-screen bg-white font-sans">
    <Navbar />

    <SidebarWithBtn
      v-model:activeItem="activeItem"
      breadcrumb="Tutoring &gt; Schedule"
    />

    <main class="flex w-full h-full items-start justify-start">

      <!-- LEFT CONTENT -->
      <div class="flex-1 h-full px-6 py-4">

        <div class="flex justify-end mb-4">
          <button
            @click="showCalendarSetup = true"
            class="px-4 py-1.5 rounded-full border border-[#139aa2] text-[#139aa2] text-xs font-semibold hover:bg-teal-50 transition-colors"
          >
            Calendar Set up
          </button>
        </div>

        <!-- Calendar Card -->
        <div class="border border-slate-200 rounded-2xl p-6 mb-6">

          <div class="flex items-center justify-center gap-6 mb-6">
            <button @click="prevMonth" class="text-slate-400 hover:text-slate-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
              </svg>
            </button>
            <span class="text-sm font-semibold text-slate-700 w-32 text-center">{{ monthName }}</span>
            <button @click="nextMonth" class="text-slate-400 hover:text-slate-600 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </button>
          </div>

          <div class="grid grid-cols-7 mb-2">
            <div
              v-for="day in ['MON','TUE','WED','THU','FRI','SAT','SUN']"
              :key="day"
              class="text-center text-xs font-bold text-[#139aa2] py-2"
            >
              {{ day }}
            </div>
          </div>

          <div class="grid grid-cols-7 gap-y-1">
            <div
              v-for="(cell, i) in daysInMonth"
              :key="i"
              class="flex flex-col items-center py-2 rounded-xl"
              :class="{
                'bg-[#139aa2]': cell.current && cell.day === new Date().getDate() && currentDate.getMonth() === new Date().getMonth() && currentDate.getFullYear() === new Date().getFullYear() && !cell.isDayOff,
                'bg-red-50 border border-red-100': cell.current && cell.isDayOff,
              }"
            >
              <span
                class="text-sm font-semibold mb-1"
                :class="[
                  !cell.current ? 'text-slate-300' :
                  cell.isDayOff ? 'text-red-400' :
                  cell.day === new Date().getDate() && currentDate.getMonth() === new Date().getMonth() && currentDate.getFullYear() === new Date().getFullYear() ? 'text-white' :
                  'text-slate-700'
                ]"
              >
                {{ String(cell.day).padStart(2, '0') }}
              </span>
              <div class="flex gap-0.5">
                <span
                  v-for="(dot, di) in cell.dots"
                  :key="di"
                  class="w-1.5 h-1.5 rounded-full"
                  :class="{
                    'bg-green-500':  dot === 'green',
                    'bg-blue-400':   dot === 'blue',
                    'bg-red-500':    dot === 'red',
                    'bg-yellow-400': dot === 'yellow',
                  }"
                ></span>
              </div>
            </div>
          </div>

          <!-- Legend -->
          <div class="flex items-center gap-6 mt-5 flex-wrap">
            <div class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
              <span class="text-xs text-slate-500">Available date</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
              <span class="text-xs text-slate-500">Booked date</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
              <span class="text-xs text-slate-500">Slots Completed</span>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
              <span class="text-xs text-slate-500">Day Off</span>
            </div>
          </div>
        </div>

        <!-- Upcoming Classes -->
        <div class="border border-slate-200 rounded-2xl p-5">
          <div class="flex justify-end mb-4">
            <button class="px-4 py-1.5 rounded-full border border-[#139aa2] text-[#139aa2] text-xs font-semibold hover:bg-teal-50 transition-colors">
              Show All Upcoming Classes
            </button>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div
              v-for="(cls, i) in classes"
              :key="i"
              class="flex items-center gap-3 border border-slate-100 rounded-xl p-3"
            >
              <img :src="cls.avatar" :alt="cls.name" class="w-10 h-10 rounded-full object-cover flex-shrink-0"/>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-slate-800 truncate">{{ cls.name }}</p>
                <p class="text-[10px] text-slate-400 truncate">{{ cls.course }}</p>
              </div>
              <div class="flex flex-col items-end gap-1 flex-shrink-0">
                <p class="text-[10px] text-slate-500 whitespace-nowrap">{{ cls.date }}</p>
                <button class="px-2 py-0.5 rounded-full bg-[#139aa2] text-white text-[9px] font-semibold">
                  Class info
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>

      <Ads />
    </main>
  </div>

  <!-- Calendar Set Up Modal -->
  <div v-if="showCalendarSetup" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-[580px] max-h-[90vh] overflow-y-auto">

      <!-- Modal Header -->
      <div class="flex items-center justify-between px-6 pt-5 pb-3">
        <h2 class="text-sm font-bold text-slate-800">Calendar Set Up</h2>
        <button @click="showCalendarSetup = false" class="text-slate-400 hover:text-slate-600 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <!-- Tabs -->
      <div class="grid grid-cols-2">
        <button
          @click="setupTab = 'slots'"
          class="py-2.5 text-sm font-semibold transition-colors"
          :class="setupTab === 'slots' ? 'bg-[#139aa2] text-white' : 'text-slate-500 hover:bg-slate-50 border-b border-slate-200'"
        >
          Time Slots
        </button>
        <button
          @click="setupTab = 'daysoff'"
          class="py-2.5 text-sm font-semibold transition-colors"
          :class="setupTab === 'daysoff' ? 'bg-[#139aa2] text-white' : 'text-slate-500 hover:bg-slate-50 border-b border-slate-200'"
        >
          Days off
        </button>
      </div>

      <!-- Tab Content -->
      <div class="px-6 py-5">

        <!-- Time Slots Tab -->
        <div v-if="setupTab === 'slots'">
          <p class="text-xs text-slate-500 mb-1">
            Your time slots will be set up according to the time zone of
            <span class="font-semibold text-slate-700">Europe/Rome (UTC + XX:YY)</span>.
          </p>
          <p class="text-xs text-slate-500 mb-1">
            If you wish to change your time zone click <a href="#" class="text-[#139aa2] underline">here</a>.
          </p>
          <p class="text-xs text-red-500 mb-1">
            Be aware that in case you change your time zone, all your
            <span class="underline cursor-pointer">scheduled classes</span> will be deleted.
          </p>
          <p class="text-xs text-slate-500 mb-5">
            You can cancel time slot on specific days by selecting specific days from the calendar.
          </p>

          <div class="border border-slate-200 rounded-xl p-4 mb-5">
            <p class="text-xs text-slate-500 mb-3">Selects the starting time from the slots available</p>
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-2 border border-slate-200 rounded-lg px-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <select v-model="selectedSlot" class="text-xs text-slate-600 focus:outline-none bg-transparent pr-2">
                  <option value="">Select</option>
                  <option v-for="t in timeOptions" :key="t" :value="t">{{ t }}</option>
                </select>
              </div>
              <button
                @click="addSlot"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-lg transition-colors"
              >
                Add Slot
              </button>
            </div>
          </div>

          <p class="text-xs font-semibold text-slate-600 mb-3">Time Slots Available</p>

          <div v-if="props.timeSlots.length === 0" class="text-center text-xs text-slate-400 py-4">
            No slots added yet.
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div
              v-for="slot in props.timeSlots"
              :key="slot.id"
              class="flex items-center justify-between bg-green-50 border border-green-100 rounded-xl px-4 py-3"
            >
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                <span class="text-xs font-semibold text-slate-700">{{ slot.start_time }} — {{ slot.end_time }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-xs text-green-600 font-medium">Available</span>
                <button @click="deleteSlot(slot.id)" class="text-slate-300 hover:text-red-400 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Days Off Tab -->
        <div v-if="setupTab === 'daysoff'">
          <p class="text-xs text-slate-500 mb-1">Select your days off so that students will not be able to book slots.</p>
          <p class="text-xs text-slate-500 mb-1">It is <span class="font-semibold">optional</span> to have a day off.</p>
          <p class="text-xs text-red-500 mb-5">Be aware that in case you change your time zone, your days off will be deleted.</p>

          <div class="border border-slate-200 rounded-xl p-4 mb-5">
            <p class="text-xs text-slate-500 mb-3">Select Your Day</p>
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-2 border border-slate-200 rounded-lg px-3 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <select v-model="selectedDayOff" class="text-xs text-slate-600 focus:outline-none bg-transparent pr-2">
                  <option value="">Select</option>
                  <option v-for="d in dayOptions" :key="d" :value="d">{{ d }}</option>
                </select>
              </div>
              <button
                @click="addDayOff"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-lg transition-colors"
              >
                Add Day Off
              </button>
            </div>
          </div>

          <p class="text-xs font-semibold text-slate-600 mb-3">Days Off</p>

          <div v-if="props.daysOff.length === 0" class="text-center text-xs text-slate-400 py-4">
            No days off added yet.
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div
              v-for="dayOff in props.daysOff"
              :key="dayOff.id"
              class="flex items-center justify-between bg-red-50 border border-red-100 rounded-xl px-4 py-3"
            >
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                <span class="text-xs font-semibold text-slate-700">{{ dayOff.day_off_date }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-xs text-red-500 font-medium">Day Off</span>
                <button @click="deleteDayOff(dayOff.id)" class="text-slate-300 hover:text-red-400 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>