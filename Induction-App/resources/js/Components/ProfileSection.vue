<template>
  <div class="bg-white shadow-xl rounded-lg overflow-hidden">
    <!-- Photo Section -->
    <div class="bg-emerald-50 px-6 py-4 border-b border-gray-200">
      <h2 class="text-lg font-medium text-gray-900">Profile Photo</h2>
    </div>
    <div class="px-6 py-5 flex flex-col sm:flex-row items-center gap-6">
      <div class="relative">
        <div class="h-32 w-32 rounded-full bg-gray-200 overflow-hidden border-4 border-white shadow-md">
          <img v-if="photoPreview" :src="photoPreview" alt="Profile" class="h-full w-full object-cover" />
          <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>
        <div class="absolute -bottom-3 -right-3 bg-white rounded-full p-1 shadow-md">
          <button @click="openFilePicker" class="bg-emerald-600 text-white rounded-full p-2 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
            </svg>
          </button>
          <input type="file" ref="fileInput" @change="$emit('upload-photo', $event)" class="hidden" accept="image/*" />
        </div>
      </div>
      <div class="flex-1">
        <p class="text-sm text-gray-500 mb-3">Upload a passport size photo (35mm × 45mm) with clear facial features</p>
        <div class="flex flex-wrap gap-3">
          <button @click="openFilePicker" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Upload Photo
          </button>
          <button v-if="photoPreview" @click="$emit('remove-photo')" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Remove
          </button>
        </div>
      </div>
    </div>
    <!-- Personal Information -->
    <div class="border-t border-gray-200 px-6 py-4">
      <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
      <p class="mt-1 text-sm text-gray-500">This information will be displayed publicly so be careful what you share.</p>
    </div>
    <div class="px-6 py-5 bg-white sm:grid sm:grid-cols-6 sm:gap-4">
      <div class="sm:col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <InputField label="Full Name" v-model="form.name" disabled />
        <InputField label="Father's Name" v-model="form.fatherName" :error="form.errors.fatherName" />
        <InputField label="Email" type="email" v-model="form.email" disabled />
        <InputField label="Mobile" type="tel" v-model="form.mobile" :error="form.errors.mobile" />
        <InputField label="WhatsApp" type="tel" v-model="form.phone" :error="form.errors.phone" />
        <RadioGroup label="Gender" v-model="form.gender" :options="[{value: 'Male', label: 'Male'}, {value: 'Female', label: 'Female'}]" :error="form.errors.gender" />
        <InputField label="Date of Birth" type="date" v-model="form.dateOfBirth" :error="form.errors.dateOfBirth" />
        <InputField label="CNIC" v-model="form.cnic" @input="handleCnicInput" placeholder="XXXXXXXXXXXXX" maxlength="13" :error="form.errors.cnic" />
      </div>
    </div>
    <!-- Address Information -->
    <div class="border-t border-gray-200 px-6 py-4">
      <h2 class="text-lg font-medium text-gray-900">Address Information</h2>
    </div>
    <div class="px-6 py-5 bg-white sm:grid sm:grid-cols-6 sm:gap-4">
      <div class="sm:col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <SelectField label="Province" v-model="form.province" :options="provinces" :error="form.errors.province" @change="$emit('update-address', 'user', 'province')" />
        <SelectField label="District" v-model="form.district" :options="districts" :disabled="!form.province" :error="form.errors.district" @change="$emit('update-address', 'user', 'district')" />
        <SelectField label="City" v-model="form.city" :options="cities" :disabled="!form.district" :error="form.errors.city" />
        <TextAreaField label="Postal Address" v-model="form.postalAddress" :error="form.errors.postalAddress" />
        <TextAreaField label="Permanent Address" v-model="form.permanentAddress" :error="form.errors.permanentAddress" />
      </div>
    </div>
    <!-- Additional Information -->
    <div class="border-t border-gray-200 px-6 py-4">
      <h2 class="text-lg font-medium text-gray-900">Additional Information</h2>
    </div>
    <div class="px-6 py-5 bg-white sm:grid sm:grid-cols-6 sm:gap-4">
      <div class="sm:col-span-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <InputField label="Religion" v-model="form.religion" :error="form.errors.religion" />
        <SelectField label="Disability Status" v-model="form.disabilityStatus" :options="[{value: '', label: 'Select Disability Status'}, {value: 'Yes', label: 'Yes'}, {value: 'No', label: 'No'}]" :error="form.errors.disabilityStatus" />
        <SelectField label="Marital Status" v-model="form.maritalStatus" :options="[{value: 'Single', label: 'Single'}, {value: 'Married', label: 'Married'}, {value: 'Divorced', label: 'Divorced'}]" :error="form.errors.maritalStatus" @change="handleMaritalStatusChange" />
        <!-- Husband Domicile -->
        <div v-if="form.gender === 'Female' && form.maritalStatus === 'Married'" class="md:col-span-3">
          <RadioGroup label="Are you applying through your husband's domicile?" v-model="form.useHusbandDomicile" :options="[{value: 'Yes', label: 'Yes'}, {value: 'No', label: 'No'}]" :error="form.errors.useHusbandDomicile" />
        </div>
        <div v-if="form.gender === 'Female' && form.maritalStatus === 'Married' && form.useHusbandDomicile === 'Yes'" class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
          <InputField label="Husband's Name" v-model="form.husbandName" :error="form.errors.husbandName" required />
          <InputField label="Husband's CNIC" v-model="form.husbandCnic" @input="handleHusbandCnicInput" placeholder="XXXXXXXXXXXXX" maxlength="13" :error="form.errors.husbandCnic" required />
          <SelectField label="Husband's Province" v-model="form.husbandProvince" :options="provinces" :error="form.errors.husbandProvince" @change="$emit('update-address', 'husband', 'province')" required />
          <SelectField label="Husband's District" v-model="form.husbandDistrict" :options="husbandDistricts" :disabled="!form.husbandProvince" :error="form.errors.husbandDistrict" @change="$emit('update-address', 'husband', 'district')" required />
          <SelectField label="Husband's City" v-model="form.husbandCity" :options="husbandCities" :disabled="!form.husbandDistrict" :error="form.errors.husbandCity" required />
        </div>
      </div>
    </div>
    <!-- Form Actions -->
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
      <button @click="$emit('reset')" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">Cancel</button>
      <button @click="$emit('save')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">Save Changes</button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import InputField from './components/InputField.vue';
import SelectField from './components/SelectField.vue';
import TextAreaField from './components/TextAreaField.vue';
import RadioGroup from './components/RadioGroup.vue';

defineProps({
  form: Object,
  provinces: Array,
  districts: Array,
  cities: Array,
  husbandDistricts: Array,
  husbandCities: Array,
  photoPreview: String
});

defineEmits(['update-address', 'save', 'reset', 'upload-photo', 'remove-photo']);

const fileInput = ref(null);

const openFilePicker = () => {
  fileInput.value.click();
};

const handleCnicInput = (event) => {
  let value = event.target.value.replace(/\D/g, '');
  if (value.length > 13) value = value.substring(0, 13);
  event.target.value = value;
  form.cnic = value;
};

const handleHusbandCnicInput = (event) => {
  let value = event.target.value.replace(/\D/g, '');
  if (value.length > 13) value = value.substring(0, 13);
  event.target.value = value;
  form.husbandCnic = value;
};

const handleMaritalStatusChange = () => {
  if (form.maritalStatus !== 'Married' || form.gender !== 'Female') {
    form.useHusbandDomicile = '';
    form.husbandName = '';
    form.husbandCnic = '';
    form.husbandProvince = '';
    form.husbandDistrict = '';
    form.husbandCity = '';
  }
};
</script>