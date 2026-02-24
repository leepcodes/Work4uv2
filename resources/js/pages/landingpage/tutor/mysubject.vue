<script >
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'
import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
  name: 'TutoringMySubjects',
 components: { Navbar, TutoringSidebar, InertiaLink },

  data() {
    return {
      sidebarOpen: false,
      activeItem: 'My Subjects',
      openMenu: null,

      subjects: [
        {
          id: 1,
          status: null,
          students: 4,
          earnings: '1.0K USD',
          packages: [
            { label: '2 Class', price: 'USD 20', sub: '10/class', color: 'bg-teal-400' },
            { label: '3 Class', price: 'USD 20', sub: '10/class', color: 'bg-purple-400' },
            { label: '5 Class', price: 'USD 20', sub: '10/class', color: 'bg-violet-500' }
          ]
        },
        {
          id: 2,
          status: null,
          students: 4,
          earnings: '1.0K USD',
          packages: [
            { label: '2 Class', price: 'USD 20', sub: '10/class', color: 'bg-teal-400' },
            { label: '3 Class', price: 'USD 27', sub: '9/class',  color: 'bg-purple-400' },
            { label: '5 Class', price: 'USD 29', sub: '8/class',  color: 'bg-violet-500' }
          ]
        },
        {
          id: 3,
          status: null,
          students: 4,
          earnings: '3.8K USD',
          packages: [
            { label: '2 Class', price: 'USD 20', sub: '10/class', color: 'bg-teal-400' },
            { label: '3 Class', price: 'USD 20', sub: '10/class', color: 'bg-purple-400' },
            { label: '5 Class', price: 'USD 20', sub: '10/class', color: 'bg-violet-500' }
          ]
        },
        {
          id: 4,
          status: 'Not Approved',
          students: 4,
          earnings: '1.0K USD',
          packages: [
            { label: '2 Class', price: 'USD 20', sub: '10/class', color: 'bg-teal-400' },
            { label: '3 Class', price: 'USD 20', sub: '10/class', color: 'bg-purple-400' },
            { label: '5 Class', price: 'USD 20', sub: '10/class', color: 'bg-violet-500' }
          ]
        },
        {
          id: 5,
          status: 'Paused',
          students: 4,
          earnings: '1.0K USD',
          packages: [
            { label: '2 Class', price: 'USD 20', sub: '10/class', color: 'bg-teal-400' },
            { label: '3 Class', price: 'USD 20', sub: '10/class', color: 'bg-purple-400' },
            { label: '5 Class', price: 'USD 20', sub: '10/class', color: 'bg-violet-500' }
          ]
        },
        {
          id: 6,
          status: 'Waiting for Approval',
          students: 4,
          earnings: '1.0K USD',
          packages: [
            { label: '2 Class', price: 'USD 20', sub: '10/class', color: 'bg-teal-400' },
            { label: '3 Class', price: 'USD 20', sub: '10/class', color: 'bg-purple-400' },
            { label: '5 Class', price: 'USD 20', sub: '10/class', color: 'bg-violet-500' }
          ]
        }
      ]
    }
  },

  methods: {
    toggleMenu(id) {
      this.openMenu = this.openMenu === id ? null : id
    }
  }
}
</script>
<template>
  <div class="min-h-screen bg-slate-50 font-sans">

    <Navbar />

    
    <TutoringSidebar
      v-model="sidebarOpen"
      v-model:activeItem="activeItem"
    />

    
    <div class="flex items-center gap-2 px-6 pt-5 pb-3">
      <button
        @click="sidebarOpen = true"
        class="text-[#139aa2] hover:text-teal-600 transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <span class="text-sm font-bold text-[#139aa2]">Tutoring &gt; My Subject</span>
    </div>

    
    <div class="flex px-6 pb-6 gap-4 w-full overflow-x-hidden">

      
      <div class="flex-1 min-w-0">

       
        <div class="flex justify-end mb-4">
          <InertiaLink 
          href="/tutor/create-subject"
          class="px-5 py-2 rounded-lg border border-[#139aa2] text-[#139aa2] text-sm font-semibold hover:bg-teal-50 transition-colors">
            Create Subject
          </InertiaLink>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="subject in subjects"
            :key="subject.id"
            class="bg-white rounded-xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-md transition-shadow"
          >
            
            <div class="relative">
              <img src="/images/tutor.jpg" alt="Subject" class="w-full h-36 object-cover"/>

              
              <div class="absolute inset-0 bg-black/30 flex items-start justify-between px-3 pt-2">
                <span class="text-white text-xs font-bold tracking-widest uppercase">Mathematics</span>
                
                <div class="relative">
                  <button
                    @click="toggleMenu(subject.id)"
                    class="text-white text-lg font-bold leading-none px-1 hover:text-slate-200 transition-colors"
                  >···</button>
                 
                  <div
                    v-if="openMenu === subject.id"
                    class="absolute right-0 top-6 bg-white rounded-lg shadow-lg z-10 py-1 flex flex-col"
                  >
                    <button class="flex items-center gap-2 px-3 py-2 text-red-500 hover:bg-red-50 transition-colors text-xs">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                    <button class="flex items-center gap-2 px-3 py-2 text-teal-500 hover:bg-teal-50 transition-colors text-xs">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </button>
                    <button class="flex items-center gap-2 px-3 py-2 text-slate-500 hover:bg-slate-50 transition-colors text-xs">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Image dots indicator -->
              <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1">
                <span v-for="n in 4" :key="n" class="w-1.5 h-1.5 rounded-full" :class="n === 1 ? 'bg-white' : 'bg-white/40'"></span>
              </div>
            </div>

            <!-- Card Body -->
            <div class="p-3">
              <!-- Subject name + status badge -->
              <div class="flex items-center gap-2 mb-2">
                <h3 class="text-sm font-bold text-slate-800">Mathematics</h3>
                <span
                  v-if="subject.status"
                  class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                  :class="{
                    'bg-red-100 text-red-500':   subject.status === 'Not Approved',
                    'bg-yellow-100 text-yellow-600': subject.status === 'Paused',
                    'bg-blue-100 text-blue-500':  subject.status === 'Waiting for Approval'
                  }"
                >{{ subject.status }}</span>
              </div>

              <!-- Class Pricing Chips -->
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
          </div>
        </div>
      </div>

      <!-- ===== ADS SIDEBAR ===== -->
      <aside class="hidden lg:flex flex-col flex-shrink-0 w-[200px] p-3">
        <div class="sticky top-[72px]">
          <img
            src="/images/adsbanner.png"
            alt="Advertisement"
            class="w-full block h-[550px]"
          />
        </div>
      </aside>

    </div>
  </div>
</template>

