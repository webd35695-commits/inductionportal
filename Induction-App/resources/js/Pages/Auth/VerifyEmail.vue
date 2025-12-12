<script setup>
import { computed } from 'vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
            <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header Section -->

                  <div class="bg-green-700 px-6 py-4 flex items-center justify-center">
                        <img src="https://sis.fgei.gov.pk/Assets/img/logofg.png" alt="FGEI Logo" class="h-16 mr-4" />
                        <div class="text-white">
                                  <h1 class="text-2xl font-bold text-white">Verify Your Email</h1>
                    <p class="text-indigo-100 mt-1">Almost there!</p>
                        </div>
                    </div>



                <!-- Content Section -->
                <div class="px-8 py-6">
                    <div class="flex justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" style="color: #0e5723 !important" class="h-16 w-16 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>

                    <div class="mb-6 text-center text-gray-600">
                        <p class="mb-4">Thanks for signing up! Before getting started, please verify your email address by clicking on the link we sent to you.</p>
                        <p>If you didn't receive the email, we'll gladly send you another.</p>
                    </div>

                    <!-- Success Message -->
                    <div
                        v-if="verificationLinkSent"
                        class="mb-6 p-3 bg-green-50 text-green-700 rounded-lg text-sm text-center border border-green-100"
                    >
                        A new verification link has been sent to your email address.
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit">
                        <div class="flex flex-col space-y-4">
                            <PrimaryButton
                                class="w-full justify-center bg-green-600 hover:bg-green-700 focus:ring-green-500"
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                            >
                                <span v-if="!form.processing">Resend Verification Email</span>
                                <span v-else class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </PrimaryButton>

                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-green-600 hover:text-green-800 font-medium underline"
                            >
                                Log Out
                            </Link>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </GuestLayout>
</template>


<style scoped>

.to-indigo-100 {
    background: darkslategrey !important;
}
</style>
