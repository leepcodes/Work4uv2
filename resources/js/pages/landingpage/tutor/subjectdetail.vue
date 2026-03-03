<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Navbar from '@/components/interfaces/navbar.vue'
import Ads from '@/components/interfaces/ads.vue'


const showEnrollModal = ref(false)
const paymentLoading = ref(false)
const paymentSuccess = ref(false)
const selectedPrice = ref<number | null>(null)

const selectPackage = (type: string, price: number | null) => {
    selectedPackage.value = type
    selectedPrice.value = price
    showEnrollModal.value = true
}

const props = defineProps<{
  subject: {
    id: number
    title: string
    description: string | null
    image: string | null
    suitable_age: string | null
    two_class: number | null
    three_class: number | null
    five_class: number | null
    students: number
    tutor: {
      id: number       
      uuid: string
      firstname: string
      lastname: string
      photo: string | null
    }
  }
}>()

const selectedPackage = ref<string | null>(null)
const showCustomModal = ref(false)

const form = useForm({
    subject_id: props.subject.id,
    tutor_id: props.subject.tutor.id,  // ✅ was props.subject.tutor.uuid
    message: '',
    custom_class_count: null as number | null,
})

const submitCustomRequest = () => {
    form.post('/subject/request', {
        onSuccess: () => {
            showCustomModal.value = false
            selectedPackage.value = null
            form.reset()
        }
    })
}

const confirmEnrollment = () => {
    paymentLoading.value = true

    setTimeout(() => {
        paymentLoading.value = false
        paymentSuccess.value = true
    }, 2000) // 2 second fake loading
}

const closeEnrollModal = () => {
    showEnrollModal.value = false
    paymentLoading.value = false
    paymentSuccess.value = false
    selectedPackage.value = null
}


</script>

<template>
  <Head :title="subject.title" />

  <div class="min-h-screen bg-white font-sans">
    <Navbar />

    <div class="px-6 pt-5 pb-2">
      <Link :href="`/tutor/profile/${subject.tutor.uuid}`" class="text-[#139aa2] hover:text-teal-600 text-xs font-semibold transition-colors">
        &lt; Return to Previous Page
      </Link>
    </div>

    <main class="flex w-full h-full items-start justify-start">

      <div class="w-[84%] h-full px-6 py-4">

        <div class="relative w-full rounded-xl overflow-hidden mb-2">
          <img
            :src="subject.image ?? '/images/tutor.jpg'"
            :alt="subject.title"
            class="w-full h-52 object-cover"
          />
        </div>

        <div class="flex justify-end mb-4">
          <span class="text-[10px] text-slate-500 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{ subject.students }} Enrolled
          </span>
        </div>

        <div class="flex items-center gap-2 mb-1">
          <img
            :src="subject.tutor.photo ?? '/images/tutor.jpg'"
            :alt="subject.tutor.firstname"
            class="w-8 h-8 rounded-full object-cover border border-slate-200"
          />
          <span class="text-xs text-slate-600 font-medium">
            {{ subject.tutor.firstname }} {{ subject.tutor.lastname }}
          </span>
        </div>

        <h1 class="text-xl font-bold text-slate-800 mb-3">{{ subject.title }}</h1>

        <p class="text-xs text-slate-500 leading-relaxed mb-6 max-w-2xl">
          {{ subject.description }}
        </p>

        <div class="mb-6">
          <h3 class="text-sm font-bold text-slate-700 mb-1">Suitable Age</h3>
          <p class="text-xs text-slate-500">From {{ subject.suitable_age }}</p>
        </div>

        <h3 class="text-sm font-bold text-slate-700 mb-3">Choose One</h3>
        <div class="mb-8 flex items-center justify-start rounded-xl p-4">
          <div class="flex gap-4 h-38 w-[100%]">

            <!-- package 2 -->
            <div
              @click="selectPackage('2', subject.two_class)"
              class="flex-1 rounded-xl px-6 py-6 cursor-pointer transition-all text-center"
              :class="selectedPackage === '2' ? 'bg-teal-500' : 'bg-[#b2dfe1]'"
            >
              <p class="text-white text-lg font-bold mb-1">2 Class</p>
              <p class="text-white text-sm font-semibold mb-4">USD {{ subject.two_class }}</p>
              <p class="text-white/70 text-sm leading-relaxed">{{ subject.students }}K Students have purchased this package</p>
            </div>
            <!-- package 3 -->
            <div
              @click="selectPackage('3', subject.three_class)"
              class="flex-1 rounded-xl px-6 py-6 cursor-pointer transition-all text-center"
              :class="selectedPackage === '3' ? 'bg-purple-500' : 'bg-[#c8b8e8]'"
            >
              <p class="text-white text-lg font-bold mb-1">3 Class</p>
              <p class="text-white text-sm font-semibold mb-4">USD {{ subject.three_class }}</p>
              <p class="text-white/70 text-sm leading-relaxed">{{ subject.students }}K Students have purchased this package</p>
            </div>
            <!-- package 5 -->
            <div
              @click="selectPackage('5', subject.five_class)"
              class="flex-1 rounded-xl px-6 py-6 cursor-pointer transition-all text-center"
              :class="selectedPackage === '5' ? 'bg-violet-600' : 'bg-[#c4b5e8]'"
            >
              <p class="text-white text-lg font-bold mb-1">5 Class</p>
              <p class="text-white text-sm font-semibold mb-4">USD {{ subject.five_class }}</p>
              <p class="text-white/70 text-sm leading-relaxed">{{ subject.students }}K Students have purchased this package</p>
            </div>

            <div
              @click="selectedPackage = 'custom'; showCustomModal = true"
              class="flex-1 rounded-xl px-6 py-6 cursor-pointer transition-all flex items-center justify-center text-center"
              :class="selectedPackage === 'custom' ? 'bg-slate-400' : 'bg-slate-200'"
            >
              <p class="text-slate-700 text-sm font-bold leading-snug">Request Different<br/>Number of Classes</p>
            </div>

          </div>
        </div>

        <div class="flex justify-end max-w-2xl">
          <button
            class="px-6 py-2.5 rounded-lg text-white text-sm font-semibold transition-colors shadow-sm"
            :class="selectedPackage
              ? 'bg-[#139aa2] hover:bg-teal-600 cursor-pointer'
              : 'bg-slate-300 cursor-not-allowed'"
            :disabled="!selectedPackage"
          >
            Proceed and Pay
          </button>
        </div>

      </div>

      <Ads />

    </main>
  </div>

  <!-- Custom Class Request Modal -->
  <div v-if="showCustomModal" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-[520px] p-8">
      <h2 class="text-lg font-bold text-gray-800 text-center mb-6">Class Request</h2>
      
        <span v-if="(form.errors as any).custom_request" class="text-red-500 text-sm font0 self-center">
          {{ (form.errors as any).custom_request }}
        </span>
        
      <div class="mb-4 mt-2">
        <label class="text-sm text-gray-600 mb-1 block">Subject</label>
        <p class="text-sm font-medium text-gray-800 px-3 py-2 border border-gray-200 rounded-lg">{{ subject.title }}</p>
      </div>

      <div class="mb-4">
        <label class="text-sm text-gray-600 mb-1 block">N of Classes Requested</label>
        <input
          v-model="form.custom_class_count"
          type="number"
          min="1"
          class="w-24 border text-black border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
        />
        <p v-if="form.errors.custom_class_count" class="text-red-500 text-xs mt-1">{{ form.errors.custom_class_count }}</p>
      </div>

      <div class="mb-6">
        <label class="text-sm text-gray-600 mb-1 block">Message for the Tutor</label>
        <textarea
          v-model="form.message"
          rows="4"
          class="w-full border text-black border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 resize-none"
        ></textarea>
        <p v-if="form.errors.message" class="text-red-500 text-xs mt-1">{{ form.errors.message }}</p>
      </div>

      <div class="flex justify-end gap-3">

        <button
          @click="showCustomModal = false; selectedPackage = null"
          class="px-5 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50"
        >
          Cancel
        </button>
        <button
          @click="submitCustomRequest"
          :disabled="form.processing"
          class="px-5 py-2 rounded-lg bg-[#139aa2] text-white text-sm font-semibold hover:bg-teal-600 disabled:opacity-50"
        >
          {{ form.processing ? 'Submitting...' : 'Submit Request' }}
        </button>
      </div>
    </div>
  </div>

  <!-- Enrollment & Mock Payment Modal -->
<div v-if="showEnrollModal" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl shadow-xl w-[420px] p-8 text-center">

    <!-- SUCCESS STATE -->
    <div v-if="paymentSuccess">
      <div class="text-green-500 text-5xl mb-4">✔</div>
      <h2 class="text-lg font-bold text-gray-800 mb-2">Payment Successful!</h2>
      <p class="text-sm text-gray-500 mb-6">
        You are now enrolled in {{ selectedPackage }} classes.
      </p>

      <button
        @click="closeEnrollModal"
        class="px-6 py-2 rounded-lg bg-[#139aa2] text-white text-sm font-semibold hover:bg-teal-600"
      >
        Done
      </button>
    </div>

    <!-- LOADING STATE -->
    <div v-else-if="paymentLoading">
      <div class="animate-spin rounded-full h-10 w-10 border-4 border-teal-500 border-t-transparent mx-auto mb-4"></div>
      <h2 class="text-md font-semibold text-gray-700">Processing Payment...</h2>
      <p class="text-xs text-gray-500 mt-2">Please wait</p>
    </div>

    <!-- CONFIRM STATE -->
    <div v-else>
      <h2 class="text-lg font-bold text-gray-800 mb-3">Confirm Enrollment</h2>
      <p class="text-sm text-gray-600 mb-2">
        Would you like to enroll in
        <span class="font-semibold">{{ selectedPackage }} Classes</span>?
      </p>

      <p class="text-sm font-semibold text-gray-800 mb-6">
        Total: USD {{ selectedPrice }}
      </p>

      <div class="flex justify-center gap-3">
        <button
          @click="closeEnrollModal"
          class="px-5 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50"
        >
          Cancel
        </button>

        <button
          @click="confirmEnrollment"
          class="px-5 py-2 rounded-lg bg-[#139aa2] text-white text-sm font-semibold hover:bg-teal-600"
        >
          Confirm & Pay
        </button>
      </div>
    </div>

  </div>
</div>
</template>