<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
            <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header Section -->

                     <div class="bg-green-700 px-6 py-4 flex items-center justify-center">
                        <img src="https://sis.fgei.gov.pk/Assets/img/logofg.png" alt="FGEI Logo" class="h-16 mr-4" />
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Reset Your Password</h1>
                            <p class="text-sm opacity-90">Create a new secure password</p>
                        </div>
                    </div>




                <!-- Form Section -->
                <form @submit.prevent="submit" class="px-8 py-6">
                    <div class="mb-1">
                        <InputLabel
                            for="email"
                            value="Email Address"
                            class="block text-gray-700 font-medium mb-1"
                        />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-4"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                            readonly
                        />
                        <InputError class="mt-1 text-red-600 text-sm" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="password"
                            value="New Password"
                            class="block text-gray-700 font-medium mb-1"
                        />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-4"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            placeholder="Enter your new password"
                        />
                        <InputError class="mt-1 text-red-600 text-sm" :message="form.errors.password" />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="password_confirmation"
                            value="Confirm Password"
                            class="block text-gray-700 font-medium mb-1"
                        />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2.5 px-4"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Confirm your new password"
                        />
                        <InputError
                            class="mt-1 text-red-600 text-sm"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <div class="mt-6">
                        <PrimaryButton
                            class="w-full justify-center bg-green-600 hover:bg-green-700 focus:ring-green-500"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing">Reset Password</span>
                            <span v-else class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Resetting...
                            </span>
                        </PrimaryButton>
                    </div>
                </form>

                <!-- Password Tips -->
                <div class="bg-gray-50 px-8 py-4 border-t border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Password Tips:</h3>
                    <ul class="text-xs text-gray-600 space-y-1">
                        <li class="flex items-center">
                            <svg class="h-3 w-3 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Use at least 8 characters
                        </li>
                        <li class="flex items-center">
                            <svg class="h-3 w-3 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Include numbers and special characters
                        </li>
                        <li class="flex items-center">
                            <svg class="h-3 w-3 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Avoid common words or patterns
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
