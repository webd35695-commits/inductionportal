<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Debug Section (Remove after testing) -->
    <div v-if="false" class="fixed top-0 right-0 bg-black text-white p-4 text-xs z-50 max-w-md overflow-auto max-h-96">
      <div>Application: {{ application }}</div>
      <div>Profile: {{ profile }}</div>
      <div>Contact: {{ contact }}</div>
      <div>Qualifications: {{ qualifications }}</div>
    </div>

    <!-- Animated Background Pattern -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none opacity-30">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-teal-400 to-blue-500 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 -left-40 w-96 h-96 bg-gradient-to-tr from-purple-400 to-pink-500 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-gradient-to-tl from-amber-400 to-orange-500 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

      <!-- Flash Messages with Animation -->
      <transition name="slide-down">
        <div v-if="flash.success" class="mb-6 p-5 bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 rounded-xl shadow-lg backdrop-blur-sm">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-check-circle text-emerald-500 text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-emerald-900">{{ flash.success }}</p>
            </div>
          </div>
        </div>
      </transition>

      <transition name="slide-down">
        <div v-if="flash.error" class="mb-6 p-5 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-xl shadow-lg backdrop-blur-sm">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-circle text-red-500 text-2xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-red-900">{{ flash.error }}</p>
            </div>
          </div>
        </div>
      </transition>

      <!-- Enhanced Page Header -->
      <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
          <div class="flex-1">
            <Link
              :href="route('candidate.dashboard')"
              class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-teal-600 transition-colors mb-3 group"
            >
              <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
              Back to Dashboard
            </Link>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-900 via-slate-800 to-slate-700 bg-clip-text text-transparent mb-2">
              Application Details
            </h1>
            <p class="text-slate-600 text-lg">Review your complete application information</p>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-wrap items-center gap-4">
            <!-- Download Button -->
            <button
              @click="downloadApplication"
              :disabled="isDownloading"
              class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-semibold rounded-xl hover:from-teal-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden"
            >
              <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
              <i class="fas fa-download mr-2 group-hover:scale-110 transition-transform"></i>
              <span>{{ isDownloading ? 'Preparing...' : 'Download' }}</span>
            </button>

            <!-- Pay Fee Button -->
            <button
              v-if="canMakePayment"
              @click="makePayment"
              :disabled="isPaying"
              class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed overflow-hidden"
            >
              <span class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity"></span>
              <i class="fas fa-credit-card mr-2 group-hover:scale-110 transition-transform"></i>
              <span>{{ isPaying ? 'Processing...' : 'Pay Fee' }}</span>
            </button>

            <!-- Status Badge -->
            <div class="inline-flex items-center px-5 py-3 rounded-xl font-semibold text-sm shadow-lg backdrop-blur-sm" :class="statusClasses">
              <span class="w-2 h-2 rounded-full mr-2 animate-pulse" :class="statusDotClass"></span>
              {{ formatStatus(application.status) }}
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Job & Application Info -->
        <div class="lg:col-span-1 space-y-6">

          <!-- Job Information Card -->
          <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                  <i class="fas fa-briefcase text-white text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Job Information</h2>
              </div>
            </div>
            <div class="p-6">
              <dl class="space-y-5">
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Job Title</dt>
                  <dd class="text-base font-bold text-slate-900">{{ application.post?.name || 'N/A' }}</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Post Abbreviation</dt>
                  <dd class="text-base text-slate-700">{{ application.post?.post_abbreviation || 'N/A' }}</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">BPS Scale</dt>
                  <dd class="text-base font-bold text-teal-700">BPS-{{ application.post?.bps || 'N/A' }}</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Number of Posts</dt>
                  <dd class="text-base text-slate-700">{{ application.post?.number_post || 'N/A' }}</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Age Limit</dt>
                  <dd class="text-base text-slate-700">{{ application.post?.min_age }}-{{ application.post?.max_age }} years</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Gender Requirement</dt>
                  <dd class="text-base text-slate-700">{{ application.post?.post_gender || 'N/A' }}</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                    <i class="fas fa-map-marker-alt text-teal-500 mr-2"></i>
                    Preferred City
                  </dt>
                  <dd class="text-base text-slate-700">{{ application.preferred_city?.name || 'N/A' }}</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                    <i class="fas fa-map-pin text-slate-400 mr-2"></i>
                    Alternative City
                  </dt>
                  <dd class="text-base text-slate-700">
                    {{ application.alternative_city?.name || application.preferred_city?.name || 'Not Selected' }}
                  </dd>
                </div>
              </dl>
            </div>
          </div>

          <!-- Application Summary Card -->
          <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                  <i class="fas fa-file-alt text-white text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Application Summary</h2>
              </div>
            </div>
            <div class="p-6">
              <dl class="space-y-5">
                <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                  <dt class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-2">Application ID</dt>
                  <dd class="text-2xl font-bold text-indigo-900 font-mono">#{{ application.id }}</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                    <i class="fas fa-calendar-plus text-teal-500 mr-2"></i>
                    Applied On
                  </dt>
                  <dd class="text-base text-slate-700">{{ formatDate(application.applied_at) }}</dd>
                </div>
                <div class="group/item hover:bg-slate-50 p-3 rounded-lg transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                    <i class="fas fa-clock text-slate-400 mr-2"></i>
                    Last Updated
                  </dt>
                  <dd class="text-base text-slate-700">{{ formatDate(application.updated_at) }}</dd>
                </div>

                <!-- Fee Status -->
                <div v-if="application.fee_amount > 0" class="p-5 bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border-2 border-amber-200">
                  <dt class="text-xs font-semibold text-amber-700 uppercase tracking-wider mb-2">Application Fee</dt>
                  <dd class="text-3xl font-black bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                    Rs. {{ application.fee_amount.toLocaleString() }}
                  </dd>
                  <span v-if="application.paid_at" class="inline-flex items-center mt-3 px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    Paid on {{ formatDate(application.paid_at) }}
                  </span>
                  <span v-else class="inline-flex items-center mt-3 px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-lg animate-pulse">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Payment Pending
                  </span>
                </div>

                <!-- Rejection Reason -->
                <div v-if="application.rejection_reason" class="p-5 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 rounded-xl">
                  <dt class="font-bold text-red-800 text-base mb-2 flex items-center">
                    <i class="fas fa-times-circle mr-2"></i>
                    Application Rejected
                  </dt>
                  <dd class="text-sm text-red-700 leading-relaxed">{{ application.rejection_reason }}</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>

        <!-- Right Column: Candidate Details -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Personal Information Card -->
          <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-6 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                  <i class="fas fa-user text-white text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Personal Information</h2>
              </div>
            </div>
            <div class="p-6">
              <!-- Profile Photo Section -->
              <div v-if="profile?.photo_path" class="flex justify-center mb-6 pb-6 border-b border-slate-200">
                <div class="relative group/photo">
                  <div class="w-32 h-32 rounded-2xl overflow-hidden border-4 border-white shadow-xl">
                    <img 
                      :src="`/storage/${profile.photo_path}`" 
                      alt="Profile Photo" 
                      class="w-full h-full object-cover"
                      @error="$event.target.src = '/images/default-avatar.png'"
                    />
                  </div>
                  <div class="absolute -bottom-2 -right-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-full p-2 shadow-lg">
                    <i class="fas fa-check text-sm"></i>
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Full Name</dt>
                  <dd class="text-base font-bold text-slate-900">{{ application.user?.name || '—' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Father's Name</dt>
                  <dd class="text-base text-slate-700">{{ profile?.father_name || '—' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">CNIC</dt>
                  <dd class="text-base font-mono font-semibold text-blue-700">{{ profile?.cnic ? formatCNIC(profile.cnic) : '—' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Date of Birth</dt>
                  <dd class="text-base text-slate-700">{{ profile?.date_of_birth ? formatDate(profile.date_of_birth) : '—' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Gender</dt>
                  <dd class="text-base text-slate-700">{{ profile?.gender ? profile.gender.charAt(0).toUpperCase() + profile.gender.slice(1) : '—' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Religion</dt>
                  <dd class="text-base text-slate-700">{{ profile?.religion || '—' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Marital Status</dt>
                  <dd class="text-base text-slate-700">{{ profile?.marital_status ? 'Married' : 'Single' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Disability Status</dt>
                  <dd class="text-base text-slate-700">{{ profile?.disability || 'No' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-blue-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Domicile</dt>
                  <dd class="text-base text-slate-700">{{ profile?.city?.name || '—' }}</dd>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Information Card -->
          <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-violet-600 to-fuchsia-600 px-6 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                  <i class="fas fa-phone text-white text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Contact Information</h2>
              </div>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group/item p-4 rounded-xl hover:bg-violet-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 flex items-center">
                    <i class="fas fa-envelope text-violet-500 mr-2"></i>
                    Email Address
                  </dt>
                  <dd class="text-base font-semibold text-slate-900">{{ application.user?.email || '—' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-violet-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 flex items-center">
                    <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                    WhatsApp Number
                  </dt>
                  <dd class="text-base font-semibold text-slate-900">{{ contact?.phone ? formatPhone(contact.phone) : '—' }}</dd>
                </div>
                <div class="group/item p-4 rounded-xl hover:bg-violet-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 flex items-center">
                    <i class="fas fa-mobile-alt text-violet-500 mr-2"></i>
                    Mobile Number
                  </dt>
                  <dd class="text-base font-semibold text-slate-900">
                    {{ contact?.mobile ? formatPhone(contact.mobile) : (contact?.phone ? 'Same as WhatsApp' : '—') }}
                  </dd>
                </div>
                <div class="md:col-span-2 p-4 rounded-xl hover:bg-violet-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 flex items-center">
                    <i class="fas fa-map-marked-alt text-rose-500 mr-2"></i>
                    Postal Address
                  </dt>
                  <dd class="text-base text-slate-700 whitespace-pre-line leading-relaxed">{{ contact?.postal_address || '—' }}</dd>
                </div>
                <div class="md:col-span-2 p-4 rounded-xl hover:bg-violet-50 transition-colors">
                  <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 flex items-center">
                    <i class="fas fa-home text-indigo-500 mr-2"></i>
                    Permanent Address
                  </dt>
                  <dd class="text-base text-slate-700 whitespace-pre-line leading-relaxed">
                    {{ contact?.permanent_address || (contact?.postal_address ? 'Same as postal address' : '—') }}
                  </dd>
                </div>
              </div>
            </div>
          </div>

          <!-- Academic Qualifications Card -->
          <div class="group bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-500 overflow-hidden border border-white/20">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                  <i class="fas fa-graduation-cap text-white text-lg"></i>
                </div>
                <h2 class="text-xl font-bold text-white">Academic Qualifications</h2>
              </div>
            </div>
            <div class="p-6">
              <div v-if="qualifications.length" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                  <thead>
                    <tr class="bg-gradient-to-r from-slate-50 to-slate-100">
                      <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Degree Level</th>
                      <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Degree Title</th>
                      <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Institute</th>
                      <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Passing Year</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100">
                    <tr v-for="q in qualifications" :key="q.id" class="hover:bg-emerald-50/50 transition-colors">
                      <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-800">
                          {{ q.degree_type?.name || 'Unknown' }}
                        </span>
                      </td>
                      <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ q.degree_name || '—' }}</td>
                      <td class="px-6 py-4 text-sm text-slate-700">{{ q.institute_name || '—' }}</td>
                      <td class="px-6 py-4 text-sm font-semibold text-teal-700">{{ q.passing_year || '—' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div v-else class="p-8 text-center">
                <i class="fas fa-book-open text-slate-300 text-4xl mb-3"></i>
                <p class="text-slate-500 italic">No qualifications added yet</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Enhanced Back Button -->
      <div class="mt-12 text-center">
        <Link
          :href="route('candidate.dashboard')"
          class="group inline-flex items-center px-10 py-4 bg-gradient-to-r from-slate-800 to-slate-900 text-white font-bold text-lg rounded-xl hover:from-slate-900 hover:to-black transition-all duration-300 shadow-xl hover:shadow-2xl"
        >
          <i class="fas fa-arrow-left mr-3 group-hover:-translate-x-2 transition-transform"></i>
          <span>Return to Dashboard</span>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import Candidatelayout from '@/Layouts/Candidatelayout.vue'

defineOptions({ layout: Candidatelayout })

const props = defineProps({
  application: Object,
  breadcrumbs: Array,
  flash: Object
})

const isDownloading = ref(false)
const isPaying = ref(false)

// Computed shortcuts - Direct access to nested data
const profile = computed(() => {
  console.log('Profile data:', props.application?.user?.candidate_profile)
  return props.application?.user?.candidate_profile || null
})

const contact = computed(() => {
  console.log('Contact data:', props.application?.user?.user_contact)
  return props.application?.user?.user_contact || null
})

const qualifications = computed(() => {
  console.log('Qualifications data:', props.application?.user?.qualifications)
  return props.application?.user?.qualifications || []
})

const canMakePayment = computed(() => {
  const status = props.application.status?.toLowerCase()
  return ['approved', 'roll number slip generated', 'fee pending'].includes(status) && !props.application.paid_at
})

// Status styling
const statusClasses = computed(() => {
  const s = (props.application.status || '').toLowerCase()
  if (s.includes('approved') || s.includes('roll') || s.includes('paid')) {
    return 'bg-gradient-to-r from-teal-100 to-emerald-100 text-teal-800 border-2 border-teal-300'
  }
  if (s.includes('pending')) {
    return 'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border-2 border-amber-300'
  }
  if (s.includes('rejected')) {
    return 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border-2 border-red-300'
  }
  return 'bg-gradient-to-r from-slate-100 to-gray-100 text-slate-800 border-2 border-slate-300'
})

const statusDotClass = computed(() => {
  const s = (props.application.status || '').toLowerCase()
  if (s.includes('approved') || s.includes('roll') || s.includes('paid')) return 'bg-teal-500'
  if (s.includes('pending')) return 'bg-amber-500'
  if (s.includes('rejected')) return 'bg-red-500'
  return 'bg-slate-500'
})

// Formatters
const formatDate = (date) => !date ? '—' : new Date(date).toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' })

const formatStatus = (status) => status ? status.split(/[-_]/).map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ') : 'Unknown'

const formatPhone = (num) => {
  if (!num) return 'Not provided'
  const cleaned = num.toString().replace(/\D/g, '')
  if (cleaned.length === 11 && cleaned.startsWith('0')) return `+92 ${cleaned.slice(1, 4)} ${cleaned.slice(4)}`
  if (cleaned.length === 10) return `+92 ${cleaned.slice(0, 3)} ${cleaned.slice(3)}`
  return num
}

const formatCNIC = (cnic) => {
  if (!cnic) return '—'
  const str = cnic.toString()
  return `${str.slice(0,5)}-${str.slice(5,12)}-${str.slice(12)}`
}

// Actions
const downloadApplication = () => {
  isDownloading.value = true
  window.location.href = route('candidate.applications.download', props.application.id)
  setTimeout(() => isDownloading.value = false, 2000)
}

const makePayment = () => {
  if (!canMakePayment.value) return
  isPaying.value = true

  router.post(route('candidate.applications.payment', props.application.id), {}, {
    onSuccess: (page) => {
      if (page.props.redirect_url) {
        window.location.href = page.props.redirect_url
      }
    },
    onError: () => {
      alert('Payment initiation failed. Please try again.')
    },
    onFinish: () => isPaying.value = false
  })
}
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.4s ease;
}

.slide-down-enter-from {
  transform: translateY(-20px);
  opacity: 0;
}

.slide-down-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}

@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-10px);
  }
}

.group:hover .group-hover\:animate-float {
  animation: float 3s ease-in-out infinite;
}
</style>
