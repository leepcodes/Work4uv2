<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const role = (page.props.auth as any)?.role;

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  activeItem: {
    type: String,
    default: 'Home'
  }
});

const emit = defineEmits(['update:modelValue', 'update:activeItem']);

const close = () => emit('update:modelValue', false);
const setActive = (name: string) => emit('update:activeItem', name);
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

          <!-- Home -->
              <Link
                :href="role === 'tutor' ? '/tutor/landing' : '/student'"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeItem === 'Home' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:bg-teal-50 hover:text-teal-700'"
                @click="setActive('Home')"
              >
                Home
              </Link>

         <!-- Tutoring -->
              <Link
                :href="role === 'tutor' ? '/tutor/request' : '/student/request'"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeItem === 'Tutoring' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:bg-teal-50 hover:text-teal-700'"
                @click="setActive('Tutoring')"
              >
                Request
              </Link>

              <!-- Calendar (Tutor / Student) -->
               <Link
                :href="role === 'tutor' ? '/tutor/calendar' : '/student/calendar'"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeItem === 'Calendar' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:bg-teal-50 hover:text-teal-700'"
                @click="setActive('Calendar')"
              >
                Calendar WALA PA TO 
              </Link>

              <!-- Questions (Student only) -->

              <Link
                  :href="role === 'tutor' ? '/tutor/mystudents' : '/student/mytutor'"
                  class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200"
                  :class="activeItem === (role === 'tutor' ? 'My Students' : 'My Tutors') ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:bg-teal-50 hover:text-teal-700'"
                  @click="setActive(role === 'tutor' ? 'My Students' : 'My Tutors')"
              >
                  {{ role === 'tutor' ? 'My Students' : 'My Tutors' }}
              </Link>

              

               <!-- My Subject (Student / Tutor ) -->
               <Link
                :href="role === 'tutor' ? '/tutor/my-subject' : '/student/myclasses'"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200"
                :class="activeItem === 'My Subjects' ? 'bg-teal-500 text-white shadow-sm' : 'text-slate-500 hover:bg-teal-50 hover:text-teal-700'"
                @click="setActive('My Subjects')"
              >
                My Subjects
              </Link>

            </nav>
    </aside>
  </transition>
</template>