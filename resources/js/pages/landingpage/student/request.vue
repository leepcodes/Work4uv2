<script lang="ts">
import { defineComponent } from 'vue'
import type { PropType } from 'vue'
import Navbar from '@/components/interfaces/navbar.vue'

import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'
import Ads from '@/components/interfaces/ads.vue'
import TutoringRequestPanel from '@/pages/landingpage/sharedUI/request.vue'

interface TutoringRequest {
  id: number
  subject: { subject_title: string }
  custom_class_count: number
  created_at: string
  message: string
  status: string
  student?: { username: string; photo?: string | null }
  tutor?: { username: string; photo?: string | null }
  offer_message?: string | null
  tutor_custom_price?: number | null
}

export default defineComponent({
  name: 'StudentRequestPage',
  components: { Navbar, SidebarWithBtn, Ads, TutoringRequestPanel },

  props: {
    requests: {
      type: Array as PropType<TutoringRequest[]>,
      default: () => [],
    },
      role: {
      type: String as PropType<'tutor' | 'student'>,
      required: true,
    },
  },

  data() {
    return {
      sidebarOpen: false,
      activeItem: 'Requests',
    }
  },
})
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans">
    <Navbar />
    <SidebarWithBtn breadcrumb="Tutoring &gt; Requests" />
    <main class="flex w-full h-full items-start justify-start">
      <TutoringRequestPanel :requests="requests" :role="role" />
      <Ads />
    </main>
  </div>
</template>