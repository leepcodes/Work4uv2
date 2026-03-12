<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Navbar from '@/components/interfaces/navbar.vue'
import Ads from '@/components/interfaces/ads.vue'
import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'

const props = defineProps<{
  role?: 'tutor' | 'student'
  person?: any
  subject?: any
  package?: any
  classItem?: any
}>()

const backPath = computed(() =>
  props.role === 'tutor'
    ? `/tutor/studentsubj/${props.person.uuid}`
    : `/student/tutorsandsubject/${props.package.uuid}`
)

const packageIndex = new URLSearchParams(window.location.search).get('pkg_index') ?? '1'

// ── Form fields ─────────────────────────────────────────────
const videoLink     = ref<string>(props.classItem?.video_link ?? '')
const notesFiles    = ref<File[]>([])
const uploadedFiles = ref<File[]>([])
const saving        = ref(false)

function handleNotesUpload(e: Event) {
  const input = e.target as HTMLInputElement
  if (input.files) notesFiles.value = [...notesFiles.value, ...Array.from(input.files)]
}

function removeNotesFile(index: number) {
  notesFiles.value.splice(index, 1)
}

function handleFileUpload(e: Event) {
  const input = e.target as HTMLInputElement
  if (input.files) uploadedFiles.value = [...uploadedFiles.value, ...Array.from(input.files)]
}

function removeFile(index: number) {
  uploadedFiles.value.splice(index, 1)
}

function save() {
  if (!props.classItem?.uuid) {
    alert('Missing class UUID — cannot save.')
    return
  }

  saving.value = true

  const form = new FormData()
  if (scheduledDate.value)  form.append('schedule', scheduledDate.value)
  if (scheduledTime.value)  form.append('scheduled_time', scheduledTime.value)
  if (videoLink.value)      form.append('video_link', videoLink.value)
  notesFiles.value.forEach(f    => form.append('notes[]', f))
  uploadedFiles.value.forEach(f => form.append('documents[]', f))

  router.post(`/classes/${props.classItem.uuid}/update`, form, {
    forceFormData: true,
    onSuccess: () => console.log('Saved!'),
    onError:   (e) => console.error('Save error:', e),
    onFinish:  () => saving.value = false,
  })
}
// ────────────────────────────────────────────────────────────

// ── Calendar popup ──────────────────────────────────────────
const showCalendar     = ref(false)
const calLoading       = ref(false)
const calStep          = ref<'calendar' | 'timeslot'>('calendar')
const calDate          = ref(new Date())
const scheduledDate    = ref<string | null>(props.classItem?.schedule ?? null)
const scheduledTime    = ref<string | null>(null)
const timeSlots        = ref<any[]>([])
const daysOff          = ref<any[]>([])
const selectedDateCell = ref<any>(null)

const calMonthName = computed(() =>
  calDate.value.toLocaleString('default', { month: 'long', year: 'numeric' })
)

const selectedDateLabel = computed(() => {
  if (!scheduledDate.value) return ''
  const d = new Date(scheduledDate.value + 'T00:00:00')
  return d.toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' })
})

function formatTime(t: string) {
  const [h, m] = t.split(':').map(Number)
  const ampm = h >= 12 ? 'PM' : 'AM'
  const hour = h % 12 === 0 ? 12 : h % 12
  return `${hour}:${String(m).padStart(2, '0')} ${ampm}`
}

const calDays = computed(() => {
  const year     = calDate.value.getFullYear()
  const month    = calDate.value.getMonth()
  const firstDay = new Date(year, month, 1).getDay()
  const total    = new Date(year, month + 1, 0).getDate()
  const prevDays = new Date(year, month, 0).getDate()
  const offset   = firstDay === 0 ? 6 : firstDay - 1
  const days     = []

  for (let i = offset - 1; i >= 0; i--)
    days.push({ day: prevDays - i, current: false, isDayOff: false, dots: [] as string[] })

  for (let d = 1; d <= total; d++) {
    const dayName  = new Date(year, month, d).toLocaleDateString('en-US', { weekday: 'long' })
    const isDayOff = daysOff.value.some((o: any) => o.day_off_date === dayName)
    const dots: string[] = []
    if (isDayOff) dots.push('red')
    else if (timeSlots.value.length > 0) dots.push('blue')
    days.push({ day: d, current: true, isDayOff, dots })
  }

  const remaining = 42 - days.length
  for (let i = 1; i <= remaining; i++)
    days.push({ day: i, current: false, isDayOff: false, dots: [] as string[] })

  return days
})

function toDateStr(day: number) {
  const y = calDate.value.getFullYear()
  const m = calDate.value.getMonth() + 1
  return `${y}-${String(m).padStart(2, '0')}-${String(day).padStart(2, '0')}`
}

function isSelectable(cell: any) {
  return cell.current && !cell.isDayOff && cell.dots.length > 0
}

function selectDate(cell: any) {
  if (!isSelectable(cell)) return
  scheduledDate.value    = toDateStr(cell.day)
  selectedDateCell.value = cell
  scheduledTime.value    = null
  calStep.value          = 'timeslot'
}

function calPrev() {
  calDate.value = new Date(calDate.value.getFullYear(), calDate.value.getMonth() - 1, 1)
}

function calNext() {
  calDate.value = new Date(calDate.value.getFullYear(), calDate.value.getMonth() + 1, 1)
}

async function openCalendar() {
  showCalendar.value = true
  calLoading.value   = true
  calStep.value      = 'calendar'

  const url = props.role === 'tutor'
    ? '/tutor/calendar-data'
    : `/student/calendar-data/${props.package.uuid}`

  const res  = await fetch(url)
  const data = await res.json()
  timeSlots.value  = data.timeSlots
  daysOff.value    = data.daysOff
  calLoading.value = false
}

function confirmBooking() {
  showCalendar.value = false
}

function backToCalendar() {
  calStep.value       = 'calendar'
  scheduledTime.value = null
}
// ────────────────────────────────────────────────────────────
</script>

<template>
  <Navbar />
  <SidebarWithBtn :breadcrumb="role === 'tutor' ? 'Tutoring &gt; My Students' : 'Tutoring &gt; My Tutors'"/>

  <div class="flex items-center gap-2 px-6 py-2 bg-white text-xs text-slate-400">
    <span>{{ role === 'tutor' ? 'Students List' : 'Tutors List' }}</span>
    <span>-</span>
    <span>{{ role === 'tutor' ? 'Student & Subjects' : 'Tutor & Subjects' }}</span>
    <span>-</span>
    <span>Package Classes</span>
    <span>-</span>
    <span class="text-slate-600 font-medium">Class Details</span>
  </div>

  <main class="flex w-full h-full items-start justify-start bg-white">

    <div class="w-[25%] h-[100vh] px-6 py-4">
      <div class="flex items-center gap-3 mb-1">
        <div class="w-11 h-11 rounded-full bg-gray-200 overflow-hidden shrink-0">
          <img :src="person.photo ?? '/images/tutor.jpg'" :alt="person.name" class="w-full h-full object-cover"/>
        </div>
        <button class="text-slate-400 hover:text-teal-500 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </button>
      </div>
      <p class="text-sm font-bold text-slate-800 mt-2">{{ person.name }}</p>
      <p class="text-xs text-slate-400 mb-6">{{ role === 'tutor' ? 'STU' : 'TUT' }}-{{ person.id }}</p>
      <p class="text-sm font-bold text-slate-800 mb-3">{{ subject.title }} - Package {{ packageIndex }}</p>
      <p class="text-xs text-slate-500">Purchase Date <span class="font-semibold text-slate-700">{{ package.purchase_date ?? '—' }}</span></p>
      <p class="text-xs text-slate-500 mt-1">Package Price <span class="font-semibold text-slate-700">{{ package.tutor_custom_price }} US$</span></p>
      <br>
      <p class="text-sm font-bold text-slate-800 mb-3">Package {{ packageIndex }}</p>
    </div>

    <div class="w-[70%] h-[100vh] px-6 py-4">
      <div class="flex gap-6">
        <div class="flex flex-col gap-4 flex-1">

          <!-- Schedule -->
          <div class="flex items-center gap-2 cursor-pointer group" @click="openCalendar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#139aa2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-semibold text-[#139aa2] group-hover:underline">Schedule</span>
          </div>

          <p class="text-xs" :class="scheduledDate ? 'text-slate-600 font-medium' : 'text-red-400'">
            {{ scheduledDate
              ? `${scheduledDate}${scheduledTime ? ' at ' + formatTime(scheduledTime) : ''}`
              : 'No Class Scheduled' }}
          </p>

          <!-- Video link -->
          <div class="flex flex-col gap-1">
            <label class="text-sm text-gray-700">Video link</label>
            <input
              v-model="videoLink"
              type="text"
              placeholder="https://..."
              class="w-full border border-gray-200 text-black bg-gray-50 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#139aa2]"
            />
          </div>

          <!-- Notes -->
          <div class="flex flex-col gap-2">
            <label class="text-sm text-gray-700">Notes</label>
            <label class="cursor-pointer w-fit" title="Upload notes">
              <input type="file" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="hidden" @change="handleNotesUpload"/>
              <div class="text-gray-400 hover:text-[#139aa2] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
            </label>

            <div v-if="notesFiles.length > 0" class="flex flex-col gap-1">
              <div v-for="(file, idx) in notesFiles" :key="idx"
                class="flex items-center justify-between bg-white border border-gray-100 rounded-lg px-3 py-2 shadow-sm">
                <div class="flex items-center gap-3">
                  <div class="bg-red-100 rounded p-1.5">
                    <span class="text-red-500 text-xs font-bold">{{ file.name.split('.').pop()?.toUpperCase() }}</span>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800 truncate max-w-[180px]">{{ file.name }}</p>
                    <p class="text-xs text-gray-400">{{ (file.size / 1024).toFixed(0) }} kb</p>
                  </div>
                </div>
                <button class="text-red-500 hover:text-red-600" @click="removeNotesFile(idx)">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Documents -->
          <div class="flex flex-col gap-2">
            <label class="text-sm text-gray-700">Documents</label>
            <label class="border-2 border-dashed border-[#139aa2] rounded-lg p-6 flex flex-col items-center gap-1 text-center cursor-pointer hover:bg-teal-50 transition-colors">
              <input type="file" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="hidden" @change="handleFileUpload"/>
              <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#139aa2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <p class="text-sm font-semibold text-[#139aa2]">Upload Document</p>
              <p class="text-xs text-gray-400">Drag and drop or click to browse</p>
              <p class="text-xs text-gray-400">Supports: JPG, PNG, PDF, DOC, DOCX</p>
            </label>

            <div v-if="uploadedFiles.length > 0" class="flex flex-col gap-1">
              <div v-for="(file, idx) in uploadedFiles" :key="idx"
                class="flex items-center justify-between bg-white border border-gray-100 rounded-lg px-3 py-2 shadow-sm">
                <div class="flex items-center gap-3">
                  <div class="bg-red-100 rounded p-1.5">
                    <span class="text-red-500 text-xs font-bold">{{ file.name.split('.').pop()?.toUpperCase() }}</span>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800 truncate max-w-[180px]">{{ file.name }}</p>
                    <p class="text-xs text-gray-400">{{ (file.size / 1024).toFixed(0) }} kb</p>
                  </div>
                </div>
                <button class="text-red-500 hover:text-red-600" @click="removeFile(idx)">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Action buttons -->
          <div class="flex gap-3">
            <button type="button"
              class="flex-1 border border-gray-300 text-gray-600 text-sm font-semibold py-2 rounded-lg hover:bg-gray-50 transition-colors"
              @click="router.visit(backPath)">
              Cancel
            </button>
            <button type="button"
              class="flex-1 bg-[#139aa2] text-white text-sm font-semibold py-2 rounded-lg hover:bg-teal-600 transition-colors disabled:opacity-60"
              :disabled="saving"
              @click="save">
              {{ saving ? 'Saving...' : 'SAVE' }}
            </button>
          </div>

        </div>

        <div class="flex flex-col items-end gap-2 pt-0.5 shrink-0">
          <div class="flex items-center gap-2">
            <button class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
            <button class="bg-[#e8f4f5] text-[#139aa2] text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap">
              + Add Consultation Note
            </button>
          </div>
        </div>
      </div>
    </div>

    <Ads/>
  </main>

  <!-- ── Calendar / Time Slot Popup ── -->
  <div v-if="showCalendar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30">
    <div class="bg-white rounded-2xl shadow-xl w-[480px] p-5 relative">

      <!-- Close -->
      <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600" @click="showCalendar = false">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </button>

      <!-- ── STEP 1: CALENDAR ── -->
      <template v-if="calStep === 'calendar'">
        <div class="flex items-center justify-center gap-6 mb-4">
          <button @click="calPrev" class="text-slate-400 hover:text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <span class="text-sm font-semibold text-slate-700 w-36 text-center">{{ calMonthName }}</span>
          <button @click="calNext" class="text-slate-400 hover:text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
          </button>
        </div>

        <div class="grid grid-cols-7 mb-2">
          <div v-for="d in ['MON','TUE','WED','THU','FRI','SAT','SUN']" :key="d"
            class="text-center text-[10px] font-bold text-[#139aa2] py-1">{{ d }}</div>
        </div>

        <div v-if="calLoading" class="flex flex-col items-center justify-center py-10 gap-3">
          <svg class="w-8 h-8 text-[#139aa2] animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
          </svg>
          <p class="text-xs text-slate-400">Loading availability...</p>
        </div>

        <div v-else class="grid grid-cols-7 gap-y-1">
          <div
            v-for="(cell, i) in calDays" :key="i"
            class="flex flex-col items-center py-2 rounded-xl transition-colors"
            :class="{
              'cursor-pointer hover:bg-teal-50': isSelectable(cell),
              'bg-[#139aa2]': cell.current && scheduledDate === toDateStr(cell.day),
              'bg-red-50 border border-red-100': cell.current && cell.isDayOff,
            }"
            @click="selectDate(cell)"
          >
            <span
              class="text-sm font-semibold mb-1"
              :class="[
                !cell.current ? 'text-slate-300' :
                scheduledDate === toDateStr(cell.day) ? 'text-white' :
                cell.isDayOff ? 'text-red-400' :
                'text-slate-700'
              ]"
            >
              {{ String(cell.day).padStart(2, '0') }}
            </span>
            <div class="flex gap-0.5">
              <span v-for="(dot, di) in cell.dots" :key="di"
                class="w-1.5 h-1.5 rounded-full"
                :class="{ 'bg-blue-400': dot === 'blue', 'bg-red-500': dot === 'red' }"
              ></span>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-6 mt-4 flex-wrap">
          <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
            <span class="text-[10px] text-slate-500">Available</span>
          </div>
          <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
            <span class="text-[10px] text-slate-500">Day Off</span>
          </div>
        </div>
      </template>

      <!-- ── STEP 2: TIME SLOT PICKER ── -->
      <template v-else-if="calStep === 'timeslot'">
        <div class="flex items-center gap-3 mb-1 pr-6">
          <button @click="backToCalendar" class="text-slate-400 hover:text-[#139aa2] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
          </button>
          <div>
            <p class="text-sm font-bold text-slate-800">Class Schedule for {{ selectedDateLabel }}</p>
            <p class="text-xs text-slate-400">Select a suitable slot from your calendar</p>
          </div>
        </div>

        <div class="mt-4 flex gap-4">
          <div class="flex-1">
            <p class="text-xs font-bold text-slate-600 mb-3">
              {{ role === 'tutor' ? 'Your Schedule' : "Tutor's Schedule" }}
            </p>
            <div class="grid grid-cols-3 gap-2">
              <button
                v-for="slot in timeSlots" :key="slot.id"
                class="text-xs font-medium px-2 py-2 rounded-lg border transition-colors flex items-center gap-1 justify-center"
                :class="scheduledTime === slot.start_time
                  ? 'bg-[#139aa2] text-white border-[#139aa2]'
                  : 'border-gray-200 text-slate-600 hover:border-[#139aa2] hover:text-[#139aa2]'"
                @click="scheduledTime = slot.start_time"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ formatTime(slot.start_time) }}
              </button>
            </div>
            <div v-if="timeSlots.length === 0" class="text-xs text-slate-400 text-center py-6">
              No time slots available
            </div>
          </div>

          <div class="w-px bg-gray-100"></div>

          <div class="flex-1">
            <p class="text-xs font-bold text-slate-600 mb-3">Student's Schedule</p>
            <p class="text-xs text-slate-400 text-center py-6">No classes booked</p>
          </div>
        </div>

        <div class="mt-5 pt-3 border-t border-gray-100 flex items-center justify-between">
          <p class="text-xs text-gray-400">
            {{ scheduledTime
              ? `${scheduledDate} at ${formatTime(scheduledTime)}`
              : 'Book a Class Select a Time Slot' }}
          </p>
          <button
            :disabled="!scheduledTime"
            class="text-white text-xs px-5 py-1.5 rounded-full transition-colors"
            :class="scheduledTime ? 'bg-[#139aa2] hover:bg-teal-600' : 'bg-gray-200 cursor-not-allowed'"
            @click="confirmBooking"
          >
            Book Class
          </button>
        </div>
      </template>

    </div>
  </div>
</template>