<script lang="ts">
export default {
  name: 'TutoringSidebar',

  props: {
    modelValue: {
      type: Boolean,
      default: false
    },
    activeItem: {
      type: String,
      default: 'Home'
    }
  },

  emits: ['update:modelValue', 'update:activeItem'],

  data() {
    return {
      navItems: [
        {
          name: 'Home',
          badge: null,
          href: '/tutor/landing',
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`
        },
        {
          name: 'Requests',
          badge: null,
          href: '/tutor/request',
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
        },
        {
          name: 'Calendar',
          badge: null,
          
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`
        },
        {
          name: 'My Students',
          badge: '100',
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`
        },
        {
          name: 'My Subjects',
          badge: null,
          href: '/tutor/my-subject',
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
        }
      ]
    }
  },

  methods: {
    close() {
      this.$emit('update:modelValue', false)
    },
    setActive(name:string) {
      this.$emit('update:activeItem', name)
    }
  }
}
</script>

<template>
  <!-- ========== SIDEBAR OVERLAY ========== -->
  <transition
    enter-active-class="transition-opacity duration-200 ease-out"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition-opacity duration-200 ease-in"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
  >
    <div
      v-if="modelValue"
      class="fixed inset-0 bg-black/40 backdrop-blur-[2px] z-40"
      @click="close"
    ></div>
  </transition>

  <!-- ========== SIDEBAR PANEL ========== -->
  <transition
    enter-active-class="transition-transform duration-300 ease-out"
    enter-from-class="-translate-x-full"
    enter-to-class="translate-x-0"
    leave-active-class="transition-transform duration-250 ease-in"
    leave-from-class="translate-x-0"
    leave-to-class="-translate-x-full"
  >
    <aside
      v-show="modelValue"
      class="fixed top-0 left-0 h-full w-64 bg-white shadow-2xl z-50 flex flex-col overflow-y-auto"
    >
      <!-- Header -->
      <div class="px-5 pt-5 pb-4 border-b border-slate-100 flex flex-col gap-3">
        <img src="/images/work4ututor.png" alt="Work4uNow" class="h-7 object-contain self-start" />
        <button
          @click="close"
          class="flex items-center gap-2 text-slate-700 font-semibold text-sm hover:text-teal-600 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Tutoring Module
        </button>
      </div>

      <!-- Module Badge -->
      <div class="px-4 pt-4 pb-2">
        <span class="block w-full text-center bg-teal-500 text-white font-semibold text-sm py-2.5 rounded-lg shadow-sm tracking-wide">
          Module Active
        </span>
      </div>

      <!-- Nav Items -->
      <nav class="flex-1 px-3 py-2 flex flex-col gap-1">
        <a
          v-for="item in navItems"
  :key="item.name"
  :href="item.href || '#'"
  class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200"
  :class="activeItem === item.name
    ? 'bg-teal-500 text-white shadow-sm'
    : 'text-slate-500 hover:bg-teal-50 hover:text-teal-700'"
  @click="item.href ? setActive(item.name) : ($event.preventDefault(), setActive(item.name))"
>
          <span class="flex-shrink-0" v-html="item.icon"></span>
          <span class="flex-1">{{ item.name }}</span>
          <span
            v-if="item.badge"
            class="text-xs font-bold px-2 py-0.5 rounded-full"
            :class="activeItem === item.name ? 'bg-white/25 text-white' : 'bg-teal-100 text-teal-700'"
          >{{ item.badge }}</span>
        </a>
      </nav>
    </aside>
  </transition>
</template>