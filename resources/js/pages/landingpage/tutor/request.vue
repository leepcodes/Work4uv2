<template>
  <div class="min-h-screen bg-slate-50 font-sans">

    <Navbar />

    <!-- ========== SIDEBAR ========== -->
    <TutoringSidebar
      v-model="sidebarOpen"
      v-model:activeItem="activeItem"
    />

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 px-6 pt-5 pb-3">
      <button
        @click="sidebarOpen = true"
        class="text-[#139aa2] hover:text-teal-600 transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <span class="text-sm font-bold text-[#139aa2]">Tutoring &gt; Requests</span>
    </div>

    <!-- ===== THREE COLUMN LAYOUT ===== -->
    <div class="flex px-6 pb-6 gap-4 w-full overflow-x-hidden">

      <!-- ===== REQUEST LIST ===== -->
      <div class="requestlist w-[310px] flex-shrink-0 flex flex-col gap-2">

        <!-- Tabs -->
        <div class="flex gap-6 mb-2 mt-4">
          <button
            @click="activeTab = 'active'"
            class="text-[12px] transition-colors"
            :class="activeTab === 'active' ? 'text-[#139aa2] font-bold' : 'text-black hover:text-slate-700'"
          >Active</button>
          <button
            @click="activeTab = 'rejected'"
            class="text-[12px] transition-colors"
            :class="activeTab === 'rejected' ? 'text-[#139aa2] font-bold' : 'text-black hover:text-slate-700'"
          >Rejected</button>
        </div>

        <!-- Search -->
        <div class="relative mb-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
          </svg>
          <input
            v-model="search"
            type="text"
            placeholder="Search name"
            class="w-full pl-9 pr-3 py-2 text-xs rounded-lg border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 text-slate-600 placeholder-slate-400"
          />
        </div>

        <!-- Scrollable Cards -->
        <div class="flex flex-col gap-2 overflow-y-auto max-h-[calc(100vh-200px)] pr-1">

          <!-- Card — replace with v-for over fetched requests from DB -->
          <div
            @click="selectRequest(sampleRequest)"
            class="bg-white rounded-xl p-3 cursor-pointer border-2 transition-all duration-200 shadow-sm hover:shadow-md"
            :class="selectedRequest?.id === sampleRequest.id
              ? 'border-teal-500'
              : 'border-transparent hover:border-slate-200'"
          >
            <div class="flex items-center gap-2 mb-2">
              <img src="/images/tutor.jpg" class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-slate-200" alt="Oliver Martinez"/>
              <span class="text-sm font-bold text-slate-800 truncate">Oliver Martinez</span>
            </div>
            <p class="text-xs text-slate-500 mb-0.5">Subject: <span class="font-bold text-slate-700">English</span></p>
            <p class="text-xs text-slate-500 mb-1">Classes Requested: <span class="font-bold text-slate-700">10</span></p>
            <p class="text-[10px] text-black font-bold text-right">Requested Date: <span class="font-medium text-slate-700">21-04-2025</span></p>
          </div>

        </div>
      </div>

      <!-- ===== MIDDLE CONTAINER ===== -->
      <div class="middlecontainer flex-1 min-w-0 flex justify-center">

        <!-- Detail Panel -->
        <transition
          enter-active-class="transition-all duration-300 ease-out"
          enter-from-class="opacity-0 translate-y-2"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition-all duration-200 ease-in"
          leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 translate-y-2"
        >
          <div v-if="selectedRequest" class="middlecontainer bg-white h-130 w-150 shadow-sm p-6 items-start justify-center">
            <!-- Header -->
            <div class="flex items-start justify-between mb-5">
              <div class="flex items-center gap-3">
                <img src="/images/tutor.jpg" class="w-10 h-10 rounded-full object-cover border border-slate-200" alt="Student"/>
                <span class="text-sm font-semibold text-slate-700">Student Name</span>
              </div>
              <button @click="selectedRequest = null" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
              </button>
            </div>

            <!-- Info Rows -->
            <div class="flex flex-col gap-3 mb-4">
              <div class="flex items-center gap-2">
                <span class="text-sm text-black font-bold">Requested Date:</span>
                <span class="text-sm text-slate-600">{{ selectedRequest.requestedDate }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-sm text-black font-bold">Classes Requested:</span>
                <span class="text-sm font-bold text-slate-800">{{ selectedRequest.classesRequested }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="text-sm text-black font-bold">Subject:</span>
                <span class="inline-block text-slate-700 text-sm px-4 py-0.5">{{ selectedRequest.subject }}</span>
              </div>
            </div>

            <hr class="border-slate-100 mb-4"/>

            <!-- Message -->
            <p class="text-sm text-slate-600 leading-relaxed mb-8">{{ selectedRequest.message }}</p>

            <!-- Buttons -->
            <div class="flex items-center gap-3 mt-30">
              <button class="flex-1 py-2.5 rounded-xl border-2 border-slate-300 text-sm font-bold text-slate-600 hover:border-red-400 hover:text-red-500 transition-colors tracking-wider">
                REJECT
              </button>
              <button class="flex-1 py-2.5 rounded-xl bg-[#139aa2] hover:bg-teal-600 text-sm font-bold text-white transition-colors shadow-sm tracking-wider">
                MAKE AN OFFER
              </button>
            </div>
          </div>
        </transition>

        <!-- Empty State -->
        <div v-if="!selectedRequest" class="flex items-center justify-center h-64 text-slate-300">
          <div class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm">Select a request to view details</p>
          </div>
        </div>

      </div>

      <!-- ===== ADS SECTION ===== -->
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

<script>
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'

export default {
  name: 'TutoringRequests',
  components: { Navbar, TutoringSidebar },

  data() {
    return {
      sidebarOpen: false,
      activeItem: 'Requests',
      activeTab: 'active',
      search: '',
      selectedRequest: null,

      // Replace with v-for over DB fetch later
      sampleRequest: {
        id: 1,
        name: 'Oliver Martinez',
        subject: 'English',
        classesRequested: 10,
        requestedDate: '21-04-2025',
        message: 'Hello, I would like to request classes with you. I need 6 sessions for the Mathematics subject, and I\'d like to schedule them within this month. Hello, I would like to request classes with you. I need 6 sessions for the Mathematics subject, and I\'d like to schedule them within this month.'
      }
    }
  },

  methods: {
    selectRequest(request) {
      this.selectedRequest = this.selectedRequest?.id === request.id ? null : request
    }
  }
}
</script>