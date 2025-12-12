<template>
  <Head title="FGEI Job Portal" />

  <!-- Full screen: 50% darkslategrey left, 50% white content right -->
  <div class="min-h-screen flex">

    <!-- Left: darkslategrey -->
    <div class="hidden lg:block w-1/2 bg-[#2F4F4F]"></div>

    <!-- Right: White Panel -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-6">

      <div class="w-full max-w-md text-center">

        <!-- Logo -->
        <div class="flex justify-center mb-6">
          <img src="https://sis.fgei.gov.pk/Assets/img/logofg.png" alt="FGEI Logo" class="h-16" />
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-2">WELCOME TO</h1>
        <h2 class="text-4xl font-bold text-teal-700 mb-8">FGEI (C/G) JOB PORTAL</h2>

        <!-- Login Button -->
        <Link
          :href="route('login')"
          class="inline-block px-8 py-3 text-lg font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-md transition duration-300"
        >
          LOGIN
        </Link>

      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<style scoped>
/* ETC Teal */
.bg-teal-600 { background-color: #00695c; }
.hover\:bg-teal-700:hover { background-color: #004d40; }
</style>
