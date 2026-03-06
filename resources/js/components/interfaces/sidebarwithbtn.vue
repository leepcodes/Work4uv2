<script lang="ts">
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'
// File: resources/js/components/interfaces/sidebarwithbtn.vue

export default {
  name: 'SidebarWithBtn',
  components: { TutoringSidebar },

  props: {
    activeItem: {
      type: String,
      default: '',
    },
    breadcrumb: {
      type: String,
      default: '',
    },
  },

  emits: ['update:activeItem'],

  data() {
    return {
      sidebarOpen: false,
    }
  },
}
</script>

<template>
  <TutoringSidebar
    v-model="sidebarOpen"
    :activeItem="activeItem"
    @update:activeItem="$emit('update:activeItem', $event)"
  />

  <div class="flex items-center gap-2 px-6 pt-5 pb-3 bg-white" >
    <button @click="sidebarOpen = true" class="text-[#139aa2] hover:text-teal-600 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
    <span class="text-sm font-bold text-[#139aa2]">{{ breadcrumb }}</span>
  </div>
</template>