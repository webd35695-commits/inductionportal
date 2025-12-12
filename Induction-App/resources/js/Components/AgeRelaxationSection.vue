<template>
  <div class="bg-white shadow-xl rounded-lg overflow-hidden">
    <div class="bg-emerald-50 px-6 py-4 border-b border-gray-200">
      <h2 class="text-lg font-medium text-gray-900">Age Relaxation Claim | عمر کی حد میں رعایت</h2>
      <p class="mt-1 text-sm text-gray-500">Select applicable age relaxation categories and provide details</p>
    </div>
    <div class="px-6 py-5 space-y-6">
      <CheckboxField label="Scheduled Castes, Buddhist Community, Recognized Tribes, AJK, Sindh (Rural), Balochistan Domiciled" v-model="form.relax_schedule_caste" :error="form.errors.relax_schedule_caste" />
      <div>
        <CheckboxField label="Released or Retired Officer Personnel of Armed Forces" v-model="form.relax_retired" @change="toggleRetiredFields" />
        <div v-if="form.relax_retired" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
          <SelectField label="Retired From" v-model="form.relax_retired_from" :options="[{value: '', label: 'Select Your Force'}, {value: 'army', label: 'Army'}, {value: 'navy', label: 'Navy'}, {value: 'air', label: 'Air Force'}]" :error="form.errors.relax_retired_from" required />
          <InputField label="Rank" v-model="form.relax_retired_position" :error="form.errors.relax_retired_position" required />
          <InputField label="Date of Appointment" type="date" v-model="form.relax_retired_appoint" :error="form.errors.relax_retired_appoint" required />
          <InputField label="Date of Retirement" type="date" v-model="form.relax_retired_retired" :error="form.errors.relax_retired_retired" required />
        </div>
      </div>
      <div>
        <CheckboxField label="Disabled Person (Nature of Disability must be mentioned)" v-model="form.relax_disable" @change="toggleDisabledFields" />
        <div v-if="form.relax_disable" class="mt-4">
          <SelectField label="Nature of Disability" v-model="form.relax_disabled_nature" :options="[{value: '', label: 'Select Your Disability'}, {value: 'Leg/Arm Paralyze', label: 'Leg/Arm Paralyze'}, {value: 'Visual Defect', label: 'Visual Defect'}, {value: 'Mental/Cognitive', label: 'Mental/Cognitive'}, {value: 'Deaf/Dumb', label: 'Deaf/Dumb'}, {value: 'Other', label: 'Other'}]" :error="form.errors.relax_disabled_nature" required />
        </div>
      </div>
      <div>
        <CheckboxField label="Widow/Widower or Child of Govt Servant died during Service (on or after 01-07-2005)" v-model="form.relax_widow" @change="toggleWidowFields" />
        <div v-if="form.relax_widow" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
          <InputField label="Name of Deceased Govt Employee" v-model="form.relax_name_employ" :error="form.errors.relax_name_employ" required />
          <InputField label="Designation and BPS" v-model="form.relax_designation" :error="form.errors.relax_designation" required />
          <InputField label="Department" v-model="form.relax_department" :error="form.errors.relax_department" required />
          <InputField label="Date of Death" type="date" v-model="form.relax_date_death" :error="form.errors.relax_date_death" required />
        </div>
      </div>
      <div>
        <CheckboxField label="Contract-based Government Employee (Currently Serving)" v-model="form.gov" @change="toggleGovFields" />
        <div v-if="form.gov" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
          <InputField label="Dept Name" v-model="form.gov_name" :error="form.errors.gov_name" required />
          <InputField label="Designation" v-model="form.gov_designation" :error="form.errors.gov_designation" required />
          <InputField label="Basic Pay Scale" v-model="form.gov_basic_pay" :error="form.errors.gov_basic_pay" required />
          <InputField label="Appointment Date" type="date" v-model="form.gov_appoint_date" :error="form.errors.gov_appoint_date" required />
          <InputField label="Till Registration End Date" v-model="form.gov_retire_date" readonly />
          <SelectField label="Appointment Nature" v-model="form.gov_appoint_nature" :options="[{value: '', label: 'Select...'}, {value: 'contract', label: 'Contract'}]" :error="form.errors.gov_appoint_nature" required />
        </div>
      </div>
    </div>
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
      <button @click="$emit('reset')" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">Cancel</button>
      <button @click="$emit('save')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">Save Changes</button>
    </div>
  </div>
</template>

<script setup>
import InputField from './components/InputField.vue';
import SelectField from './components/SelectField.vue';
import CheckboxField from './components/CheckboxField.vue';

defineProps({ form: Object });
defineEmits(['save', 'reset']);

const toggleRetiredFields = () => {
  if (!form.relax_retired) {
    form.relax_retired_from = '';
    form.relax_retired_position = '';
    form.relax_retired_appoint = '';
    form.relax_retired_retired = '';
  }
};

const toggleDisabledFields = () => {
  if (!form.relax_disable) form.relax_disabled_nature = '';
};

const toggleWidowFields = () => {
  if (!form.relax_widow) {
    form.relax_name_employ = '';
    form.relax_designation = '';
    form.relax_department = '';
    form.relax_date_death = '';
  }
};

const toggleGovFields = () => {
  if (!form.gov) {
    form.gov_name = '';
    form.gov_designation = '';
    form.gov_basic_pay = '';
    form.gov_appoint_date = '';
    form.gov_appoint_nature = '';
  }
};
</script>