<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8 px-4 sm:px-6 lg:px-8">
    <!-- Success Toast -->
    <transition name="slide-fade">
      <div v-if="showSuccessToast" class="fixed top-4 right-4 z-50">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span class="font-medium">{{ successMessage }}</span>
        </div>
      </div>
    </transition>

    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
          <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">My Profile</h1>
            <p class="text-gray-600 flex items-center">
              <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
              Complete your profile to increase your chances
            </p>
          </div>
          <div class="hidden md:block">
            <div class="bg-white rounded-xl shadow-sm px-6 py-4 border border-gray-200">
              <div class="text-sm text-gray-600 mb-1">Profile Completion</div>
              <div class="flex items-center space-x-3">
                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden w-32">
                  <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-500" :style="`width: ${profileCompletion}%`"></div>
                </div>
                <span class="text-lg font-bold text-indigo-600">{{ profileCompletion }}%</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modern Tab Navigation -->
      <div class="mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-2">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            <button
              @click="activeTab = 'profile'"
              :class="[
                'relative px-6 py-4 rounded-xl font-medium text-sm transition-all duration-300',
                activeTab === 'profile'
                  ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30'
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="hidden sm:inline">Profile Details</span>
              </div>
              <div v-if="activeTab === 'profile'" class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg"></div>
            </button>

            <button
              @click="activeTab = 'qualifications'"
              :class="[
                'relative px-6 py-4 rounded-xl font-medium text-sm transition-all duration-300',
                activeTab === 'qualifications'
                  ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30'
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
                <span class="hidden sm:inline">Qualifications</span>
              </div>
              <div v-if="activeTab === 'qualifications'" class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg"></div>
            </button>

            <button
              @click="activeTab = 'ageRelaxation'"
              :class="[
                'relative px-6 py-4 rounded-xl font-medium text-sm transition-all duration-300',
                activeTab === 'ageRelaxation'
                  ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30'
                  : 'text-gray-600 hover:bg-gray-50'
              ]"
            >
              <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
                <span class="hidden sm:inline">Age Relaxation</span>
              </div>
              <div v-if="activeTab === 'ageRelaxation'" class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg"></div>
            </button>
          </div>
        </div>
      </div>

      <!-- Profile Tab Content -->
      <div v-if="activeTab === 'profile'" class="space-y-6 animate-fadeIn">
        <!-- Photo Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-4 sm:px-8 py-6">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Profile Photo
            </h2>
            <p class="text-blue-100 text-sm mt-1">Upload a professional passport-size photo</p>
          </div>

          <div class="px-4 sm:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center gap-8">
              <div class="relative group">
                <div class="w-40 h-40 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden border-4 border-white shadow-xl">
                  <img v-if="photoPreview" :src="photoPreview" alt="Profile" class="w-full h-full object-cover" />
                  <div v-else class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                </div>
                <label class="absolute -bottom-3 -right-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-full p-3 shadow-lg cursor-pointer hover:shadow-xl transition-all duration-300 hover:scale-110">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                  </svg>
                  <input type="file" ref="fileInput" @change="handleFileUpload" class="hidden" accept="image/*" />
                </label>
              </div>

              <div class="flex-1 space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                  <h4 class="font-medium text-blue-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Photo Guidelines
                  </h4>
                  <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Size: 35mm × 45mm (passport size)</li>
                    <li>• Format: JPG, PNG (max 2MB)</li>
                    <li>• Background: Plain white or light color</li>
                    <li>• Face should be clearly visible</li>
                  </ul>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                  <label class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl px-6 py-3 text-center font-medium cursor-pointer hover:shadow-lg transition-all duration-300">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload Photo
                    <input type="file" ref="fileInput" @change="handleFileUpload" class="hidden" accept="image/jpeg,image/jpg,image/png" />
                  </label>
                  <button v-if="form.photo && photoPreview" @click="savePhoto" :disabled="uploadingPhoto" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl px-6 py-3 font-medium hover:shadow-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg v-if="!uploadingPhoto" class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg v-else class="w-5 h-5 inline-block mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ uploadingPhoto ? 'Saving...' : 'Save Photo' }}
                  </button>
                  <button v-if="photoPreview" @click="removePhoto" class="bg-red-50 text-red-600 rounded-xl px-6 py-3 font-medium hover:bg-red-100 transition-all duration-300">
                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-4 sm:px-8 py-6">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              Personal Information
            </h2>
            <p class="text-emerald-100 text-sm mt-1">Your basic details and contact information</p>
          </div>

          <div class="px-4 sm:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Full Name -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Full Name *</label>
                <input
                  type="text"
                  v-model="form.name"
                  disabled
                  class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                />
                <p v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</p>
              </div>

              <!-- Father's Name -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Father's Name *</label>
                <input
                  type="text"
                  v-model="form.fatherName"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                />
                <p v-if="form.errors.fatherName" class="text-sm text-red-600">{{ form.errors.fatherName }}</p>
              </div>

              <!-- Email -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Email Address *</label>
                <input
                  type="email"
                  v-model="form.email"
                  disabled
                  class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                />
                <p v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</p>
              </div>

              <!-- Mobile -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Mobile Number *</label>
                <input
                  type="tel"
                  v-model="form.mobile"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                />
                <p v-if="form.errors.mobile" class="text-sm text-red-600">{{ form.errors.mobile }}</p>
              </div>

              <!-- WhatsApp -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">WhatsApp Number</label>
                <input
                  type="tel"
                  v-model="form.phone"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                />
                <p v-if="form.errors.phone" class="text-sm text-red-600">{{ form.errors.phone }}</p>
              </div>

              <!-- CNIC -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">CNIC *</label>
                <input
                  type="text"
                  v-model="form.cnic"
                  @input="handleCnicInput"
                  placeholder="XXXXXXXXXXXXX"
                  maxlength="13"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                />
                <p v-if="form.errors.cnic" class="text-sm text-red-600">{{ form.errors.cnic }}</p>
              </div>

              <!-- Date of Birth -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Date of Birth *</label>
                <input
                  type="date"
                  v-model="form.dateOfBirth"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                />
                <p v-if="form.errors.dateOfBirth" class="text-sm text-red-600">{{ form.errors.dateOfBirth }}</p>
              </div>

              <!-- Gender -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Gender *</label>
                <div class="flex gap-6 pt-1">
                  <label class="flex items-center space-x-3 cursor-pointer group">
                    <input
                      type="radio"
                      v-model="form.gender"
                      value="Male"
                      class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500"
                    />
                    <span class="text-gray-700 group-hover:text-blue-600 transition-colors">Male</span>
                  </label>
                  <label class="flex items-center space-x-3 cursor-pointer group">
                    <input
                      type="radio"
                      v-model="form.gender"
                      value="Female"
                      class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500"
                    />
                    <span class="text-gray-700 group-hover:text-blue-600 transition-colors">Female</span>
                  </label>
                </div>
                <p v-if="form.errors.gender" class="text-sm text-red-600">{{ form.errors.gender }}</p>
              </div>

             <!-- Religion -->
<div class="space-y-2">
  <label class="block text-sm font-semibold text-gray-700">Religion <span class="text-red-600">*</span></label>

  <select
    v-model="form.religion"
    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 select2"
    required
  >
    <option value="" disabled selected>Select your religion</option>
    <option value="Islam">Islam</option>
    <option value="Ahmadi">Ahmadi</option>
    <option value="Christianity">Christianity</option>
    <option value="Hinduism">Hinduism</option>
    <option value="Sikhism">Sikhism</option>
    <option value="Buddhism">Buddhism</option>
    <option value="Others">Others</option>
  </select>

  <!-- Error message -->
  <p v-if="form.errors.religion" class="text-sm text-red-600">{{ form.errors.religion }}</p>
</div>




              <!-- Marital Status -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Marital Status *</label>
                <select
                  v-model="form.maritalStatus"
                  @change="handleMaritalStatusChange"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                >
                  <option value="Single">Single</option>
                  <option value="Married">Married</option>
                  <option value="Divorced">Divorced</option>
                </select>
                <p v-if="form.errors.maritalStatus" class="text-sm text-red-600">{{ form.errors.maritalStatus }}</p>
              </div>

              <!-- Disability Status -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Disability Status *</label>
                <select
                  v-model="form.disabilityStatus"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                >
                  <option value="">Select Status</option>
                  <option value="No">No</option>
                  <option value="Yes">Yes</option>
                </select>
                <p v-if="form.errors.disabilityStatus" class="text-sm text-red-600">{{ form.errors.disabilityStatus }}</p>
              </div>
            </div>

            <!-- Husband's Domicile Section -->
            <div v-if="form.gender === 'Female' && form.maritalStatus === 'Married'" class="mt-8 pt-8 border-t border-gray-200">
              <div class="bg-gradient-to-r from-pink-50 to-purple-50 border border-pink-200 rounded-xl p-6 mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-4">
                  Are you applying through your husband's domicile? *
                </label>
                <div class="flex gap-6">
                  <label class="flex items-center space-x-3 cursor-pointer group">
                    <input
                      type="radio"
                      v-model="form.useHusbandDomicile"
                      value="Yes"
                      class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500"
                    />
                    <span class="text-gray-700 group-hover:text-blue-600 transition-colors">Yes</span>
                  </label>
                  <label class="flex items-center space-x-3 cursor-pointer group">
                    <input
                      type="radio"
                      v-model="form.useHusbandDomicile"
                      value="No"
                      class="w-5 h-5 text-blue-600 focus:ring-2 focus:ring-blue-500"
                    />
                    <span class="text-gray-700 group-hover:text-blue-600 transition-colors">No</span>
                  </label>
                </div>
                <p v-if="form.errors.useHusbandDomicile" class="text-sm text-red-600 mt-2">{{ form.errors.useHusbandDomicile }}</p>
              </div>

              <!-- Husband's Details -->
              <div v-if="form.useHusbandDomicile === 'Yes'" class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn">
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Husband's Name *</label>
                  <input
                    type="text"
                    v-model="form.husbandName"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="form.errors.husbandName" class="text-sm text-red-600">{{ form.errors.husbandName }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Husband's CNIC *</label>
                  <input
                    type="text"
                    v-model="form.husbandCnic"
                    @input="handleHusbandCnicInput"
                    placeholder="XXXXXXXXXXXXX"
                    maxlength="13"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="form.errors.husbandCnic" class="text-sm text-red-600">{{ form.errors.husbandCnic }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Husband's Province *</label>
                  <select
                    v-model="form.husbandProvince"
                    @change="handleHusbandProvinceChange"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  >
                    <option value="">Select Province</option>
                    <option v-for="province in provinces" :key="province.id" :value="province.id">
                      {{ province.name }}
                    </option>
                  </select>
                  <p v-if="form.errors.husbandProvince" class="text-sm text-red-600">{{ form.errors.husbandProvince }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Husband's District *</label>
                  <select
                    v-model="form.husbandDistrict"
                    :disabled="!form.husbandProvince"
                    @change="handleHusbandDistrictChange"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 disabled:bg-gray-50"
                  >
                    <option value="">Select District</option>
                    <option v-for="district in husbandDistricts" :key="district.id" :value="district.id">
                      {{ district.name }}
                    </option>
                  </select>
                  <p v-if="form.errors.husbandDistrict" class="text-sm text-red-600">{{ form.errors.husbandDistrict }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Husband's City *</label>
                  <select
                    v-model="form.husbandCity"
                    :disabled="!form.husbandDistrict"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 disabled:bg-gray-50"
                  >
                    <option value="">Select City</option>
                    <option v-for="city in husbandCities" :key="city.id" :value="city.id">
                      {{ city.name }}
                    </option>
                  </select>
                  <p v-if="form.errors.husbandCity" class="text-sm text-red-600">{{ form.errors.husbandCity }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-4 sm:px-8 py-6">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Address Information
            </h2>
            <p class="text-purple-100 text-sm mt-1">Your residential address details</p>
          </div>

          <div class="px-4 sm:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
              <!-- Province -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Province *</label>
                <select
                  v-model="form.province"
                  @change="handleProvinceChange"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                >
                  <option value="">Select Province</option>
                  <option v-for="province in provinces" :key="province.id" :value="province.id">
                    {{ province.name }}
                  </option>
                </select>
                <p v-if="form.errors.province" class="text-sm text-red-600">{{ form.errors.province }}</p>
              </div>

              <!-- District -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">District *</label>
                <select
                  v-model="form.district"
                  :disabled="!form.province"
                  @change="handleDistrictChange"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 disabled:bg-gray-50"
                >
                  <option value="">Select District</option>
                  <option v-for="district in districts" :key="district.id" :value="district.id">
                    {{ district.name }}
                  </option>
                </select>
                <p v-if="form.errors.district" class="text-sm text-red-600">{{ form.errors.district }}</p>
              </div>

              <!-- City -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">City *</label>
                <select
                  v-model="form.city"
                  :disabled="!form.district"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 disabled:bg-gray-50"
                >
                  <option value="">Select City</option>
                  <option v-for="city in cities" :key="city.id" :value="city.id">
                    {{ city.name }}
                  </option>
                </select>
                <p v-if="form.errors.city" class="text-sm text-red-600">{{ form.errors.city }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Permanent Address -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Permanent Address *</label>
                <textarea
                  v-model="form.permanentAddress"
                  rows="3"
                  placeholder="Enter your permanent address"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 resize-none"
                ></textarea>
                <p v-if="form.errors.permanentAddress" class="text-sm text-red-600">{{ form.errors.permanentAddress }}</p>
              </div>

              <!-- Postal Address -->
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Postal Address *</label>
                <textarea
                  v-model="form.postalAddress"
                  rows="3"
                  placeholder="Enter your postal address"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 resize-none"
                ></textarea>
                <p v-if="form.errors.postalAddress" class="text-sm text-red-600">{{ form.errors.postalAddress }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col-reverse sm:flex-row justify-between gap-4">
          <button
            @click="resetForm"
            type="button"
            class="w-full sm:w-auto px-8 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-300"
          >
            Cancel
          </button>
          <div class="flex flex-col-reverse sm:flex-row gap-4">
            <button
                @click="activeTab = 'qualifications'"
                type="button"
                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 flex items-center justify-center"
              >
                Next
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            <button
              @click="saveAndNext('qualifications')"
              type="button"
              class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 flex items-center justify-center"
            >
              Save & Next
              <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Qualifications Tab Content -->
      <div v-if="activeTab === 'qualifications'" class="space-y-6 animate-fadeIn">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-4 sm:px-8 py-6">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
              </svg>
              Educational Qualifications
            </h2>
            <p class="text-amber-100 text-sm mt-1">Add your academic credentials and certifications</p>
          </div>

          <div class="px-8 py-8">
            <!-- Add Qualification Form -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-8">
              <h3 class="text-lg font-semibold text-gray-900 mb-6">
                {{ editingQualification ? 'Edit Qualification' : 'Add New Qualification' }}
              </h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Degree Type -->
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Degree Type *</label>
                  <select
                    v-model="qualificationForm.degree"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  >
                    <option value="">Select Degree</option>
                    <option v-for="type in availableTypes" :key="type.id" :value="type.id">
                      {{ type.name }}
                    </option>
                  </select>
                  <p v-if="qualificationFormErrors.degree" class="text-sm text-red-600">{{ qualificationFormErrors.degree }}</p>
                </div>

                <!-- Field of Study -->
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Field of Study *</label>
                  <input
                    type="text"
                    v-model="qualificationForm.field"
                    placeholder="e.g., Computer Science"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="qualificationFormErrors.field" class="text-sm text-red-600">{{ qualificationFormErrors.field }}</p>
                </div>

                <!-- Institution -->
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Institution Name *</label>
                  <input
                    type="text"
                    v-model="qualificationForm.institution"
                    placeholder="Enter institution name"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="qualificationFormErrors.institution" class="text-sm text-red-600">{{ qualificationFormErrors.institution }}</p>
                </div>

                <!-- Year of Completion -->
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Year of Completion *</label>
                  <input
                    type="number"
                    v-model="qualificationForm.year"
                    :min="1900"
                    :max="new Date().getFullYear()"
                    placeholder="2024"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="qualificationFormErrors.year" class="text-sm text-red-600">{{ qualificationFormErrors.year }}</p>
                </div>
              </div>

              <div class="mt-6 flex justify-end gap-3">
                <button
                  v-if="editingQualification"
                  @click="cancelEdit"
                  type="button"
                  class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-white transition-all duration-300"
                >
                  Cancel
                </button>
                <button
                  @click="saveQualification"
                  type="button"
                  class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300"
                >
                  {{ editingQualification ? 'Update' : '+ Add' }} Qualification
                </button>
              </div>
            </div>

            <!-- Qualifications List -->
            <div v-if="qualifications.length > 0" class="space-y-4">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Qualifications</h3>

              <div
                v-for="(qualification, index) in qualifications"
                :key="qualification.id || index"
                class="bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-300"
              >
                <div class="flex flex-col sm:flex-row items-start justify-between gap-4">
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                      <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                      </div>
                      <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ getDegreeName(qualification.degree) }}</h4>
                        <p class="text-sm text-gray-600">{{ qualification.field }}</p>
                      </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 ml-15">
                      <div>
                        <p class="text-xs text-gray-500 mb-1">Institution</p>
                        <p class="text-sm font-medium text-gray-700">{{ qualification.institution }}</p>
                      </div>
                      <div>
                        <p class="text-xs text-gray-500 mb-1">Year</p>
                        <p class="text-sm font-medium text-gray-700">{{ qualification.year }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="flex gap-2 w-full sm:w-auto justify-end sm:ml-4 mt-2 sm:mt-0">
                    <button
                      @click="editQualification(qualification, index)"
                      class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-300"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      @click="deleteQualification(qualification, index)"
                      class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-300"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
              </div>
              <h3 class="text-lg font-medium text-gray-900 mb-2">No qualifications added</h3>
              <p class="text-sm text-gray-500">Get started by adding your first qualification using the form above.</p>
            </div>
          </div>

          <!-- Navigation Buttons -->
          <div class="px-4 sm:px-8 py-6 bg-gray-50 flex flex-col-reverse sm:flex-row justify-between gap-4">
            <button
              @click="activeTab = 'profile'"
              type="button"
              class="w-full sm:w-auto px-8 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-white transition-all duration-300 flex items-center justify-center"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Previous
            </button>
            <div class="flex flex-col sm:flex-row gap-4">
              <button
                @click="activeTab = 'ageRelaxation'"
                type="button"
                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 flex items-center justify-center"
              >
                Next
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Age Relaxation Tab Content -->
      <div v-if="activeTab === 'ageRelaxation'" class="space-y-6 animate-fadeIn">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
          <div class="bg-gradient-to-r from-rose-500 to-red-600 px-4 sm:px-8 py-6">
            <h2 class="text-xl font-semibold text-white flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
              </svg>
              Age Relaxation Claims | عمر کی حد میں رعایت
            </h2>
            <p class="text-rose-100 text-sm mt-1">Select applicable categories for age relaxation benefits</p>
          </div>

          <div class="px-4 sm:px-8 py-8 space-y-6">
            <!-- Scheduled Castes -->
            <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-300">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h4 class="text-lg font-semibold text-gray-900 mb-2">Scheduled Castes / Buddhist Community</h4>
                  <p class="text-sm text-gray-600">For candidates belonging to scheduled castes, Buddhist community, recognized tribes, AJK, Sindh (Rural), or Balochistan domiciled</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                  <input
                    type="checkbox"
                    v-model="ageRelaxationForm.relax_schedule_caste"
                    @change="toggleScheduleCaste"
                    class="sr-only peer"
                  />
                  <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-indigo-600"></div>
                </label>
              </div>
            </div>

            <!-- Retired Armed Forces -->
            <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-300">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h4 class="text-lg font-semibold text-gray-900 mb-2">Retired Armed Forces Personnel</h4>
                  <p class="text-sm text-gray-600">Released or retired officers from Army, Navy, or Air Force</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                  <input
                    type="checkbox"
                    v-model="ageRelaxationForm.relax_retired"
                    @change="toggleRetiredFields"
                    class="sr-only peer"
                  />
                  <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-indigo-600"></div>
                </label>
              </div>

              <div v-if="ageRelaxationForm.relax_retired" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 animate-fadeIn">
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Retired From *</label>
                  <select
                    v-model="ageRelaxationForm.relax_retired_from"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  >
                    <option value="">Select Force</option>
                    <option value="army">Army</option>
                    <option value="navy">Navy</option>
                    <option value="air_force">Air Force</option>
                  </select>
                  <p v-if="ageRelaxationForm.errors.relax_retired_from" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_retired_from }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Rank *</label>
                  <input
                    type="text"
                    v-model="ageRelaxationForm.relax_retired_position"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.relax_retired_position" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_retired_position }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Date of Appointment *</label>
                  <input
                    type="date"
                    v-model="ageRelaxationForm.relax_retired_appoint"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.relax_retired_appoint" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_retired_appoint }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Date of Retirement *</label>
                  <input
                    type="date"
                    v-model="ageRelaxationForm.relax_retired_retired"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.relax_retired_retired" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_retired_retired }}</p>
                </div>
              </div>
            </div>

            <!-- Disabled Person -->
            <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-300">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h4 class="text-lg font-semibold text-gray-900 mb-2">Disabled Person</h4>
                  <p class="text-sm text-gray-600">For candidates with physical or mental disabilities (Nature of disability must be mentioned)</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                  <input
                    type="checkbox"
                    v-model="ageRelaxationForm.relax_disable"
                    @change="toggleDisabledFields"
                    class="sr-only peer"
                  />
                  <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-indigo-600"></div>
                </label>
              </div>

              <div v-if="ageRelaxationForm.relax_disable" class="mt-4 pt-4 border-t border-gray-200 animate-fadeIn">
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Nature of Disability *</label>
                  <select
                    v-model="ageRelaxationForm.relax_disabled_nature"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  >
                    <option value="">Select Disability</option>
                    <option value="Leg/Arm Paralyze">Leg/Arm Paralyze</option>
                    <option value="Visual Defect">Visual Defect</option>
                    <option value="Mental/Cognitive">Mental/Cognitive</option>
                    <option value="Deaf/Dumb">Deaf/Dumb</option>
                    <option value="Other">Other</option>
                  </select>
                  <p v-if="ageRelaxationForm.errors.relax_disabled_nature" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_disabled_nature }}</p>
                </div>
              </div>
            </div>

            <!-- Widow/Widower -->
            <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-300">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h4 class="text-lg font-semibold text-gray-900 mb-2">Widow/Widower of Government Employee</h4>
                  <p class="text-sm text-gray-600">For candidates whose spouse died during government service (on or after 01-07-2005)</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                  <input
                    type="checkbox"
                    v-model="ageRelaxationForm.relax_widow"
                    @change="toggleWidowFields"
                    class="sr-only peer"
                  />
                  <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-indigo-600"></div>
                </label>
              </div>

              <div v-if="ageRelaxationForm.relax_widow" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 animate-fadeIn">
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Name of Deceased Employee *</label>
                  <input
                    type="text"
                    v-model="ageRelaxationForm.relax_name_employ"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.relax_name_employ" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_name_employ }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Designation and BPS *</label>
                  <input
                    type="text"
                    v-model="ageRelaxationForm.relax_designation"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.relax_designation" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_designation }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Department *</label>
                  <input
                    type="text"
                    v-model="ageRelaxationForm.relax_department"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.relax_department" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_department }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Date of Death *</label>
                  <input
                    type="date"
                    v-model="ageRelaxationForm.relax_date_death"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.relax_date_death" class="text-sm text-red-600">{{ ageRelaxationForm.errors.relax_date_death }}</p>
                </div>
              </div>
            </div>

            <!-- Government Employee -->
            <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all duration-300">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h4 class="text-lg font-semibold text-gray-900 mb-2">Contract Government Employee</h4>
                  <p class="text-sm text-gray-600">Currently serving on contract basis in government department</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer ml-4">
                  <input
                    type="checkbox"
                    v-model="ageRelaxationForm.gov"
                    @change="toggleGovFields"
                    class="sr-only peer"
                  />
                  <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-blue-500 peer-checked:to-indigo-600"></div>
                </label>
              </div>

              <div v-if="ageRelaxationForm.gov" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 animate-fadeIn">
                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Department Name *</label>
                  <input
                    type="text"
                    v-model="ageRelaxationForm.gov_name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.gov_name" class="text-sm text-red-600">{{ ageRelaxationForm.errors.gov_name }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Designation *</label>
                  <input
                    type="text"
                    v-model="ageRelaxationForm.gov_designation"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.gov_designation" class="text-sm text-red-600">{{ ageRelaxationForm.errors.gov_designation }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Basic Pay Scale *</label>
                  <input
                    type="text"
                    v-model="ageRelaxationForm.gov_basic_pay"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.gov_basic_pay" class="text-sm text-red-600">{{ ageRelaxationForm.errors.gov_basic_pay }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Appointment Date *</label>
                  <input
                    type="date"
                    v-model="ageRelaxationForm.gov_appoint_date"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.gov_appoint_date" class="text-sm text-red-600">{{ ageRelaxationForm.errors.gov_appoint_date }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Till Registration End Date</label>
                  <input
                    type="text"
                    v-model="ageRelaxationForm.gov_retire_date"
                    readonly
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl transition-all duration-300"
                  />
                  <p v-if="ageRelaxationForm.errors.gov_retire_date" class="text-sm text-red-600">{{ ageRelaxationForm.errors.gov_retire_date }}</p>
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-semibold text-gray-700">Appointment Nature *</label>
                  <select
                    v-model="ageRelaxationForm.gov_appoint_nature"
                    class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                  >
                    <option value="">Select...</option>
                    <option value="contract">Contract</option>
                  </select>
                  <p v-if="ageRelaxationForm.errors.gov_appoint_nature" class="text-sm text-red-600">{{ ageRelaxationForm.errors.gov_appoint_nature }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="px-4 sm:px-8 py-6 bg-gray-50 flex flex-col-reverse sm:flex-row justify-between gap-4">
            <button
              @click="activeTab = 'qualifications'"
              type="button"
              class="w-full sm:w-auto px-8 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-white transition-all duration-300 flex items-center justify-center"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Previous
            </button>
            <div class="flex flex-col sm:flex-row gap-4">
              <button
                @click="skipToDashboard"
                type="button"
                class="w-full sm:w-auto px-8 py-3 border border-gray-300 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all duration-300 flex items-center justify-center"
              >
                Skip to Dashboard
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </button>
              <button
                @click="saveAgeRelaxationForm"
                type="button"
                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 flex items-center justify-center"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save
              </button>
              <button
                @click="saveAndGoToDashboard"
                type="button"
                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-medium hover:shadow-lg transition-all duration-300 flex items-center justify-center"
              >
                Save & Go to Dashboard
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import axios from "axios";
import Candidatelayout from "@/Layouts/Candidatelayout.vue";
import Swal from 'sweetalert2';

// Reactive variables
const activeTab = ref("profile");
const fileInput = ref(null);
const photoPreview = ref(null);
const uploadingPhoto = ref(false);
const qualifications = ref([]);
const districts = ref([]);
const cities = ref([]);
const husbandDistricts = ref([]);
const husbandCities = ref([]);
const showSuccessToast = ref(false);
const successMessage = ref("");




// Access props
const {
  userDetails,
  provinces,
  qualification_types,
  qualificationData,
  ageRelaxation,
} = usePage().props;

// Initialize profile form
const form = useForm({
  user_id: userDetails?.id || "",
  name: userDetails?.name || "",
  email: userDetails?.email || "",
  fatherName: userDetails?.candidate_profile?.father_name || "",
  gender: userDetails?.candidate_profile?.gender || "",
  dateOfBirth: userDetails?.candidate_profile?.date_of_birth || "",
  city: userDetails?.candidate_profile?.city?.id || "",
  cnic: userDetails?.candidate_profile?.cnic || "",
  mobile: userDetails?.user_contact?.mobile || "",
  phone: userDetails?.user_contact?.phone || "",
  permanentAddress: userDetails?.user_contact?.postal_address || "",
  postalAddress: userDetails?.user_contact?.postal_address || "",
  disabilityStatus: userDetails?.candidate_profile?.disability || "",
  religion: userDetails?.candidate_profile?.religion || "",
  maritalStatus: userDetails?.candidate_profile?.marital_status ? "Married" : "Single",
  useHusbandDomicile: userDetails?.user_spouses?.useHusbandDomicile || "",
  husbandName: userDetails?.user_spouses?.spouse_name || "",
  husbandCnic: userDetails?.user_spouses?.spouse_cnic || "",
  husbandProvince: userDetails?.user_spouses?.[0]?.city?.district?.province?.id || "",
  husbandDistrict: userDetails?.user_spouses?.[0]?.city?.district?.id || "",
  husbandCity: userDetails?.user_spouses?.[0]?.city_id || "",
  province: userDetails?.candidate_profile?.city?.district?.province?.id || "",
  district: userDetails?.candidate_profile?.city?.district?.id || "",
  photo: null,
});

const profileCompletion = computed(() => {
  let score = 0;
  
  // 1. Photo (10%)
  if (photoPreview.value || userDetails?.candidate_profile?.photo_path) {
    score += 10;
  }
  
  // 2. Qualifications (20%)
  if (qualifications.value.length > 0) {
    score += 20;
  }
  
  // 3. Personal & Contact Info (70%)
  const requiredFields = [
    form.name,
    form.fatherName,
    form.cnic,
    form.dateOfBirth,
    form.gender,
    form.religion,
    form.maritalStatus,
    form.mobile,
    form.postalAddress,
    form.permanentAddress,
    form.province,
    form.district,
    form.city
  ];
  
  const filledCount = requiredFields.filter(field => {
    return field !== null && field !== undefined && field.toString().trim() !== '';
  }).length;
  
  if (requiredFields.length > 0) {
    score += (filledCount / requiredFields.length) * 70;
  }
  
  return Math.round(score);
});

const usedDegreeIds = computed(() => new Set(qualifications.value.map(q => q.degree).filter(Boolean)));
const availableTypes = computed(() => (qualification_types || []).filter(t => !usedDegreeIds.value.has(t.id) || qualificationForm.value.degree === t.id));


// Initialize qualifications from database
onMounted(() => {
  const path = userDetails?.candidate_profile?.photo_path;
  if (path) {
    photoPreview.value = `/storage/${path}`;
  }
  if (qualificationData?.length > 0) {
    qualifications.value = qualificationData.map((qual) => ({
      id: qual.id || null,
      degree: qual.degree_type_id || "",
      field: qual.degree_name || "",
      institution: qual.institute_name || "",
      year: qual.passing_year || "",
    }));
  }
  loadUserAddress();
  if (userDetails?.user_spouses?.length) {
    loadHusbandAddress();
  }
});

// Load user address districts and cities
const loadUserAddress = async () => {
  if (form.province) {
    try {
      const response = await axios.get(route("districts.fetch", { province_id: form.province }));
      districts.value = response.data.districts;
      if (form.district) {
        const cityResponse = await axios.get(route("cities.fetch", { district_id: form.district }));
        cities.value = cityResponse.data.cities;
      }
    } catch (error) {
      console.error("Error loading user address:", error);
    }
  }
};

// Load husband address districts and cities
const loadHusbandAddress = async () => {
  if (form.husbandProvince) {
    try {
      const response = await axios.get(route("districts.fetch", { province_id: form.husbandProvince }));
      husbandDistricts.value = response.data.districts;
      if (form.husbandDistrict) {
        const cityResponse = await axios.get(route("cities.fetch", { district_id: form.husbandDistrict }));
        husbandCities.value = cityResponse.data.cities;
      }
    } catch (error) {
      console.error("Error loading husband address:", error);
    }
  }
};

// Watch for changes to province and district to load options
watch([() => form.province, () => form.district], async () => {
  await loadUserAddress();
});
watch([() => form.husbandProvince, () => form.husbandDistrict], async () => {
  await loadHusbandAddress();
});

// Qualifications form
const qualificationForm = ref({
  id: null,
  degree: "",
  field: "",
  institution: "",
  year: "",
});
const qualificationFormErrors = ref({});
const editingQualification = ref(false);
const editingIndex = ref(null);

// Get degree name by ID
const getDegreeName = (degreeId) => {
  const degree = qualification_types.find((type) => type.id == degreeId);
  return degree ? degree.name : "Unknown";
};

// Reset qualification form
const resetQualificationForm = () => {
  qualificationForm.value = {
    id: null,
    degree: "",
    field: "",
    institution: "",
    year: "",
  };
  qualificationFormErrors.value = {};
  editingQualification.value = false;
  editingIndex.value = null;
};

// Validate qualification form
const validateQualificationForm = () => {
  const errors = {};
  if (!qualificationForm.value.degree) {
    errors.degree = "Degree is required";
  }
  if (!qualificationForm.value.field) {
    errors.field = "Field of study is required";
  }
  if (!qualificationForm.value.institution) {
    errors.institution = "Institution is required";
  }
  if (!qualificationForm.value.year) {
    errors.year = "Year of completion is required";
  } else if (
    qualificationForm.value.year < 1900 ||
    qualificationForm.value.year > new Date().getFullYear()
  ) {
    errors.year = "Please enter a valid year";
  }
  qualificationFormErrors.value = errors;
  return Object.keys(errors).length === 0;
};

// Save qualification (Add or Update)
const saveQualification = () => {
  if (!validateQualificationForm()) {
    return;
  }
  const qualificationData = {
    id: qualificationForm.value.id,
    degree_type_id: qualificationForm.value.degree,
    degree_name: qualificationForm.value.field,
    institute_name: qualificationForm.value.institution,
    passing_year: qualificationForm.value.year,
  };
  const formData = useForm(qualificationData);
  formData.post(route("candidate.qualification.store"), {
    preserveScroll: true,
    onSuccess: (page) => {
      const newQualification = {
        id: qualificationForm.value.id,
        degree: qualificationForm.value.degree,
        field: qualificationForm.value.field,
        institution: qualificationForm.value.institution,
        year: qualificationForm.value.year,
      };
      if (editingQualification.value) {
        qualifications.value[editingIndex.value] = newQualification;
      } else {
        qualifications.value.push(newQualification);
      }
      resetQualificationForm();
      showSuccessMessage(editingQualification.value ? "Qualification updated successfully!" : "Qualification added successfully!");
    },
    onError: (errors) => {
      console.error("Validation errors:", errors);
      qualificationFormErrors.value = errors;
    },
  });
};

// Edit qualification
const editQualification = (qualification, index) => {
  qualificationForm.value = {
    id: qualification.id,
    degree: qualification.degree,
    field: qualification.field,
    institution: qualification.institution,
    year: qualification.year,
  };
  editingQualification.value = true;
  editingIndex.value = index;
  qualificationFormErrors.value = {};
};

// Cancel edit
const cancelEdit = () => {
  resetQualificationForm();
};

// Delete qualification
const deleteQualification = (qualification, index) => {
  if (confirm("Are you sure you want to delete this qualification?")) {
    if (qualification.id) {
      const formData = useForm({});
      formData.delete(route("candidate.qualification.destroy", qualification.id), {
        preserveScroll: true,
        onSuccess: () => {
          qualifications.value.splice(index, 1);
          showSuccessMessage("Qualification deleted successfully!");
        },
        onError: (errors) => {
          console.error("Delete error:", errors);
        },
      });
    } else {
      qualifications.value.splice(index, 1);
    }
  }
};

// CNIC Input Handlers
const handleCnicInput = (e) => {
  // Remove any non-numeric characters
  let value = e.target.value.replace(/\D/g, '');

  // Limit to 13 digits
  if (value.length > 13) {
    value = value.slice(0, 13);
  }

  form.cnic = value;

  // Clear error if CNIC is now valid
  if (value.length === 13) {
    form.errors.cnic = null;
    delete form.errors.cnic;
  } else if (value.length > 0 && value.length < 13) {
    form.errors.cnic = "CNIC must be 13 digits";
  } else {
    form.errors.cnic = null;
    delete form.errors.cnic;
  }
};

const handleHusbandCnicInput = (e) => {
  // Remove any non-numeric characters
  let value = e.target.value.replace(/\D/g, '');

  // Limit to 13 digits
  if (value.length > 13) {
    value = value.slice(0, 13);
  }

  form.husbandCnic = value;

  // Clear error if CNIC is now valid
  if (value.length === 13) {
    form.errors.husbandCnic = null;
    delete form.errors.husbandCnic;
  } else if (value.length > 0 && value.length < 13) {
    form.errors.husbandCnic = "Husband's CNIC must be 13 digits";
  } else {
    form.errors.husbandCnic = null;
    delete form.errors.husbandCnic;
  }
};

// Profile methods
const openFilePicker = () => {
  fileInput.value.click();
};

const handleFileUpload = (e) => {
  const file = e.target.files[0];
  if (file) {
    // Validate file type
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!validTypes.includes(file.type)) {
      alert('Please upload a valid image file (JPG or PNG)');
      e.target.value = '';
      return;
    }

    // Validate file size (max 2MB)
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    if (file.size > maxSize) {
      alert('File size must be less than 2MB');
      e.target.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = (event) => {
      photoPreview.value = event.target.result;
    };
    reader.readAsDataURL(file);
    form.photo = file;
  }
};

const removePhoto = () => {
  photoPreview.value = null;
  fileInput.value.value = "";
  form.photo = null;
};



// Save photo separately
const savePhoto = () => {
  if (!form.photo) {
    Swal.fire({
      icon: 'warning',
      title: 'No Photo Selected',
      text: 'Please select a photo first',
      confirmButtonColor: '#3085d6',
    });
    return;
  }

  // Validate required fields


  uploadingPhoto.value = true;

  // Create a temporary form with current form values
  const photoForm = useForm({
    photo: form.photo,
    user_id: form.user_id,
    name: form.name,
    email: form.email,
    fatherName: form.fatherName,
    gender: form.gender,
    dateOfBirth: form.dateOfBirth,
    city: form.city,
    cnic: form.cnic,
    mobile: form.mobile,
    phone: form.phone,
    permanentAddress: form.permanentAddress,
    postalAddress: form.postalAddress,
    disabilityStatus: form.disabilityStatus,
    religion: form.religion,
    maritalStatus: form.maritalStatus,
    province: form.province,
    district: form.district,
    useHusbandDomicile: form.useHusbandDomicile || '',
    husbandName: form.husbandName || '',
    husbandCnic: form.husbandCnic || '',
    husbandProvince: form.husbandProvince || '',
    husbandDistrict: form.husbandDistrict || '',
    husbandCity: form.husbandCity || '',
  });

  photoForm.post(route('candidate.profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      showSuccessMessage('Photo uploaded successfully!');
      uploadingPhoto.value = false;

      // Reset the file input but keep the preview
      form.photo = null;
      if (fileInput.value) {
        fileInput.value.value = '';
      }
    },
    onError: (errors) => {
      console.error('Error uploading photo:', errors);

      // Show specific error messages
      if (errors.photo) {
        Swal.fire({
          icon: 'error',
          title: 'Upload Failed',
          text: `Photo upload failed: ${errors.photo}`,
          confirmButtonColor: '#d33',
        });
      } else {
        const errorList = Object.entries(errors).map(([field, message]) => `<li class="text-left">${message}</li>`).join('');
        Swal.fire({
          icon: 'error',
          title: 'Validation Errors',
          html: `<div class="text-left"><p class="mb-2">Please fix the following errors:</p><ul class="list-disc pl-5 text-sm text-red-600 space-y-1">${errorList}</ul></div>`,
          confirmButtonColor: '#d33',
        });
      }

      uploadingPhoto.value = false;
    },
  });
};

const resetForm = () => {
  form.reset();
  husbandDistricts.value = [];
  husbandCities.value = [];
  districts.value = [];
  cities.value = [];
};


const showSuccessMessage = (message) => {
  successMessage.value = message;
  showSuccessToast.value = true;
  setTimeout(() => {
    showSuccessToast.value = false;
  }, 3000);
};

const saveForm = () => {
  if (
    form.gender === "Female" &&
    form.maritalStatus === "Married" &&
    form.useHusbandDomicile === "Yes"
  ) {
    if (!form.husbandName) {
      form.errors.husbandName = "Husband's name is required";
    }
    if (!form.husbandCnic || form.husbandCnic.length !== 13) {
      form.errors.husbandCnic = "Husband's CNIC must be 13 digits";
    }
    if (!form.husbandProvince) {
      form.errors.husbandProvince = "Husband's province is required";
    }
    if (!form.husbandDistrict) {
      form.errors.husbandDistrict = "Husband's district is required";
    }
    if (!form.husbandCity) {
      form.errors.husbandCity = "Husband's city is required";
    }
    if (
      form.errors.husbandName ||
      form.errors.husbandCnic ||
      form.errors.husbandProvince ||
      form.errors.husbandDistrict ||
      form.errors.husbandCity
    ) {
      return;
    }
  }
  form.post(route("candidate.profile.update"), {
    preserveScroll: true,
    onSuccess: () => {
      showSuccessMessage("Profile updated successfully!");
    },
    onError: (errors) => {
      console.error("Error updating profile:", errors);
      const errorList = Object.entries(errors).map(([field, message]) => `<li class="text-left">${message}</li>`).join('');
      Swal.fire({
        icon: 'error',
        title: 'Validation Errors',
        html: `<div class="text-left"><p class="mb-2">Please fix the following errors:</p><ul class="list-disc pl-5 text-sm text-red-600 space-y-1">${errorList}</ul></div>`,
        confirmButtonColor: '#d33',
      });
    },
  });
};

// Save and navigate to next tab
const saveAndNext = (nextTab) => {
  form.post(route("candidate.profile.update"), {
    preserveScroll: true,
    onSuccess: () => {
      showSuccessMessage("Profile updated successfully!");
      activeTab.value = nextTab;
    },
    onError: (errors) => {
      console.error("Error updating profile:", errors);
      const errorList = Object.entries(errors).map(([field, message]) => `<li class="text-left">${message}</li>`).join('');
      Swal.fire({
        icon: 'error',
        title: 'Validation Errors',
        html: `<div class="text-left"><p class="mb-2">Please fix the following errors:</p><ul class="list-disc pl-5 text-sm text-red-600 space-y-1">${errorList}</ul></div>`,
        confirmButtonColor: '#d33',
      });
    },
  });
};

// Skip to dashboard without saving age relaxation
const skipToDashboard = () => {
  window.location.href = route('candidate.dashboard');
};

// Save age relaxation and go to dashboard
const saveAndGoToDashboard = () => {
  if (
    ageRelaxationForm.relax_retired &&
    (!ageRelaxationForm.relax_retired_from ||
      !ageRelaxationForm.relax_retired_position ||
      !ageRelaxationForm.relax_retired_appoint ||
      !ageRelaxationForm.relax_retired_retired)
  ) {
    return;
  }
  if (
    ageRelaxationForm.relax_disable &&
    !ageRelaxationForm.relax_disabled_nature
  ) {
    return;
  }
  if (
    ageRelaxationForm.relax_widow &&
    (!ageRelaxationForm.relax_name_employ ||
      !ageRelaxationForm.relax_designation ||
      !ageRelaxationForm.relax_department ||
      !ageRelaxationForm.relax_date_death)
  ) {
    return;
  }
  if (
    ageRelaxationForm.gov &&
    (!ageRelaxationForm.gov_name ||
      !ageRelaxationForm.gov_designation ||
      !ageRelaxationForm.gov_basic_pay ||
      !ageRelaxationForm.gov_appoint_date ||
      !ageRelaxationForm.gov_appoint_nature)
  ) {
    return;
  }
  ageRelaxationForm.post(route("candidate.ageRelaxation.update"), {
    preserveScroll: true,
    onSuccess: () => {
      showSuccessMessage("Age Relaxation updated successfully!");
      setTimeout(() => {
        window.location.href = route('candidate.dashboard');
      }, 1000);
    },
    onError: (errors) => {
      console.error("Error updating age relaxation:", errors);
    },
  });
};

// Handle province and district changes
const handleProvinceChange = async () => {
  if (!form.province) {
    districts.value = [];
    cities.value = [];
    form.district = "";
    form.city = "";
    return;
  }
  try {
    const response = await axios.get(
      route("districts.fetch", { province_id: form.province })
    );
    districts.value = response.data.districts;
    cities.value = [];
    form.district = "";
    form.city = "";
  } catch (error) {
    console.error("Error fetching districts:", error.response?.data || error.message);
    districts.value = [];
    cities.value = [];
    form.district = "";
    form.city = "";
  }
};

const handleDistrictChange = async () => {
  if (!form.district) {
    cities.value = [];
    form.city = "";
    return;
  }
  try {
    const response = await axios.get(
      route("cities.fetch", { district_id: form.district })
    );
    cities.value = response.data.cities;
    form.city = "";
  } catch (error) {
    console.error("Error fetching cities:", error.response?.data || error.message);
    cities.value = [];
    form.city = "";
  }
};

// Handle husband's province and district changes
const handleHusbandProvinceChange = async () => {
  if (!form.husbandProvince) {
    husbandDistricts.value = [];
    husbandCities.value = [];
    form.husbandDistrict = "";
    form.husbandCity = "";
    return;
  }
  try {
    const response = await axios.get(
      route("districts.fetch", { province_id: form.husbandProvince })
    );
    husbandDistricts.value = response.data.districts;
    husbandCities.value = [];
    form.husbandDistrict = "";
    form.husbandCity = "";
  } catch (error) {
    console.error("Error fetching husband's districts:", error.response?.data || error.message);
    husbandDistricts.value = [];
    husbandCities.value = [];
    form.husbandDistrict = "";
    form.husbandCity = "";
  }
};

const handleHusbandDistrictChange = async () => {
  if (!form.husbandDistrict) {
    husbandCities.value = [];
    form.husbandCity = "";
    return;
  }
  try {
    const response = await axios.get(
      route("cities.fetch", { district_id: form.husbandDistrict })
    );
    husbandCities.value = response.data.cities;
    form.husbandCity = "";
  } catch (error) {
    console.error("Error fetching husband's cities:", error.response?.data || error.message);
    husbandCities.value = [];
    form.husbandCity = "";
  }
};

// Handle marital status change to reset domicile fields
const handleMaritalStatusChange = () => {
  if (form.maritalStatus !== "Married" || form.gender !== "Female") {
    form.useHusbandDomicile = "";
    form.husbandName = "";
    form.husbandCnic = "";
    form.husbandProvince = "";
    form.husbandDistrict = "";
    form.husbandCity = "";
    husbandDistricts.value = [];
    husbandCities.value = [];
  } else if (userDetails?.user_spouses?.length) {
    form.useHusbandDomicile = "Yes";
    loadHusbandAddress();
  }
};

// Age Relaxation Form
// Age Relaxation Form
const ageRelaxationForm = useForm({
  relax_schedule_caste: Boolean(Number(ageRelaxation?.relax_schedule_caste)),
  relax_retired: Boolean(Number(ageRelaxation?.relax_retired)),
  relax_retired_from: ageRelaxation?.relax_retired_from || "",
  relax_retired_position: ageRelaxation?.relax_retired_position || "",
  relax_retired_appoint: ageRelaxation?.relax_retired_appoint || "",
  relax_retired_retired: ageRelaxation?.relax_retired_retired || "",
  relax_disable: Boolean(Number(ageRelaxation?.relax_disable)),
  relax_disabled_nature: ageRelaxation?.relax_disabled_nature || "",
  relax_widow: Boolean(Number(ageRelaxation?.relax_widow)),
  relax_name_employ: ageRelaxation?.relax_name_employ || "",
  relax_designation: ageRelaxation?.relax_designation || "",
  relax_department: ageRelaxation?.relax_department || "",
  relax_date_death: ageRelaxation?.relax_date_death || "",
  gov: Boolean(Number(ageRelaxation?.gov)),
  gov_name: ageRelaxation?.gov_name || "",
  gov_designation: ageRelaxation?.gov_designation || "",
  gov_basic_pay: ageRelaxation?.gov_basic_pay || "",
  gov_appoint_date: ageRelaxation?.gov_appoint_date || "",
  gov_retire_date: ageRelaxation?.gov_retire_date || "",
  gov_appoint_nature: ageRelaxation?.gov_appoint_nature || "",
});

// Toggle visibility of conditional fields
// Toggle visibility of conditional fields and ensure mutual exclusivity
// Toggle visibility of conditional fields and ensure mutual exclusivity
const toggleRetiredFields = () => {
  if (ageRelaxationForm.relax_retired) {
    if (ageRelaxationForm.relax_schedule_caste || ageRelaxationForm.relax_disable || ageRelaxationForm.relax_widow || ageRelaxationForm.gov) {
      ageRelaxationForm.relax_retired = false;
      Swal.fire({
        icon: 'warning',
        title: 'Multiple Selections Not Allowed',
        text: 'You can only select one age relaxation category. Please uncheck the existing selection first.',
        confirmButtonColor: '#3085d6',
      });
      return;
    }
  } else {
    ageRelaxationForm.relax_retired_from = "";
    ageRelaxationForm.relax_retired_position = "";
    ageRelaxationForm.relax_retired_appoint = "";
    ageRelaxationForm.relax_retired_retired = "";
  }
};

const toggleScheduleCaste = () => {
  if (ageRelaxationForm.relax_schedule_caste) {
    if (ageRelaxationForm.relax_retired || ageRelaxationForm.relax_disable || ageRelaxationForm.relax_widow || ageRelaxationForm.gov) {
      ageRelaxationForm.relax_schedule_caste = false;
      Swal.fire({
        icon: 'warning',
        title: 'Multiple Selections Not Allowed',
        text: 'You can only select one age relaxation category. Please uncheck the existing selection first.',
        confirmButtonColor: '#3085d6',
      });
      return;
    }
  }
};

const toggleDisabledFields = () => {
  if (ageRelaxationForm.relax_disable) {
    if (ageRelaxationForm.relax_schedule_caste || ageRelaxationForm.relax_retired || ageRelaxationForm.relax_widow || ageRelaxationForm.gov) {
      ageRelaxationForm.relax_disable = false;
      Swal.fire({
        icon: 'warning',
        title: 'Multiple Selections Not Allowed',
        text: 'You can only select one age relaxation category. Please uncheck the existing selection first.',
        confirmButtonColor: '#3085d6',
      });
      return;
    }
  } else {
    ageRelaxationForm.relax_disabled_nature = "";
  }
};

const toggleWidowFields = () => {
  if (ageRelaxationForm.relax_widow) {
    if (ageRelaxationForm.relax_schedule_caste || ageRelaxationForm.relax_retired || ageRelaxationForm.relax_disable || ageRelaxationForm.gov) {
      ageRelaxationForm.relax_widow = false;
      Swal.fire({
        icon: 'warning',
        title: 'Multiple Selections Not Allowed',
        text: 'You can only select one age relaxation category. Please uncheck the existing selection first.',
        confirmButtonColor: '#3085d6',
      });
      return;
    }
  } else {
    ageRelaxationForm.relax_name_employ = "";
    ageRelaxationForm.relax_designation = "";
    ageRelaxationForm.relax_department = "";
    ageRelaxationForm.relax_date_death = "";
  }
};

const toggleGovFields = () => {
  if (ageRelaxationForm.gov) {
    if (ageRelaxationForm.relax_schedule_caste || ageRelaxationForm.relax_retired || ageRelaxationForm.relax_disable || ageRelaxationForm.relax_widow) {
      ageRelaxationForm.gov = false;
      Swal.fire({
        icon: 'warning',
        title: 'Multiple Selections Not Allowed',
        text: 'You can only select one age relaxation category. Please uncheck the existing selection first.',
        confirmButtonColor: '#3085d6',
      });
      return;
    }
  } else {
    ageRelaxationForm.gov_name = "";
    ageRelaxationForm.gov_designation = "";
    ageRelaxationForm.gov_basic_pay = "";
    ageRelaxationForm.gov_appoint_date = "";
    ageRelaxationForm.gov_appoint_nature = "";
  }
};

// Save Age Relaxation Form
const saveAgeRelaxationForm = () => {
  if (
    ageRelaxationForm.relax_retired &&
    (!ageRelaxationForm.relax_retired_from ||
      !ageRelaxationForm.relax_retired_position ||
      !ageRelaxationForm.relax_retired_appoint ||
      !ageRelaxationForm.relax_retired_retired)
  ) {
    return;
  }
  if (
    ageRelaxationForm.relax_disable &&
    !ageRelaxationForm.relax_disabled_nature
  ) {
    return;
  }
  if (
    ageRelaxationForm.relax_widow &&
    (!ageRelaxationForm.relax_name_employ ||
      !ageRelaxationForm.relax_designation ||
      !ageRelaxationForm.relax_department ||
      !ageRelaxationForm.relax_date_death)
  ) {
    return;
  }
  if (
    ageRelaxationForm.gov &&
    (!ageRelaxationForm.gov_name ||
      !ageRelaxationForm.gov_designation ||
      !ageRelaxationForm.gov_basic_pay ||
      !ageRelaxationForm.gov_appoint_date ||
      !ageRelaxationForm.gov_appoint_nature)
  ) {
    return;
  }
  ageRelaxationForm.post(route("candidate.ageRelaxation.update"), {
    preserveScroll: true,
    onSuccess: () => {
      showSuccessMessage("Age Relaxation updated successfully!");
    },
    onError: (errors) => {
      console.error("Error updating age relaxation:", errors);
    },
  });
};

// Reset Age Relaxation Form
const resetAgeRelaxationForm = () => {
  ageRelaxationForm.reset();
};

defineOptions({
  layout: Candidatelayout,
});
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.5s ease-out;
}

.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from {
  transform: translateX(20px);
  opacity: 0;
}

.slide-fade-leave-to {
  transform: translateX(-20px);
  opacity: 0;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(to bottom, #3b82f6, #6366f1);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(to bottom, #2563eb, #4f46e5);
}

/* Smooth transitions for all interactive elements */
input, select, textarea, button {
  transition: all 0.3s ease;
}

/* Focus visible for accessibility */
input:focus-visible,
select:focus-visible,
textarea:focus-visible {
  outline: 2px solid #3b82f6;
  outline-offset: 2px;
}

/* Disabled state styling */
input:disabled,
select:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}

/* Radio button custom styling */
input[type="radio"]:checked {
  background-color: #3b82f6;
  border-color: #3b82f6;
}

/* Animate profile completion bar */
.profile-completion-bar {
  transition: width 1s ease-out;
}
</style>
