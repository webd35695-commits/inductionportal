<!-- src/Components/ProfileDetails.vue -->
<template>
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <!-- Photo Section -->
        <div class="bg-emerald-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Profile Photo</h2>
        </div>
        <div class="px-6 py-5 flex flex-col sm:flex-row items-center gap-6">
            <div class="relative">
                <div class="h-32 w-32 rounded-full bg-gray-200 overflow-hidden border-4 border-white shadow-md">
                    <img
                        v-if="photoPreview"
                        :src="photoPreview"
                        alt="Profile"
                        class="h-full w-full object-cover"
                    />
                    <div
                        v-else
                        class="h-full w-full flex items-center justify-center text-gray-400"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-16 w-16"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            />
                        </svg>
                    </div>
                </div>
                <div class="absolute -bottom-3 -right-3 bg-white rounded-full p-1 shadow-md">
                    <button
                        @click="openFilePicker"
                        class="bg-emerald-600 text-white rounded-full p-2 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>
                    <input
                        type="file"
                        ref="fileInput"
                        @change="handleFileUpload"
                        class="hidden"
                        accept="image/*"
                    />
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-500 mb-3">
                    Upload a passport size photo (35mm × 45mm) with clear facial features
                </p>
                <div class="flex flex-wrap gap-3">
                    <button
                        @click="openFilePicker"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="-ml-1 mr-2 h-5 w-5 text-gray-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                            />
                        </svg>
                        Upload Photo
                    </button>
                    <button
                        v-if="photoPreview"
                        @click="removePhoto"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="-ml-1 mr-2 h-5 w-5 text-gray-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            />
                        </svg>
                        Remove
                    </button>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="border-t border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
            <p class="mt-1 text-sm text-gray-500">
                This information will be displayed publicly so be careful what you share.
            </p>
        </div>
        <div class="px-6 py-5 bg-white sm:grid sm:grid-cols-6 sm:gap-4">
            <div class="sm:col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        disabled
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-gray-100"
                    />
                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">
                        {{ form.errors.name }}
                    </p>
                </div>
                <div>
                    <label for="fatherName" class="block text-sm font-medium text-gray-700">Father's Name</label>
                    <input
                        type="text"
                        id="fatherName"
                        v-model="form.fatherName"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    />
                    <p v-if="form.errors.fatherName" class="mt-2 text-sm text-red-600">
                        {{ form.errors.fatherName }}
                    </p>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm bg-gray-100"
                    />
                    <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700">Mobile</label>
                    <input
                        type="tel"
                        id="mobile"
                        v-model="form.mobile"
                        @input="handleMobileInput"
                        placeholder="03XXXXXXXXX"
                        maxlength="11"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    />
                    <p v-if="form.errors.mobile" class="mt-2 text-sm text-red-600">
                        {{ form.errors.mobile }}
                    </p>
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">WhatsApp</label>
                    <input
                        type="tel"
                        id="phone"
                        v-model="form.phone"
                        @input="handlePhoneInput"
                        placeholder="03XXXXXXXXX"
                        maxlength="11"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    />
                    <p v-if="form.errors.phone" class="mt-2 text-sm text-red-600">
                        {{ form.errors.phone }}
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Gender</label>
                    <div class="mt-1 space-y-2">
                        <div class="flex items-center">
                            <input
                                id="male"
                                v-model="form.gender"
                                type="radio"
                                value="Male"
                                class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
                            />
                            <label for="male" class="ml-3 block text-sm font-medium text-gray-700">Male</label>
                        </div>
                        <div class="flex items-center">
                            <input
                                id="female"
                                v-model="form.gender"
                                type="radio"
                                value="Female"
                                class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300"
                            />
                            <label for="female" class="ml-3 block text-sm font-medium text-gray-700">Female</label>
                        </div>
                    </div>
                    <p v-if="form.errors.gender" class="mt-2 text-sm text-red-600">
                        {{ form.errors.gender }}
                    </p>
                </div>
                <div>
                    <label for="dateOfBirth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input
                        type="date"
                        id="dateOfBirth"
                        v-model="form.dateOfBirth"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    />
                    <p v-if="form.errors.dateOfBirth" class="mt-2 text-sm text-red-600">
                        {{ form.errors.dateOfBirth }}
                    </p>
                </div>
                <div>
                    <label for="cnic" class="block text-sm font-medium text-gray-700">CNIC</label>
                    <input
                        type="text"
                        id="cnic"
                        v-model="form.cnic"
                        placeholder="XXXXX-XXXXXXX-X"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    />
                    <p v-if="form.errors.cnic" class="mt-2 text-sm text-red-600">
                        {{ form.errors.cnic }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="border-t border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">Address Information</h2>
        </div>
        <div class="px-6 py-5 bg-white sm:grid sm:grid-cols-6 sm:gap-4">
            <div class="sm:col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                    <select
                        id="province"
                        v-model="form.province"
                        @change="handleProvinceChange"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                    >
                        <option value="">Select Province</option>
                        <option v-for="province in provinces" :key="province.id" :value="province.id">
                            {{ province.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.province" class="mt-2 text-sm text-red-600">
                        {{ form.errors.province }}
                    </p>
                </div>
                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700">District</label>
                    <select
                        id="district"
                        v-model="form.district"
                        :disabled="!form.province"
                        @change="handleDistrictChange"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                    >
                        <option value="">Select District</option>
                        <option v-for="district in districts" :key="district.id" :value="district.id">
                            {{ district.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.district" class="mt-2 text-sm text-red-600">
                        {{ form.errors.district }}
                    </p>
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <select
                        id="city"
                        v-model="form.city"
                        :disabled="!form.district"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                    >
                        <option value="">Select City</option>
                        <option v-for="city in cities" :key="city.id" :value="city.id">
                            {{ city.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.city" class="mt-2 text-sm text-red-600">
                        {{ form.errors.city }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label for="postalAddress" class="block text-sm font-medium text-gray-700">Postal Address</label>
                    <textarea
                        id="postalAddress"
                        v-model="form.postalAddress"
                        rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    ></textarea>
                    <p v-if="form.errors.postalAddress" class="mt-2 text-sm text-red-600">
                        {{ form.errors.postalAddress }}
                    </p>
                </div>
                <div class="md:col-span-2">
                    <label for="permanentAddress" class="block text-sm font-medium text-gray-700">Permanent Address</label>
                    <textarea
                        id="permanentAddress"
                        v-model="form.permanentAddress"
                        rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    ></textarea>
                    <p v-if="form.errors.permanentAddress" class="mt-2 text-sm text-red-600">
                        {{ form.errors.permanentAddress }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="border-t border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">Additional Information</h2>
        </div>
        <div class="px-6 py-5 bg-white sm:grid sm:grid-cols-6 sm:gap-4">
            <div class="sm:col-span-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="religion" class="block text-sm font-medium text-gray-700">Religion</label>
                    <input
                        type="text"
                        id="religion"
                        v-model="form.religion"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    />
                    <p v-if="form.errors.religion" class="mt-2 text-sm text-red-600">
                        {{ form.errors.religion }}
                    </p>
                </div>
                <div>
                    <label for="disabilityStatus" class="block text-sm font-medium text-gray-700">Disability Status</label>
                    <select
                        id="disabilityStatus"
                        v-model="form.disabilityStatus"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                    >
                        <option value="">Select Disability Status</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    <p v-if="form.errors.disabilityStatus" class="mt-2 text-sm text-red-600">
                        {{ form.errors.disabilityStatus }}
                    </p>
                </div>
                <div>
                    <label for="maritalStatus" class="block text-sm font-medium text-gray-700">Marital Status</label>
                    <select
                        id="maritalStatus"
                        v-model="form.maritalStatus"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md"
                    >
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                    </select>
                    <p v-if="form.errors.maritalStatus" class="mt-2 text-sm text-red-600">
                        {{ form.errors.maritalStatus }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
            <button
                @click="resetForm"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
                Cancel
            </button>
            <button
                @click="saveForm"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
            >
                Save Changes
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

defineProps({
    userDetails: Object,
    provinces: Array,
});

const fileInput = ref(null);
const photoPreview = ref(null);
const districts = ref([]);
const cities = ref([]);

const form = useForm({
    user_id: userDetails?.id || '',
    name: userDetails?.name || '',
    email: userDetails?.email || '',
    fatherName: userDetails?.candidate_profile?.father_name || '',
    gender: userDetails?.candidate_profile?.gender || '',
    dateOfBirth: userDetails?.candidate_profile?.date_of_birth || '',
    city: userDetails?.candidate_profile?.city?.id || '',
    cnic: userDetails?.candidate_profile?.cnic || '',
    mobile: userDetails?.user_contact?.mobile || '',
    phone: userDetails?.user_contact?.phone || '',
    permanentAddress: userDetails?.user_contact?.postal_address || '',
    postalAddress: userDetails?.user_contact?.postal_address || '',
    disabilityStatus: userDetails?.candidate_profile?.disability || '',
    religion: userDetails?.candidate_profile?.religion || '',
    maritalStatus: userDetails?.user_spouses?.length ? 'Married' : 'Single',
    spouse_name: userDetails?.user_spouses?.[0]?.spouse_name || '',
    spouse_city_id: userDetails?.user_spouses?.[0]?.city_id || '',
    province: userDetails?.candidate_profile?.city?.district?.province?.id || '',
    district: userDetails?.candidate_profile?.city?.district?.id || '',
    photo: null,
});

const openFilePicker = () => {
    fileInput.value.click();
};

const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
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
    fileInput.value.value = '';
    form.photo = null;
};

const resetForm = () => {
    form.reset();
};

const saveForm = () => {
    form.post(route('candidate.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Profile updated successfully');
            alert('Profile updated successfully!');
        },
        onError: (errors) => {
            console.error('Error updating profile:', errors);
            alert('Error updating profile:\n' + Object.values(errors).join('\n'));
        },
    });
};

const handleProvinceChange = async () => {
    if (!form.province) {
        districts.value = [];
        cities.value = [];
        form.district = '';
        form.city = '';
        return;
    }

    try {
        const response = await axios.get(route('districts.fetch', { province_id: form.province }));
        districts.value = response.data.districts;
        cities.value = [];
        form.district = '';
        form.city = '';
    } catch (error) {
        console.error('Error fetching districts:', error.response?.data || error.message);
        districts.value = [];
        cities.value = [];
        form.district = '';
        form.city = '';
    }
};

const handleDistrictChange = async () => {
    if (!form.district) {
        cities.value = [];
        form.city = '';
        return;
    }

    try {
        const response = await axios.get(route('cities.fetch', { district_id: form.district }));
        cities.value = response.data.cities;
        form.city = '';
    } catch (error) {
        console.error('Error fetching cities:', error.response?.data || error.message);
        cities.value = [];
        form.city = '';
    }
};
const handleMobileInput = (e) => {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0 && !value.startsWith('03')) {
        // If user starts typing something other than 03, prepopulate or correct (simple enforcement: must start with 03)
        // Here we just let them type but if they type '3' we assume '03' context.
        // Or strictly enforce '03' prefix if length > 1
        if (value.length >= 2 && value.substring(0, 2) !== '03') {
             // For strict enforcement, one could force '03'
        }
    }
    // Limit to 11 digits
    if (value.length > 11) value = value.slice(0, 11);
    form.mobile = value;
};

const handlePhoneInput = (e) => {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) value = value.slice(0, 11);
    form.phone = value;
};
</script>