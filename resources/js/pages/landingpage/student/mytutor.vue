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
  
        <MyList :items="classes" role="student" reloadKey="classes" />
        
</template>