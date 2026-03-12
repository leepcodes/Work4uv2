<script setup lang="ts">
import Ads from '@/components/interfaces/ads.vue'
import Navbar from '@/components/interfaces/navbar.vue'
import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'
import MyList from '@/pages/landingpage/sharedUI/mylist.vue'

interface Schedule {
  id: number
  uuid: string
  total_class_count: number
  complete_class_count: number
  remaining_class_count: number
  available_date: string | null
  student: { id: number; name: string; photo: string | null }
  subject: { title: string }
}

defineProps<{ students: Schedule[] }>()
</script>

<template>
    
    <MyList :items="students" role="tutor" reloadKey="students" />
  
 
</template>