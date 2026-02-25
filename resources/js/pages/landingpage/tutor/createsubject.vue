<script >
import Navbar from '@/components/interfaces/navbar.vue'
import { Link as InertiaLink } from '@inertiajs/vue3';

export default {
  name: 'TutoringCreateSubject',
  components: { Navbar, InertiaLink },

  data() {
    return {
      previewImages: [],
      form: {
        subject:     '',
        description: '',
        ageFrom:     '',
        ageTo:       '',
        price2:      '',
        price3:      '',
        price5:      ''
      }
    }
  },

  methods: {
    handleImageUpload(e) {
      const files = Array.from(e.target.files)
      const remaining = 5 - this.previewImages.length
      files.slice(0, remaining).forEach(file => {
        const reader = new FileReader()
        reader.onload = ev => this.previewImages.push(ev.target.result)
        reader.readAsDataURL(file)
      })
    },
    removeImage(index) {
      this.previewImages.splice(index, 1)
    }
  }
}
</script>

<template>
  <div class="min-h-screen bg-white font-sans">

    <Navbar />

   
    <div class="flex items-center gap-1 px-6 pt-5 pb-2">
      <InertiaLink
        href="/tutor/my-subject"
        class="text-[#139aa2] hover:text-teal-600 transition-colors text-sm font-bold"
      >
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
      </InertiaLink>
      <span class="text-sm font-bold text-[#139aa2] ml-1">Create a New Subject</span>
    </div>

    <main class="flex w-full h-full items-start justify-start">

      <!-- ===== LEFT: FORM ===== -->
      <div class="w-[50%] h-full px-6 py-4">

        <!-- Subject Details -->
        <h2 class="text-sm font-bold text-slate-800 mb-3">Subject Details</h2>

        <!-- Subject Title -->
        <div class="mb-1">
          <label class="block text-xs font-semibold text-slate-700 mb-1">Subject Title</label>
          <div class="relative">
            <select
              v-model="form.subject"
              class="w-full appearance-none border border-slate-200 rounded-lg px-3 py-2.5 text-xs text-slate-400 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300 cursor-pointer"
            >
              <option value="" disabled>Choose Subject</option>
              <option>Mathematics</option>
              <option>English</option>
              <option>Science</option>
              <option>History</option>
              <option>Programming</option>
            </select>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>
          <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">
            Choose subject title (this will be used as the title of the subject/topic/course). In case of a newly added subject, approval is required.
          </p>
        </div>

        <!-- Subject Description -->
        <div class="mt-4 mb-5">
          <label class="block text-xs font-semibold text-slate-700 mb-1">Subject Description</label>
          <div class="relative">
            <textarea
              v-model="form.description"
              maxlength="500"
              rows="4"
              placeholder="Describe what students will learn in this subject..."
              class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-xs text-slate-600 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-300 resize-none"
            ></textarea>
            <span class="absolute bottom-2 right-3 text-[10px] text-slate-400">{{ form.description.length }} / 500 characters</span>
          </div>
        </div>

        <!-- Suitable Age -->
        <div class="mb-6">
          <label class="block text-xs font-semibold text-slate-700 mb-3">Suitable Age</label>
          <div class="flex items-center gap-6">
            <div>
              <p class="text-xs text-slate-500 mb-1.5">From</p>
              <input
                v-model="form.ageFrom"
                type="number"
                min="1"
                class="w-24 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"
              />
            </div>
            <div>
              <p class="text-xs text-slate-500 mb-1.5">To</p>
              <input
                v-model="form.ageTo"
                type="number"
                min="1"
                class="w-24 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 bg-white focus:outline-none focus:ring-2 focus:ring-teal-300"
              />
            </div>
          </div>
        </div>

        <!-- Preview / Image Upload -->
        <div class="mb-6">
          <label class="block text-xs font-semibold text-slate-700 mb-2">Preview</label>
          <div
            class="border border-slate-200 rounded-xl bg-slate-50 flex flex-col items-center justify-center py-10 cursor-pointer hover:border-teal-300 hover:bg-teal-50/20 transition-colors"
            @click="$refs.fileInput.click()"
          >
            <div class="w-10 h-10 rounded-full border-2 border-[#139aa2] flex items-center justify-center mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#139aa2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>
            </div>
            <p class="text-xs">
              <span class="text-[#139aa2] font-medium hover:underline">Add more images</span>
              <span class="text-slate-500"> or Browse</span>
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Recommended size: 1200x800px. Max 5 images.</p>
            <input ref="fileInput" type="file" accept="image/*" multiple class="hidden" @change="handleImageUpload"/>
          </div>

          <!-- Image Previews -->
          <div v-if="previewImages.length" class="flex gap-2 mt-3 flex-wrap">
            <div v-for="(img, i) in previewImages" :key="i" class="relative w-20 h-16 rounded-lg overflow-hidden border border-slate-200">
              <img :src="img" class="w-full h-full object-cover"/>
              <button
                @click.stop="removeImage(i)"
                class="absolute top-0.5 right-0.5 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[9px] hover:bg-red-600"
              >✕</button>
            </div>
          </div>
        </div>

        <!-- Set Your Pricing -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-3">Set Your Pricing</label>
          <div class="flex gap-3">

            <div class="flex-1 bg-[#b2dfe1] rounded-xl px-3 py-3">
              <p class="text-slate-700 text-xs font-bold mb-2">2 Classes</p>
              <div class="bg-white rounded-lg flex items-center px-2 py-1.5">
                <span class="text-slate-500 text-xs font-semibold mr-1">$</span>
                <input v-model="form.price2" type="number" placeholder="0.00"
                  class="w-full text-xs text-slate-600 placeholder-slate-400 focus:outline-none bg-transparent"/>
              </div>
            </div>

            <div class="flex-1 bg-[#c8b8e8] rounded-xl px-3 py-3">
              <p class="text-slate-700 text-xs font-bold mb-2">3 Classes</p>
              <div class="bg-white rounded-lg flex items-center px-2 py-1.5">
                <span class="text-slate-500 text-xs font-semibold mr-1">$</span>
                <input v-model="form.price3" type="number" placeholder="0.00"
                  class="w-full text-xs text-slate-600 placeholder-slate-400 focus:outline-none bg-transparent"/>
              </div>
            </div>

            <div class="flex-1 bg-[#c4b5e8] rounded-xl px-3 py-3">
              <p class="text-slate-700 text-xs font-bold mb-2">5 Classes</p>
              <div class="bg-white rounded-lg flex items-center px-2 py-1.5">
                <span class="text-slate-500 text-xs font-semibold mr-1">$</span>
                <input v-model="form.price5" type="number" placeholder="0.00"
                  class="w-full text-xs text-slate-600 placeholder-slate-400 focus:outline-none bg-transparent"/>
              </div>
            </div>

          </div>
          <p class="text-[11px] text-slate-500 mt-2">
            Minimum price must be at least 10 US$ <span class="font-bold underline">per class</span>
          </p>
        </div>

      </div>

      <!-- ===== MIDDLE: BUTTONS ===== -->
      <div class="w-[34%] h-full px-6 py-4 flex flex-col gap-3 pt-10 items-center">
        <button class="w-80 py-2.5 rounded-xl border border-slate-300 text-sm font-medium text-slate-500 bg-white hover:bg-slate-50 transition-colors">
          Cancel
        </button>
        <button class="w-80 py-2.5 rounded-xl bg-[#7dbfc4] hover:bg-[#139aa2] text-sm font-semibold text-white transition-colors shadow-sm">
          Create Subject
        </button>
        <button class="w-80 py-2.5 rounded-xl border border-red-300 text-sm font-medium text-red-400 bg-white hover:bg-red-50 transition-colors">
          Delete Subject
        </button>
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

    </main>
  </div>
</template>