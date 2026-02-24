<script>
import Navbar from '@/components/interfaces/navbar.vue'
import TutoringSidebar from '@/components/interfaces/TutoringSidebar.vue'
import VueApexCharts from 'vue3-apexcharts'

export default {
  name: 'TutoringLanding',

  components: {
    Navbar,
    TutoringSidebar,
    apexchart: VueApexCharts
  },

  data() {
    return {
      sidebarOpen: false,
      activeItem: 'Home',

      // ── Stats Cards ────────────────────────────────────────────
      statsCards: [
        {
          label: 'Total Earning',
          value: '2,838',
          change: '+2.99%',
          bg: 'bg-gradient-to-br from-amber-400 to-orange-500',
          iconBg: 'bg-white/25',
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
        },
        {
          label: 'Total Enrollments',
          value: '355',
          change: '+5.09%',
          bg: 'bg-gradient-to-br from-violet-500 to-purple-700',
          iconBg: 'bg-white/20',
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>`
        },
        {
          label: 'Total Orders',
          value: '8,328',
          change: '+2.99%',
          bg: 'bg-gradient-to-br from-fuchsia-500 to-purple-800',
          iconBg: 'bg-white/20',
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>`
        },
        {
          label: 'Course Engagements',
          value: '38',
          change: '+2.99%',
          bg: 'bg-gradient-to-br from-emerald-400 to-teal-600',
          iconBg: 'bg-white/20',
          icon: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>`
        }
      ],

      // ── ApexCharts: Bar (My Orders) ────────────────────────────
      barSeries: [
        { name: 'Orders', data: [] }
      ],
      barChartOptions: {
        chart: {
          type: 'bar',
          toolbar: { show: false },
          fontFamily: 'inherit',
          animations: { enabled: true, easing: 'easeinout', speed: 600 }
        },
        plotOptions: {
          bar: { borderRadius: 5, columnWidth: '45%' }
        },
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'light',
            type: 'vertical',
            gradientToColors: ['#14b8a6'],
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100]
          }
        },
        colors: ['#3b82f6'],
        dataLabels: { enabled: false },
        grid: {
          borderColor: '#f1f5f9',
          xaxis: { lines: { show: false } },
          yaxis: { lines: { show: true } }
        },
        xaxis: {
          categories: ['Jan','Feb','Mar','Apr','May','Jun','July','Aug','Sep','Oct','Nov','Dec'],
          axisBorder: { show: false },
          axisTicks: { show: false },
          labels: { style: { colors: '#94a3b8', fontSize: '12px', fontFamily: 'inherit' } }
        },
        yaxis: {
          min: 0,
          max: 100,
          tickAmount: 5,
          labels: { style: { colors: '#94a3b8', fontSize: '12px', fontFamily: 'inherit' } }
        },
        tooltip: {
          theme: 'light',
          y: { formatter: val => `${val} orders` }
        }
      },

      // ── ApexCharts: Area (Earning Summary) ────────────────────
      areaSeries: [
        { name: 'Last 6 months',        data: [] },
        { name: 'Same Period Last Year', data: [] }
      ],
      areaChartOptions: {
        chart: {
          type: 'area',
          toolbar: { show: false },
          fontFamily: 'inherit',
          animations: { enabled: true, easing: 'easeinout', speed: 700 }
        },
        colors: ['#3b82f6', '#cbd5e1'],
        stroke: {
          curve: 'smooth',
          width: [2, 1.5],
          dashArray: [0, 6]
        },
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.35,
            opacityTo: 0.0,
            stops: [0, 100]
          }
        },
        dataLabels: { enabled: false },
        grid: {
          borderColor: '#f1f5f9',
          xaxis: { lines: { show: false } }
        },
        xaxis: {
          categories: ['May','Jun','Jul','Aug','Sep','Oct','Nov'],
          axisBorder: { show: false },
          axisTicks: { show: false },
          labels: { style: { colors: '#94a3b8', fontSize: '12px', fontFamily: 'inherit' } }
        },
        yaxis: {
          labels: {
            formatter: val => `$${(val / 1000).toFixed(0)}k`,
            style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'inherit' }
          }
        },
        legend: { show: false },
        tooltip: {
          theme: 'light',
          y: { formatter: val => `$${(val / 1000).toFixed(1)}k` }
        }
      }
    }
  },

  methods: {
    toggleSidebar() { this.sidebarOpen = !this.sidebarOpen }
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans">

    <!-- ========== NAVBAR ========== -->
    <Navbar />

    <!-- ========== SIDEBAR ========== -->
    <TutoringSidebar
      v-model="sidebarOpen"
      v-model:activeItem="activeItem"
    />

    <!-- ========== MAIN LAYOUT ========== -->
    <div class="flex mt-[60px] min-h-[calc(100vh-60px)]">

      <main class="flex-1 p-6 min-w-0 overflow-x-hidden">

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center gap-3">
            <button
              @click="toggleSidebar"
              class="w-10 h-10 flex items-center justify-center bg-teal-500 hover:bg-teal-600 text-white rounded-xl shadow-sm transition-colors duration-200 flex-shrink-0"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
            <div>
              <h2 class="text-lg font-bold text-teal-500">Tutoring</h2>
              <p class="text-xs text-slate-400 mt-0.5">Click Here To Navigate Through The Module</p>
            </div>
          </div>
          <select class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm text-slate-600 bg-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-300 transition">
            <option>Weekly</option>
            <option>Monthly</option>
            <option>Yearly</option>
          </select>
        </div>

        <!-- ===== STATS CARDS ===== -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
          <div
            v-for="card in statsCards"
            :key="card.label"
            class="relative rounded-2xl p-5 text-white shadow-lg overflow-hidden"
            :class="card.bg"
          >
            <div class="flex items-start justify-between mb-6">
              <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" :class="card.iconBg">
                <span v-html="card.icon" class="w-6 h-6 flex items-center justify-center"></span>
              </div>
              <div class="flex items-center gap-1 bg-white/20 backdrop-blur-sm px-2.5 py-1 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                <span class="text-[11px] font-bold">{{ card.change }}</span>
              </div>
            </div>
            <div>
              <p class="text-sm font-medium opacity-90 mb-1">{{ card.label }}</p>
              <h3 class="text-3xl font-extrabold tracking-tight leading-none">{{ card.value }}</h3>
            </div>
          </div>
        </div>

        <!-- ===== MY ORDERS BAR CHART ===== -->
        <div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
          <h3 class="text-sm font-bold text-slate-700 mb-1">My Orders</h3>
          <apexchart
            type="bar"
            height="280"
            :options="barChartOptions"
            :series="barSeries"
          />
        </div>

        <!-- ===== EARNING SUMMARY AREA CHART ===== -->
        <div class="bg-white rounded-2xl shadow-sm p-5 mb-5">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="flex items-center gap-3">
              <h3 class="text-sm font-bold text-slate-700">Earning Summary</h3>
              <span class="text-xs text-slate-400 cursor-pointer hover:text-slate-600 select-none flex items-center gap-1">
                Mar 22 - Oct 2022
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </span>
            </div>
            <div class="flex items-center gap-5 text-xs text-slate-500">
              <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>
                Last 6 months
              </span>
              <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full border-2 border-slate-300 inline-block"></span>
                Same Period last year
              </span>
            </div>
          </div>
          <apexchart
            type="area"
            height="220"
            :options="areaChartOptions"
            :series="areaSeries"
          />
        </div>

        <!-- ===== BOTTOM ROW ===== -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <!-- Upcoming Classes -->
          <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-sm font-bold text-slate-700">Upcoming Classes</h3>
              <a href="#" class="text-xs font-semibold text-teal-500 hover:text-teal-700 transition-colors">See all</a>
            </div>

            <div v-for="n in 3" :key="n" class="flex items-center gap-3 py-2.5" :class="n < 3 ? 'border-b border-slate-100' : ''">
              <div class="flex flex-col items-center justify-center w-10 h-10 rounded-lg bg-teal-500 text-white flex-shrink-0">
                <span class="text-xs font-bold leading-none">21</span>
                <span class="text-[9px] leading-none opacity-80">Jan</span>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-700">PHP crash course</p>
                <p class="text-xs text-slate-400">Jahanzaib</p>
              </div>
              <div class="text-right flex-shrink-0">
                <p class="text-[10px] text-slate-400 mb-1">10AM- 11AM</p>
                <button class="bg-teal-500 hover:bg-teal-600 text-white text-[10px] font-semibold px-2.5 py-1 rounded-md transition-colors">
                  Join Now
                </button>
              </div>
            </div>
          </div>

          <!-- My Orders History -->
          <div class="bg-white rounded-2xl shadow-sm p-5">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-sm font-bold text-slate-700">My Orders History</h3>
              <a href="#" class="text-xs font-semibold text-teal-500 hover:text-teal-700 transition-colors">See all</a>
            </div>

            <div v-for="n in 3" :key="n" class="flex items-center gap-3 py-2.5" :class="n < 3 ? 'border-b border-slate-100' : ''">
              <img src="/images/tutor.jpg" alt="Aleena" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 border-teal-100" />
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-700">Aleena</p>
                <p class="text-xs text-slate-400">1 Class Left</p>
              </div>
              <button class="w-9 h-9 rounded-lg bg-teal-500 hover:bg-teal-600 text-white flex items-center justify-center flex-shrink-0 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z"/>
                </svg>
              </button>
            </div>
          </div>

        </div>

      </main>

      <!-- ========== ADS SIDEBAR ========== -->
      <aside class="hidden lg:flex flex-col flex-shrink-0 w-[200px] p-3">
        <div class="sticky top-[72px]">
          <img
            src="/images/adsbanner.png"
            alt="Advertisement"
            class="w-full block h-[790px]"
          />
        </div>
      </aside>

    </div>
  </div>
</template>