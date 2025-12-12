<!-- src/Components/Qualifications.vue -->
<template>
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="bg-emerald-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Qualifications</h2>
            <p class="mt-1 text-sm text-gray-500">
                Add your educational qualifications and certifications
            </p>
        </div>
        <div class="px-6 py-5">
            <div class="flex justify-end mb-4">
                <button
                    @click="addNewQualification"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="-ml-1 mr-2 h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        />
                    </svg>
                    Add Qualification
                </button>
            </div>
            <div v-if="qualifications.length > 0" class="space-y-6">
                <div
                    v-for="(qualification, index) in qualifications"
                    :key="index"
                    class="border border-gray-200 rounded-lg p-4"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Degree <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="qualification.degree"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                            >
                                <option value="">Select Degree</option>
                                <option
                                    v-for="type in qualification_types"
                                    :key="type.id"
                                    :value="type.id"
                                >
                                    {{ type.name }}
                                </option>
                            </select>
                            <p
                                v-if="qualificationForm.errors[`qualifications.${index}.degree_type_id`]"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ qualificationForm.errors[`qualifications.${index}.degree_type_id`] }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Field of Study <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                v-model="qualification.field"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            />
                            <p
                                v-if="qualificationForm.errors[`qualifications.${index}.degree_name`]"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ qualificationForm.errors[`qualifications.${index}.degree_name`] }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Institution <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                v-model="qualification.institution"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            />
                            <p
                                v-if="qualificationForm.errors[`qualifications.${index}.institute_name`]"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ qualificationForm.errors[`qualifications.${index}.institute_name`] }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Year of Completion <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="number"
                                v-model="qualification.year"
                                min="1900"
                                :max="new Date().getFullYear()"
                                class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            />
                            <p
                                v-if="qualificationForm.errors[`qualifications.${index}.passing_year`]"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ qualificationForm.errors[`qualifications.${index}.passing_year`] }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button
                            @click="removeQualification(index)"
                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                            Remove
                        </button>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-8">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mx-auto h-12 w-12 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    No qualifications added
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Get started by adding your first qualification.
                </p>
                <div class="mt-6">
                    <button
                        @click="addNewQualification"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="-ml-1 mr-2 h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            />
                        </svg>
                        Add Qualification
                    </button>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
            <button
                @click="saveQualifications"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
                Save Qualifications
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    qualificationData: Array,
    qualification_types: Array,
});

const qualifications = ref([]);

onMounted(() => {
    if (qualificationData?.length > 0) {
        qualifications.value = qualificationData.map(qual => ({
            id: qual.id || null,
            degree: qual.degree_type_id || '',
            field: qual.degree_name || '',
            institution: qual.institute_name || '',
            year: qual.passing_year || '',
        }));
    }
});

const qualificationForm = useForm({
    qualifications: qualifications.value,
});

const addNewQualification = () => {
    qualifications.value.push({
        id: null,
        degree: '',
        field: '',
        institution: '',
        year: '',
    });
};

const removeQualification = (index) => {
    qualifications.value.splice(index, 1);
};

const saveQualifications = () => {
    const isValid = qualifications.value.every(
        (q) => q.degree && q.field && q.institution && q.year
    );
    if (!isValid) {
        alert('Please fill all required fields (Degree, Field of Study, Institution, Year)');
        return;
    }

    qualificationForm.qualifications = qualifications.value.map(qual => ({
        id: qual.id,
        degree_type_id: qual.degree,
        degree_name: qual.field,
        institute_name: qual.institution,
        passing_year: qual.year,
    }));

    qualificationForm.post(route('candidate.qualifications.update'), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Qualifications saved successfully');
            alert('Qualifications updated successfully!');
        },
        onError: (errors) => {
            console.error('Validation errors:', errors);
            alert('Validation errors:\n' + Object.values(errors).join('\n'));
        },
    });
};
</script>