<script setup lang="ts">
import { ref } from 'vue'
import Ads from '@/components/interfaces/ads.vue'
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'
import MyList from '@/pages/landingpage/sharedUI/mylist.vue'

interface Schedule {
  id: number
  total_class_count: number
  complete_class_count: number
  remaining_class_count: number
  available_date: string | null
  tutor: { id: number; name: string; photo: string | null }
  subject: { title: string }
}

defineProps<{ classes: Schedule[] }>()

const sidebarOpen = ref(false)
const activeItem = ref('Home')
</script>

<template>
  <div class="min-h-screen bg-white font-sans">
    <Navbar />
    <TutoringSidebar v-model="sidebarOpen" v-model:activeItem="activeItem" />

    <div class="flex items-center bg-white gap-2 px-6 pt-5 pb-3">
      <button @click="sidebarOpen = true" class="text-[#139aa2] hover:text-teal-600 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <span class="text-sm font-bold text-[#139aa2]">Tutoring &gt; My Tutors</span>
    </div>

    <main class="flex w-full h-full items-start justify-start bg-white">
      <div class="flex-1 h-full px-6 py-4">
        <MyList :items="classes" role="student" reloadKey="classes" />
      </div>
      <Ads />
    </main>
  </div>
</template>