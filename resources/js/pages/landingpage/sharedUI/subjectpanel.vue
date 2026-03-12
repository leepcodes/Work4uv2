<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Ads from '@/components/interfaces/ads.vue'
import Navbar from '@/components/interfaces/navbar.vue'
import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'

interface Package {
  id: number
  uuid: string
  package_number: number
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
  person: Person
  subjects: SubjectWithPackages[]
  backPath: string
  backLabel: string
  breadcrumbLabel: string
}>()

const activeItem = ref('Home')
const expandedSubjects = ref<number[]>([])

const toggleSubject = (id: number) => {
  if (expandedSubjects.value.includes(id)) {
    expandedSubjects.value = expandedSubjects.value.filter(s => s !== id)
  } else {
    expandedSubjects.value.push(id)
  }
}

const handlePackageClick = (pkg: Package) => {
  if (props.role === 'tutor') {
    router.visit(`/tutor/studentsubj/${pkg.uuid}/package`)
  } else {
    router.visit(`/student/studentsubj/${pkg.uuid}/package`)
  }
}
</script>

<template>
  <Navbar />
  <SidebarWithBtn
    v-model:activeItem="activeItem"
    :breadcrumb="role === 'tutor' ? 'Tutoring &gt; My Students' : 'Tutoring &gt; My Tutors'"
  />

  
  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 px-6 py-2 bg-white text-xs text-slate-400">
    <span>{{ role === 'tutor' ? 'Students List' : 'Tutors List' }}</span>
    <span>-</span>
    <span>{{ role === 'tutor' ? 'Student & Subjects' : 'Tutor & Subjects' }}</span>

  </div>

  <main class="flex w-full h-full items-start justify-start bg-white">


    <div class="w-[25%] h-[100vh] px-6 py-4 " >
          
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

  
    <div class="w-[70%] h-[100vh] px-6 py-4">

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
            v-for="pkg in subject.packages"
            :key="pkg.id"
            @click="handlePackageClick(pkg)"
            class="border border-slate-200 rounded-xl p-4 transition-colors cursor-pointer hover:border-teal-300 hover:shadow-sm"
          >
            <div class="flex items-center gap-2 mb-3">
              <p class="text-sm font-semibold text-slate-700">Package {{ pkg.package_number }}</p>
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

   
    <Ads/>
  </main>

</template>