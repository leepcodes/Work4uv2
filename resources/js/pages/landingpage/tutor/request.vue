<script lang="ts">
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'
import Ads from '@/components/interfaces/ads.vue'
import { router, useForm } from '@inertiajs/vue3'

interface TutoringRequest {
  id: number
  student: { 
    username: string
    photo?: string | null
  }
  subject: { subject_title: string }
  custom_class_count: number
  created_at: string
  message: string
  status: string
  tutor_custom_price?: number | null  
}

export default {
  name: 'TutoringRequests',
  components: { Navbar, TutoringSidebar, Ads },

  props: {
    requests: {
      type: Array as () => TutoringRequest[],
      default: () => [],
    },
  },

  data() {
    return {
      sidebarOpen: false,
      activeItem: 'Requests',
      activeTab: 'active',
      search: '',
      selectedRequest: null as TutoringRequest | null,
      showOfferModal: false,
      offerPrice: null as number | null,
      offerMessage: '',
    }
  },

  computed: {
    filteredRequests(): TutoringRequest[] {
      return this.requests.filter((r) =>
        r.student.username.toLowerCase().includes(this.search.toLowerCase())
      )
    },
  },

  watch: {
    activeTab(newTab) {
      const status = newTab === 'active' ? 'pending' : 'rejected'
      router.get(
        '/tutor/request',
        { status },
        { preserveState: true, replace: true }
      )
      this.selectedRequest = null
    },
  },

  methods: {
    selectRequest(request: TutoringRequest) {
      this.selectedRequest = this.selectedRequest?.id === request.id ? null : request
    },

    formatDate(dateStr: string): string {
      return new Date(dateStr).toLocaleDateString('en-GB')
    },

    openOfferModal() {
      this.offerPrice = null
      this.offerMessage = ''
      this.showOfferModal = true
    },

    closeOfferModal() {
      this.showOfferModal = false
    },

    submitOffer() {
      if (!this.selectedRequest) return
      router.post('/tutor/request/offer', {
        request_id:    this.selectedRequest.id,
        offer_price:   this.offerPrice,
        offer_message: this.offerMessage,
      }, {
        onSuccess: () => {
          this.showOfferModal = false
          this.selectedRequest = null
        }
      })
    },
  },
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans">
    <Navbar />
    <TutoringSidebar v-model="sidebarOpen" v-model:activeItem="activeItem" />

    <div class="flex items-center gap-2 px-6 pt-5 pb-3">
      <button @click="sidebarOpen = true" class="text-[#139aa2] hover:text-teal-600 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <span class="text-sm font-bold text-[#139aa2]">Tutoring &gt; Requests</span>
    </div>

    <div class="flex px-6 pb-6 gap-4 w-full overflow-x-hidden">

      <!-- Request List -->
      <div class="requestlist w-[310px] flex-shrink-0 flex flex-col gap-2">

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

        <!-- Request Cards -->
        <div class="flex flex-col gap-2 overflow-y-auto max-h-[calc(100vh-200px)] pr-1">

          <div v-if="filteredRequests.length === 0" class="text-center text-xs text-slate-400 mt-6">
            No requests found.
          </div>

          <div
            v-for="req in filteredRequests"
            :key="req.id"
            @click="selectRequest(req)"
            class="bg-white rounded-xl p-3 cursor-pointer border-2 transition-all duration-200 shadow-sm hover:shadow-md"
            :class="selectedRequest?.id === req.id ? 'border-teal-500' : 'border-transparent hover:border-slate-200'"
          >
            <div class="flex items-center gap-2 mb-2">
              <img
                :src="req.student.photo ?? '/images/tutor.jpg'"
                class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-slate-200"
                :alt="req.student.username"
              />
              <span class="text-sm font-bold text-slate-800 truncate">{{ req.student.username }}</span>
            </div>
            <p class="text-xs text-slate-500 mb-0.5">Subject: <span class="font-bold text-slate-700">{{ req.subject.subject_title }}</span></p>
            <p class="text-xs text-slate-500 mb-1">Classes Requested: <span class="font-bold text-slate-700">{{ req.custom_class_count }}</span></p>
            <p class="text-[10px] text-black font-bold text-right">
              Requested Date: <span class="font-medium text-slate-700">{{ formatDate(req.created_at) }}</span>
            </p>
          </div>
        </div>
      </div>

      <!-- Detail Panel -->
      <div class="middlecontainer  h-auto py-10 overflow-hidden w-full   flex flex-col justify-center items-center">
        <transition
              enter-active-class="transition-all duration-300 ease-out"
              enter-from-class="opacity-0 translate-y-2"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition-all duration-200 ease-in"
              leave-from-class="opacity-100 translate-y-0"
              leave-to-class="opacity-0 translate-y-2"
            >
              <div v-if="selectedRequest" class="flex flex-col gap-4">

                <!-- TOP -->
                <div class="bg-white h-auto rounded-xl w-150 shadow-sm p-6 items-start justify-center">
                  <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center gap-3">
                      <img
                        :src="selectedRequest.student.photo ?? '/images/tutor.jpg'"
                        class="w-10 h-10 rounded-full object-cover border border-slate-200"
                        :alt="selectedRequest.student.username"
                      />
                      <span class="text-sm font-semibold text-slate-700">{{ selectedRequest.student.username }}</span>
                    </div>
                    <button @click="selectedRequest = null" class="text-slate-400 hover:text-slate-600 transition-colors">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                    </button>
                  </div>

                  <div class="flex flex-col gap-3 mb-4">
                    <div class="flex items-center gap-2">
                      <span class="text-sm text-black font-bold">Requested Date:</span>
                      <span class="text-sm text-slate-600">{{ formatDate(selectedRequest.created_at) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="text-sm text-black font-bold">Classes Requested:</span>
                      <span class="text-sm font-bold text-slate-800">{{ selectedRequest.custom_class_count }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="text-sm text-black font-bold">Subject:</span>
                      <span class="inline-block text-slate-700 text-sm px-4 py-0.5">{{ selectedRequest.subject.subject_title }}</span>
                    </div>
                  </div>

                  <hr class="border-slate-100 mb-4"/>
                  <p class="text-sm text-slate-600 leading-relaxed mb-8">{{ selectedRequest.message }}</p>

                  <div  v-if="selectedRequest.status !== 'offered'" class="flex items-center gap-3 mt-30">
                    <button class="flex-1 py-2.5 rounded-xl border-2 border-slate-300 text-sm font-bold text-slate-600 hover:border-red-400 hover:text-red-500 transition-colors tracking-wider">
                      REJECT
                    </button>
                    <button
                      @click="openOfferModal"
                      class="flex-1 py-2.5 rounded-xl bg-[#139aa2] hover:bg-teal-600 text-sm font-bold text-white transition-colors shadow-sm tracking-wider"
                    >
                      MAKE AN OFFER
                    </button>
                  </div>
                </div>

                <!-- BTM (offered status) -->
                <div class="bg-white rounded-xl  w-150 shadow-sm p-6">
                  <div class="flex flex-col gap-3 mb-4">
                    <div class="flex items-center gap-2">
                      <span class="text-sm text-black font-bold">Requested Date:</span>
                      <span class="text-sm text-slate-600">{{ formatDate(selectedRequest.created_at) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="text-sm text-black font-bold">Classes Requested:</span>
                      <span class="text-sm font-bold text-slate-800">{{ selectedRequest.custom_class_count }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <span class="text-sm text-black font-bold">Subject:</span>
                      <span class="inline-block text-slate-700 text-sm px-4 py-0.5">{{ selectedRequest.subject.subject_title }}</span>
                    </div>
                  </div>

                  <hr class="border-slate-100 mb-4"/>
                  <p class="text-sm text-slate-600 leading-relaxed mb-4">{{ selectedRequest.message }}</p>

                  <div class="border border-slate-200 rounded-xl p-4">
                    <p class="text-sm font-semibold text-[#139aa2]">
                      Price <span class="font-bold">{{ selectedRequest.tutor_custom_price }} US$</span>
                    </p>
                  </div>
                </div>

              </div>
            </transition>

            <div v-if="!selectedRequest" class="flex items-center justify-center h-64 text-slate-300">
              <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm">Select a request to view details</p>
              </div>
            </div>
          </div>

             <Ads />
    </div>
  </div>

  <!-- Make Offer Modal -->
  <div v-if="showOfferModal" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-[520px] p-8">
      <h2 class="text-lg font-bold text-gray-800 text-center mb-6">Make Offer</h2>

      <div class="mb-4">
        <label class="text-sm text-gray-600 mb-1 block">Subject</label>
        <p class="text-sm font-medium text-gray-800 px-3 py-2 border border-gray-200 rounded-lg">
          {{ selectedRequest?.subject.subject_title }}
        </p>
      </div>

      <div class="mb-4">
        <label class="text-sm text-gray-600 mb-1 block">Enter Price</label>
        <div class="flex items-center gap-2">
          <input
            v-model="offerPrice"
            type="number"
            min="1"
            class="w-32 border text-black border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
          />
          <span class="text-sm text-gray-600 font-medium">US$</span>
        </div>
      </div>

      <div class="mb-6">
        <label class="text-sm text-gray-600 mb-1 block">Message To Student</label>
        <textarea
          v-model="offerMessage"
          rows="4"
          class="w-full border text-black border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 resize-none"
        ></textarea>
      </div>

      <div class="flex justify-end gap-3">
        <button
          @click="closeOfferModal"
          class="px-5 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50"
        >
          Cancel
        </button>
        <button
          @click="submitOffer"
          class="px-5 py-2 rounded-lg bg-[#139aa2] text-white text-sm font-semibold hover:bg-teal-600"
        >
          Submit Request
        </button>
      </div>
    </div>
  </div>
</template>