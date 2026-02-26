<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import Navbar from '@/components/interfaces/navbar.vue'
import Ads from '@/components/interfaces/ads.vue'

withDefaults(defineProps<{
  tutor: {
    id: number
    firstname: string
    lastname: string
    avatar: string
    description: string
  }
  subjects: Array<{
    id: number
    uuid: string
    title: string
    status: string | null
    image: string | null
    students: number
    earnings: string
    packages: Array<{
      label: string
      price: string
      sub: string
      color: string
    }>
  }>
}>(), {
  subjects: () => []  
})

const activeTab = ref('Tutoring')
const tabs = ['About Me', 'Tutoring', 'Webinars', 'E-learnings', 'Videos']
</script>

<template>
  <Head :title="`${tutor.firstname} ${tutor.lastname} - Profile`" />

  <div class="min-h-screen bg-white font-sans">

    <Navbar />

    <!-- Breadcrumb -->
    <div class="px-6 pt-5 pb-2">
      <Link href="/student" class="text-[#139aa2] hover:text-teal-600 text-xs font-semibold transition-colors">
        &lt; Return to Previous Page
      </Link>
    </div>

    <!-- ===== MAIN LAYOUT ===== -->
    <div class="flex px-6 pb-10 gap-4 w-full">

      <!-- ===== LEFT CONTENT ===== -->
      <div class="flex-1 min-w-0">

        <!-- Profile Card -->
    <div class="border border-slate-200 rounded-2xl p-6 mb-4 relative">

  <!-- Calendar icon top right -->
    <button class="absolute top-5 right-5 text-slate-400 hover:text-teal-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </button>

  <!-- Avatar + Name + Bio -->
  <div class="flex items-center gap-5 mb-5">
    <img
      :src="tutor.avatar || '/images/tutor.jpg'"
      :alt="tutor.firstname"
      class="w-16 h-16 rounded-full object-cover flex-shrink-0 border border-slate-200"
    />
    <div>
      <h2 class="text-base font-bold text-slate-800 mb-1">
        {{ tutor.firstname }} {{ tutor.lastname }}
      </h2>
      <p class="text-xs text-slate-500 leading-relaxed max-w-lg">
        {{ tutor.description }}
      </p>
    </div>
  </div>

  <!-- Divider -->
  <hr class="border-slate-100 mb-4" />

  <!-- Stats -->
  <div class="flex items-center gap-10 mb-5">
    <div class="flex items-center gap-2">
      <span class="text-sm font-semibold text-slate-700">Learners</span>
      <span class="text-sm font-bold text-slate-900">1.5K</span>
    </div>
    <div class="flex items-center gap-2">
      <span class="text-sm font-semibold text-slate-700">Students</span>
      <span class="text-sm font-bold text-slate-900">300</span>
    </div>
  </div>

  <!-- Follow Button -->
  <button class="px-8 h-10 w-45 py-2 rounded-lg bg-[#139aa2] hover:bg-teal-600 text-white text-xs font-bold tracking-widest transition-colors">
    FOLLOW
  </button>

</div>
        

        <!-- Tabs -->
        <div class="flex  w-full items-center gap-4 border border-slate-200 rounded-full px-3 py-2 mb-5 w-fit">
        <button
            v-for="tab in tabs"
            :key="tab"
            @click="activeTab = tab"
            class="px-5 py-1.5 h-12 w-40 text-xs font-semibold rounded-full transition-colors"
            :class="activeTab === tab
            ? 'bg-[#139aa2] text-white'
            : 'text-slate-500 hover:text-slate-700'"
        >
            {{ tab }}
        </button>
        </div>

        
        <!-- Tab content -->
         
       <div v-if="activeTab === 'Tutoring'">
            <div v-if="subjects.length === 0" class="text-xs text-slate-400 text-center py-10">
                No subjects yet.
            </div>
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <Link
                v-for="subject in subjects"
                :key="subject.uuid"
                :href="`/subject/${subject.uuid}`"
                class="bg-white rounded-xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-md transition-shadow"
                >
                <div class="relative">
                    <img :src="subject.image ?? '/images/tutor.jpg'" alt="Subject" class="w-full h-36 object-cover"/>
                    <div class="absolute inset-0 bg-black/30 flex items-start px-3 pt-2">
                    <span class="text-white text-xs font-bold tracking-widest uppercase">{{ subject.title }}</span>
                    </div>
                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
                    <span v-for="n in 4" :key="n" class="w-1.5 h-1.5 rounded-full" :class="n === 1 ? 'bg-white' : 'bg-white/40'"></span>
                    </div>
                </div>

                <div class="p-3">
                    <div class="flex items-center gap-2 mb-2">
                    <h3 class="text-sm font-bold text-slate-800">{{ subject.title }}</h3>
                    <span
                        v-if="subject.status"
                        class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                        :class="{
                        'bg-red-100 text-red-500': subject.status === 'Not Approved',
                        'bg-yellow-100 text-yellow-600': subject.status === 'Paused',
                        'bg-blue-100 text-blue-500': subject.status === 'Waiting for Approval'
                        }"
                    >{{ subject.status }}</span>
                    </div>

                    <div class="flex gap-2 mb-3">
                    <div
                        v-for="pkg in subject.packages"
                        :key="pkg.label"
                        class="flex-1 rounded-md px-1.5 py-1.5 text-center"
                        :class="pkg.color"
                    >
                        <p class="text-[10px] font-bold text-white leading-tight">{{ pkg.label }}</p>
                        <p class="text-[10px] text-white/90 leading-tight">{{ pkg.price }}</p>
                        <p class="text-[9px] text-white/70 leading-tight">{{ pkg.sub }}</p>
                    </div>
                    </div>

                    <p class="text-[10px] text-slate-500">{{ subject.students }} Students have bought this course</p>
                    <p class="text-[10px] text-slate-500">{{ subject.earnings }} Earnings</p>
                </div>
                </Link>
            </div>
            </div>

            <div v-else class="text-xs text-slate-400 text-center py-10">
            No content yet.
            </div>
      </div>

      <!-- ===== ADS SIDEBAR ===== -->
      <Ads/>

    </div>
  </div>
</template>