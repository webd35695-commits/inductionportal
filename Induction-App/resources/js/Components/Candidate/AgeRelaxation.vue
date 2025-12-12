<!-- src/Components/AgeRelaxation.vue -->
<template>
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="bg-emerald-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Age Relaxation Claim | عمر کی حد میں رعایت</h2>
            <p class="mt-1 text-sm text-gray-500">
                Select applicable age relaxation categories and provide details
            </p>
        </div>
        <div class="px-6 py-5 space-y-6">
            <div class="flex items-center justify-between">
                <label class="block text-sm font-medium text-gray-700 flex-1">
                    Scheduled Castes, Buddhist Community, Recognized Tribes, AJK, Sindh (Rural), Balochistan Domiciled
                </label>
                <input
                    type="checkbox"
                    v-model="ageRelaxationForm.relax_schedule_caste"
                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                />
                <p v-if="ageRelaxationForm.errors.relax_schedule_caste" class="mt-2 text-sm text-red-600">
                    {{ ageRelaxationForm.errors.relax_schedule_caste }}
                </p>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700 flex-1">
                        Released or Retired Officer Personnel of Armed Forces
                    </label>
                    <input
                        type="checkbox"
                        v-model="ageRelaxationForm.relax_retired"
                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                        @change="toggleRetiredFields"
                    />
                </div>
                <div v-if="ageRelaxationForm.relax_retired" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Retired From <span class="text-red-500">*</span></label>
                        <select
                            v-model="ageRelaxationForm.relax_retired_from"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                        >
                            <option value="">Select Your Force</option>
                            <option value="army">Army</option>
                            <option value="navy">Navy</option>
                            <option value="air">Air Force</option>
                        </select>
                        <p v-if="ageRelaxationForm.errors.relax_retired_from" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.relax_retired_from }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rank <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            v-model="ageRelaxationForm.relax_retired_position"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.relax_retired_position" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.relax_retired_position }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date of Appointment <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            v-model="ageRelaxationForm.relax_retired_appoint"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.relax_retired_appoint" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.relax_retired_appoint }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date of Retirement <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            v-model="ageRelaxationForm.relax_retired_retired"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.relax_retired_retired" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.relax_retired_retired }}
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700 flex-1">
                        Disabled Person (Nature of Disability must be mentioned)
                    </label>
                    <input
                        type="checkbox"
                        v-model="ageRelaxationForm.relax_disable"
                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                        @change="toggleDisabledFields"
                    />
                </div>
                <div v-if="ageRelaxationForm.relax_disable" class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Nature of Disability <span class="text-red-500">*</span></label>
                    <select
                        v-model="ageRelaxationForm.relax_disabled_nature"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                    >
                        <option value="">Select Your Disability</option>
                        <option value="Leg/Arm Paralyze">Leg/Arm Paralyze</option>
                        <option value="Visual Defect">Visual Defect</option>
                        <option value="Mental/Cognitive">Mental/Cognitive</option>
                        <option value="Deaf/Dumb">Deaf/Dumb</option>
                        <option value="Other">Other</option>
                    </select>
                    <p v-if="ageRelaxationForm.errors.relax_disabled_nature" class="mt-2 text-sm text-red-600">
                        {{ ageRelaxationForm.errors.relax_disabled_nature }}
                    </p>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700 flex-1">
                        Widow/Widower or Child of Govt Servant died during Service (on or after 01-07-2005)
                    </label>
                    <input
                        type="checkbox"
                        v-model="ageRelaxationForm.relax_widow"
                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                        @change="toggleWidowFields"
                    />
                </div>
                <div v-if="ageRelaxationForm.relax_widow" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name of Deceased Govt Employee <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            v-model="ageRelaxationForm.relax_name_employ"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.relax_name_employ" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.relax_name_employ }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Designation and BPS <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            v-model="ageRelaxationForm.relax_designation"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.relax_designation" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.relax_designation }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            v-model="ageRelaxationForm.relax_department"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.relax_department" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.relax_department }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date of Death <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            v-model="ageRelaxationForm.relax_date_death"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.relax_date_death" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.relax_date_death }}
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700 flex-1">
                        Contract-based Government Employee (Currently Serving)
                    </label>
                    <input
                        type="checkbox"
                        v-model="ageRelaxationForm.gov"
                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                        @change="toggleGovFields"
                    />
                </div>
                <div v-if="ageRelaxationForm.gov" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dept Name <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            v-model="ageRelaxationForm.gov_name"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.gov_name" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.gov_name }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Designation <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            v-model="ageRelaxationForm.gov_designation"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.gov_designation" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.gov_designation }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Basic Pay Scale <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            v-model="ageRelaxationForm.gov_basic_pay"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.gov_basic_pay" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.gov_basic_pay }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Appointment Date <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            v-model="ageRelaxationForm.gov_appoint_date"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.gov_appoint_date" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.gov_appoint_date }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Till Registration End Date</label>
                        <input
                            type="text"
                            v-model="ageRelaxationForm.gov_retire_date"
                            readonly
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 sm:text-sm"
                        />
                        <p v-if="ageRelaxationForm.errors.gov_retire_date" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.gov_retire_date }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Appointment Nature <span class="text-red-500">*</span></label>
                        <select
                            v-model="ageRelaxationForm.gov_appoint_nature"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                        >
                            <option value="">Select...</option>
                            <option value="contract">Contract</option>
                        </select>
                        <p v-if="ageRelaxationForm.errors.gov_appoint_nature" class="mt-2 text-sm text-red-600">
                            {{ ageRelaxationForm.errors.gov_appoint_nature }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
            <button
                @click="resetAgeRelaxationForm"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
                Cancel
            </button>
            <button
                @click="saveAgeRelaxationForm"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
                Save Changes
            </button>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

defineProps({
    userDetails: Object,
});

const ageRelaxationForm = useForm({
    relax_schedule_caste: userDetails?.post_apply?.relax_schedule_caste || false,
    relax_retired: userDetails?.post_apply?.relax_retired || false,
    relax_retired_from: userDetails?.post_apply?.relax_retired_from || '',
    relax_retired_position: userDetails?.post_apply?.relax_retired_position || '',
    relax_retired_appoint: userDetails?.post_apply?.relax_retired_appoint || '',
    relax_retired_retired: userDetails?.post_apply?.relax_retired_retired || '',
    relax_disable: userDetails?.post_apply?.relax_disable || false,
    relax_disabled_nature: userDetails?.post_apply?.relax_disabled_nature || '',
    relax_widow: userDetails?.post_apply?.relax_widow || false,
    relax_name_employ: userDetails?.post_apply?.relax_name_employ || '',
    relax_designation: userDetails?.post_apply?.relax_designation || '',
    relax_department: userDetails?.post_apply?.relax_department || '',
    relax_date_death: userDetails?.post_apply?.relax_date_death || '',
    gov: userDetails?.post_apply?.gov || false,
    gov_name: userDetails?.post_apply?.gov_name || '',
    gov_designation: userDetails?.post_apply?.gov_designation || '',
    gov_basic_pay: userDetails?.post_apply?.gov_basic_pay || '',
    gov_appoint_date: userDetails?.post_apply?.gov_appoint_date || '',
    gov_retire_date: userDetails?.post_apply?.gov_retire_date || '',
    gov_appoint_nature: userDetails?.post_apply?.gov_appoint_nature || '',
});

const toggleRetiredFields = () => {
    if (!ageRelaxationForm.relax_retired) {
        ageRelaxationForm.relax_retired_from = '';
        ageRelaxationForm.relax_retired_position = '';
        ageRelaxationForm.relax_retired_appoint = '';
        ageRelaxationForm.relax_retired_retired = '';
    }
};

const toggleDisabledFields = () => {
    if (!ageRelaxationForm.relax_disable) {
        ageRelaxationForm.relax_disabled_nature = '';
    }
};

const toggleWidowFields = () => {
    if (!ageRelaxationForm.relax_widow) {
        ageRelaxationForm.relax_name_employ = '';
        ageRelaxationForm.relax_designation = '';
        ageRelaxationForm.relax_department = '';
        ageRelaxationForm.relax_date_death = '';
    }
};

const toggleGovFields = () => {
    if (!ageRelaxationForm.gov) {
        ageRelaxationForm.gov_name = '';
        ageRelaxationForm.gov_designation = '';
        ageRelaxationForm.gov_basic_pay = '';
        ageRelaxationForm.gov_appoint_date = '';
        ageRelaxationForm.gov_appoint_nature = '';
    }
};

const saveAgeRelaxationForm = () => {
    if (ageRelaxationForm.relax_retired && (
        !ageRelaxationForm.relax_retired_from ||
        !ageRelaxationForm.relax_retired_position ||
        !ageRelaxationForm.relax_retired_appoint ||
        !ageRelaxationForm.relax_retired_retired
    )) {
        alert('Please fill all required fields in Retired Armed Forces section.');
        return;
    }
    if (ageRelaxationForm.relax_disable && !ageRelaxationForm.relax_disabled_nature) {
        alert('Please select the nature of disability.');
        return;
    }
    if (ageRelaxationForm.relax_widow && (
        !ageRelaxationForm.relax_name_employ ||
        !ageRelaxationForm.relax_designation ||
        !ageRelaxationForm.relax_department ||
        !ageRelaxationForm.relax_date_death
    )) {
        alert('Please fill all required fields in Widow/Widower section.');
        return;
    }
    if (ageRelaxationForm.gov && (
        !ageRelaxationForm.gov_name ||
        !ageRelaxationForm.gov_designation ||
        !ageRelaxationForm.gov_basic_pay ||
        !ageRelaxationForm.gov_appoint_date ||
        !ageRelaxationForm.gov_appoint_nature
    )) {
        alert('Please fill all required fields in Government Employee section.');
        return;
    }

    ageRelaxationForm.post(route('candidate.ageRelaxation.update'), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Age Relaxation updated successfully');
            alert('Age Relaxation updated successfully!');
        },
        onError: (errors) => {
            console.error('Error updating age relaxation:', errors);
            alert('Error updating age relaxation:\n' + Object.values(errors).join('\n'));
        },
    });
};

const resetAgeRelaxationForm = () => {
    ageRelaxationForm.reset();
};
</script>