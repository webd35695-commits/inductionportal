<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.post(route("register"), {
    onFinish: () => form.reset("password", "password_confirmation"),
  });
};
</script>

<template>
  <Head title="Register" />

  <!-- Full screen: 50% darkslategrey left, 50% white form right -->
  <div class="min-h-screen flex">

    <!-- Left: darkslategrey -->
    <div class="hidden lg:block w-1/2 bg-[#2F4F4F]"></div>

    <!-- Right: White Form Panel -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-6">

      <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
          <img src="https://sis.fgei.gov.pk/Assets/img/logofg.png" alt="FGEI Logo" class="h-16" />
        </div>

        <!-- Title -->
        <div class="text-center mb-6">
          <h1 class="text-xl font-semibold text-gray-800">Register to FGEI Induction Portal</h1>
          <p class="text-sm text-gray-600">Enter your details below</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="space-y-5">

          <!-- Name -->
          <div>
            <InputLabel for="name" value="Full Name *" class="text-sm font-medium text-gray-700" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 focus:border-teal-600 focus:ring-teal-600"
              v-model="form.name"
              required
              autofocus
              autocomplete="name"
            />
            <InputError class="mt-1 text-xs text-red-600" :message="form.errors.name" />
          </div>

          <!-- Email -->
          <div>
            <InputLabel for="email" value="Email Address *" class="text-sm font-medium text-gray-700" />
            <TextInput
              id="email"
              type="email"
              class="mt-1 block w-full rounded-md border-gray-300 focus:border-teal-600 focus:ring-teal-600"
              v-model="form.email"
              required
              autocomplete="username"
            />
            <InputError class="mt-1 text-xs text-red-600" :message="form.errors.email" />
          </div>

          <!-- Password -->
          <div>
            <InputLabel for="password" value="Password *" class="text-sm font-medium text-gray-700" />
            <TextInput
              id="password"
              type="password"
              class="mt-1 block w-full rounded-md border-gray-300 focus:border-teal-600 focus:ring-teal-600"
              v-model="form.password"
              required
              autocomplete="new-password"
            />
            <InputError class="mt-1 text-xs text-red-600" :message="form.errors.password" />
          </div>

          <!-- Confirm Password -->
          <div>
            <InputLabel for="password_confirmation" value="Confirm Password *" class="text-sm font-medium text-gray-700" />
            <TextInput
              id="password_confirmation"
              type="password"
              class="mt-1 block w-full rounded-md border-gray-300 focus:border-teal-600 focus:ring-teal-600"
              v-model="form.password_confirmation"
              required
              autocomplete="new-password"
            />
            <InputError class="mt-1 text-xs text-red-600" :message="form.errors.password_confirmation" />
          </div>

          <!-- Register Button -->
          <div class="flex justify-center">
            <PrimaryButton
              class="px-8 py-2 text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 rounded-md"
              :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
              :disabled="form.processing"
            >
              <span v-if="!form.processing">Register</span>
              <span v-else class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Registering...
              </span>
            </PrimaryButton>
          </div>

          <!-- Footer Links -->
          <div class="text-center text-sm text-gray-600 space-y-1 mt-6">
            <p>Already have an account? <Link :href="route('login')" class="text-teal-600 hover:text-teal-800 underline">Sign in</Link></p>
            <p>Don't know how to use website? <a href="#" class="text-teal-600 hover:text-teal-800 underline">Download User Manual</a></p>
          </div>

          <div class="flex justify-center items-center space-x-3 mt-6">
            <img src="https://www.google.com/chrome/static/images/chrome-logo.svg" alt="Chrome" class="h-5">
            <img src="https://mozilla.org/media/img/logos/firefox/logo-word-hor.e20771bb4dd5.svg" alt="Firefox" class="h-5">
            <img src="https://static.microsoft.com/edge/images/icons/edge-logo.svg" alt="Edge" class="h-5">
            <img src="https://www.apple.com/v/safari/a/images/overview/icon_safari_large_2x.png" alt="Safari" class="h-5 w-5 object-contain">
          </div>
          <p class="text-center text-xs text-gray-500 mt-1">BEST VIEWED ON</p>

        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* ETC Teal */
.bg-teal-600 { background-color: #00695c; }
.hover\:bg-teal-700:hover { background-color: #004d40; }
.focus\:ring-teal-600:focus { --tw-ring-color: #00695c; }
.focus\:border-teal-600:focus { border-color: #00695c; }
</style>
