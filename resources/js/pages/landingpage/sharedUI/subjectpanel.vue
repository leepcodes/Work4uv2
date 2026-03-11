 <script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Ads from '@/components/interfaces/ads.vue'
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'
import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'

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

interface SubjectWithPackages {
  id: number
  title: string
  packages: Package[]
}

interface Person {
  id: number
  uuid: string
  name: string
  photo: string | null
}

const props = defineProps<{
  role: 'tutor' | 'student'
  person: Person        // tutor view: student info | student view: student's own info
  subjects: SubjectWithPackages[]
  backPath: string
  backLabel: string
  breadcrumbLabel: string
}>()

const sidebarOpen = ref(false)
const activeItem = ref('Home')
const expandedSubjects = ref<number[]>([])

const toggleSubject = (id: number) => {
  if (expandedSubjects.value.includes(id)) {
    expandedSubjects.value = expandedSubjects.value.filter(s => s !== id)
  } else {
    expandedSubjects.value.push(id)
  }
}

const getPackageLabel = (count: number) => {
  if (count === 2) return 'Package 1'
  if (count === 3) return 'Package 2'
  if (count === 5) return 'Package 3'
  return 'Custom Package'
}

const handlePackageClick = (pkg: Package, index: number) => {
  if (props.role === 'tutor') {
    router.visit(`/tutor/studentsubj/${pkg.uuid}/package?pkg_index=${index + 1}`)
  }
}
</script>

<template>
  <!-- Navbar -->
  <Navbar />

  <!-- Sidebar -->
  <SidebarWithBtn
    v-if="role === 'tutor'"
    :breadcrumb="`Tutoring &gt; My Students`"
  />
  <TutoringSidebar
    v-else
    v-model="sidebarOpen"
    v-model:activeItem="activeItem"
  />

  <!-- Top bar (student only, tutor uses SidebarWithBtn) -->
  <div v-if="role === 'student'" class="flex items-center bg-white gap-2 px-6 pt-5 pb-3">
    <button @click="sidebarOpen = true" class="text-[#139aa2] hover:text-teal-600 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
    <span class="text-sm font-bold text-[#139aa2]">Tutoring &gt; My Tutors & Subjects</span>
  </div>

  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 px-6 py-2 bg-white text-xs text-slate-400">
    <button @click="router.visit(backPath)" class="hover:text-teal-600 transition-colors">
      {{ backLabel }}
    </button>
    <span>-</span>
    <span class="text-slate-600 font-medium">{{ breadcrumbLabel }}</span>
  </div>

  <main class="flex w-full h-full items-start justify-start bg-white">

    <!-- LEFT — Person Info -->
    <div class="w-[25%] h-full px-6 py-4 border-r border-slate-100">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden shrink-0">
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
      <p class="text-sm font-bold text-slate-800">{{ person.name }}</p>
      <p class="text-xs text-slate-400">{{ role === 'tutor' ? 'STU' : 'ME' }}{{ person.uuid }}</p>
    </div>

    <!-- MIDDLE — Subjects & Packages -->
    <div class="w-[60%] h-full px-6 py-4 flex flex-col gap-3">

      <div v-if="subjects.length === 0" class="text-center text-xs text-slate-400 mt-10">
        No subjects found.
      </div>

      <div
        v-for="subject in subjects"
        :key="subject.id"
        class="border border-slate-200 rounded-xl overflow-hidden"
      >
        <button
          @click="toggleSubject(subject.id)"
          class="w-full flex items-center justify-between px-4 py-3 bg-white hover:bg-slate-50 transition-colors"
        >
          <div class="flex items-center gap-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4 text-slate-400 transition-transform"
              :class="expandedSubjects.includes(subject.id) ? 'rotate-0' : '-rotate-90'"
              fill="none" viewBox="0 0 24 24" stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
            <span class="text-sm font-semibold text-slate-800">{{ subject.title }}</span>
          </div>
          <span class="text-xs text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
            {{ subject.packages.length }} Package{{ subject.packages.length > 1 ? 's' : '' }}
          </span>
        </button>

        <div v-if="expandedSubjects.includes(subject.id)" class="px-4 py-3 bg-white border-t border-slate-100 flex flex-col gap-3">
          <div
            v-for="(pkg, index) in subject.packages"
            :key="pkg.id"
            @click="handlePackageClick(pkg, index)"
            class="border border-slate-200 rounded-xl p-4 transition-colors cursor-pointer hover:border-teal-300 hover:shadow-sm"
            >
            <div class="flex items-center gap-2 mb-3">
              <p class="text-sm font-semibold text-slate-700">{{ getPackageLabel(pkg.total_class_count) }}</p>
              <span
                v-if="pkg.status === 'completed'"
                class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-600 font-medium"
              >
                Completed
              </span>
            </div>

            <div class="flex items-center gap-6 mb-2">
              <p class="text-xs text-slate-500">{{ pkg.total_class_count }} Classes Purchased</p>
              <p class="text-xs text-slate-500">{{ pkg.complete_class_count }} Classes Completed</p>
              <p class="text-xs text-slate-500">
                Next Class: <span class="font-bold text-slate-800">{{ pkg.available_date ?? 'Not set' }}</span>
              </p>
            </div>

            <div class="flex items-center gap-6">
              <p class="text-xs text-slate-400">Purchase Date: {{ pkg.purchase_date ?? '—' }}</p>
              <p class="text-xs text-slate-400">Package Price: ${{ pkg.tutor_custom_price }}</p>
            </div>
          </div>
        </div>
      </div>

    </div>

    <Ads />
  </main>
</template>