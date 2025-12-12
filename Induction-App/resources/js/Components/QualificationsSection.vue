<template>
  <div class="bg-white shadow-xl rounded-lg overflow-hidden">
    <div class="bg-emerald-50 px-6 py-4 border-b border-gray-200">
      <h2 class="text-lg font-medium text-gray-900">Qualifications</h2>
      <p class="mt-1 text-sm text-gray-500">Add your educational qualifications and certifications</p>
    </div>
    <div class="px-6 py-5">
      <div class="border border-gray-200 rounded-lg p-4 mb-6">
        <h3 class="text-md font-medium text-gray-900 mb-4">{{ editingQualification ? 'Edit Qualification' : 'Add New Qualification' }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <SelectField label="Degree" v-model="qualificationForm.degree" :options="qualification_types" :error="qualificationFormErrors.degree" required />
          <InputField label="Field of Study" v-model="qualificationForm.field" :error="qualificationFormErrors.field" required />
          <InputField label="Institution" v-model="qualificationForm.institution" :error="qualificationFormErrors.institution" required />
          <InputField label="Year of Completion" type="number" v-model="qualificationForm.year" :min="1900" :max="new Date().getFullYear()" :error="qualificationFormErrors.year" required />
        </div>
        <div class="mt-6 flex justify-end space-x-3">
          <button v-if="editingQualification" @click="cancelEdit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">Cancel</button>
          <button @click="$emit('save')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">{{ editingQualification ? 'Update' : 'Add' }} Qualification</button>
        </div>
      </div>
      <div v-if="qualifications.length > 0" class="mt-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Degree</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field of Study</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institution</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(qualification, index) in qualifications" :key="qualification.id || index">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ getDegreeName(qualification.degree) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ qualification.field }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ qualification.institution }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ qualification.year }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                  <button @click="$emit('edit', qualification, index)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                  <button @click="$emit('delete', qualification, index)" class="text-red-600 hover:text-red-900">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div v-else class="text-center py-8 mt-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No qualifications added</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by adding your first qualification using the form above.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import InputField from './components/InputField.vue';
import SelectField from './components/SelectField.vue';

defineProps({
  qualifications: Array,
  qualification_types: Array,
  qualificationForm: Object,
  qualificationFormErrors: Object,
  editingQualification: Boolean,
  getDegreeName: Function
});

defineEmits(['save', 'edit', 'delete']);

const cancelEdit = () => {
  qualificationForm.value = { id: null, degree: '', field: '', institution: '', year: '' };
  qualificationFormErrors.value = {};
  editingQualification.value = false;
};
</script>