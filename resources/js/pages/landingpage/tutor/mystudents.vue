<script setup lang="ts">
import { ref } from 'vue'
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'
import Ads from '@/components/interfaces/ads.vue'

const sidebarOpen = ref(false)

defineProps({
  adBannerSrc: {
    type: String,
    default: '/images/adsbanner.png',
  },
  adBannerAlt: {
    type: String,
    default: 'Advertisement',
  },
})
</script>

<template>
  <Navbar />
  <div class="flex items-center bg-white gap-2 px-6 pt-5 pb-3">
    <button
      @click="sidebarOpen = true"
      class="text-[#139aa2] hover:text-teal-600 transition-colors"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
    <span class="text-sm font-bold text-[#139aa2]">Tutoring &gt; My Students</span>
  </div>
  <div class="flex items-center bg-white gap-2 px-6 pt-5 pb-3">
    
   

    <!-- Back chevron -->
    <span class="text-[#139aa2] cursor-pointer">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
    </span>

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-1 text-sm">
      <span class="font-bold text-black cursor-pointer hover:text-[#139aa2] transition-colors">Students List</span>
      <span class="font-bold text-black">-</span>
      <span class="font-bold text-black cursor-pointer hover:text-[#139aa2] transition-colors">Student &amp; Subjects</span>
      <span class="font-bold text-black">-</span>
      <span class="font-bold text-black cursor-pointer hover:text-[#139aa2] transition-colors">Package Classes</span>
      <span class="font-bold text-black">-</span>
      <span class="font-bold text-black">Class Details</span>
    </nav>
  </div>

  <main class="flex w-full h-full items-start bg-white justify-start">

    <!-- LEFT -->
    <div class="w-[25%] h-full px-6 py-4">
      <div class="flex flex-col pt-6 gap-1">

        <!-- Chat icon -->
        <div class="self-end mb-2 text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </div>

        <!-- Avatar -->
        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-200">
          <img src="/images/tutor-avatar.png" alt="Tutor" class="w-full h-full object-cover" />
        </div>

        <!-- Name -->
        <p class="mt-2 text-sm font-semibold text-gray-800">Tutor's Name</p>

        <!-- ID -->
        <p class="text-xs text-gray-400">TTRITA2025462464474724</p>

        <!-- Divider -->
        <div class="w-full border-t border-gray-100 my-3" />

        <!-- Package info -->
        <div class="w-full flex flex-col gap-2 text-sm">
          <p class="font-bold text-gray-800">Mathematics - Package 1</p>
          <p class="text-gray-600">
            Purchase Date <span class="font-bold text-gray-800">26/05/2025</span>
          </p>
          <p class="text-gray-600">
            Package Price <span class="font-bold text-gray-800">20 US$</span>
          </p>
        </div>

      </div>
    </div>

    <!-- MIDDLE: 2-col inner layout -->
    <div class="w-[60%] h-full px-6 py-4">
      <div class="flex gap-6">

        <!-- LEFT inner col: content -->
        <div class="flex flex-col gap-4 flex-1">

          <!-- Schedule title -->
          <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#139aa2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm font-semibold text-[#139aa2]">Schedule</span>
          </div>

          <!-- No class scheduled -->
          <p class="text-xs text-red-400">No Class Scheduled</p>

          <!-- Video link -->
          <div class="flex flex-col gap-1">
            <label class="text-sm text-gray-700">Video link</label>
            <input
              type="text"
              class="w-full border border-gray-200 bg-gray-50 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#139aa2]"
            />
          </div>

          <!-- Notes -->
          <div class="flex flex-col gap-1">
            <label class="text-sm text-gray-700">Notes</label>
            <div class="text-gray-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
          </div>

          <!-- Documents upload -->
          <div class="flex flex-col gap-2">
            <label class="text-sm text-gray-700">Documents</label>
            <div class="border-2 border-dashed border-[#139aa2] rounded-lg p-6 flex flex-col items-center gap-1 text-center cursor-pointer hover:bg-teal-50 transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#139aa2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <p class="text-sm font-semibold text-[#139aa2]">Upload Document</p>
              <p class="text-xs text-gray-400">Drag and drop or the click on the browse</p>
              <p class="text-xs text-gray-400">Supports: JPG, PNG, PDF, DOC, DOCX</p>
            </div>
          </div>

          <!-- Action buttons -->
          <div class="flex gap-3">
            <button class="flex-1 border border-gray-300 text-gray-600 text-sm font-semibold py-2 rounded-lg hover:bg-gray-50 transition-colors">
              Cancel
            </button>
            <button class="flex-1 bg-[#139aa2] text-white text-sm font-semibold py-2 rounded-lg hover:bg-teal-600 transition-colors">
              SAVE
            </button>
          </div>

          <!-- Document list -->
          <div class="flex flex-col gap-3">
            <div class="flex flex-col gap-1">
              <div class="flex items-center justify-between bg-white border border-gray-100 rounded-lg px-3 py-2 shadow-sm">
                <div class="flex items-center gap-3">
                  <div class="bg-red-100 rounded p-1.5">
                    <span class="text-red-500 text-xs font-bold">PDF</span>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">Document Name</p>
                    <p class="text-xs text-gray-400">766 kb</p>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <button class="bg-[#139aa2] text-white text-xs px-3 py-1 rounded-full">View</button>
                  <button class="text-red-500 hover:text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                  </button>
                </div>
              </div>
              <p class="text-xs text-gray-400 px-1">Loaded on 10th October 2025 from Sophia Johnson</p>
            </div>
            <div class="flex flex-col gap-1">
              <div class="flex items-center justify-between bg-white border border-gray-100 rounded-lg px-3 py-2 shadow-sm">
                <div class="flex items-center gap-3">
                  <div class="bg-red-100 rounded p-1.5">
                    <span class="text-red-500 text-xs font-bold">PDF</span>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">Document Name</p>
                    <p class="text-xs text-gray-400">766 kb</p>
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <button class="bg-[#139aa2] text-white text-xs px-3 py-1 rounded-full">View</button>
                  <button class="text-red-500 hover:text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                  </button>
                </div>
              </div>
              <p class="text-xs text-gray-400 px-1">Loaded on 10th October 2025 from Sophia Johnson</p>
            </div>
          </div>

        </div>

        <!-- RIGHT inner col: hamburger + Add Consultation Note -->
        <div class="flex flex-col items-end gap-2 pt-0.5 shrink-0">
          <div class="flex items-center gap-2">
            <button class="text-gray-500 hover:text-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
            <button class="bg-[#e8f4f5] text-[#139aa2] text-xs font-semibold px-3 py-1.5 rounded-full whitespace-nowrap">
              + Add Consultation Note
            </button>
          </div>
        </div>

      </div>
    </div>

    <!-- ADS SIDEBAR -->
    <Ads />

  </main>
</template>