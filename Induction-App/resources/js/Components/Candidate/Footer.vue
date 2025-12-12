<template>
  <footer class="bg-gray-900 text-black-300 pt-12 pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-10xl mx-auto">
      <!-- Footer Columns -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
        <!-- About FGEI -->
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">About FGEI</h3>
          <p class="mb-4 text-gray-400">
            Federal Government Educational Institutions providing quality
            education across Pakistan since 1976.
          </p>
          <div class="flex space-x-4">
            <a
              href="https://facebook.com/fgei"
              target="_blank"
              rel="noopener noreferrer"
              class="text-gray-400 hover:text-white transition-colors"
              aria-label="Facebook"
            >
              <svg
                class="h-6 w-6"
                fill="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
              >
                <path
                  fill-rule="evenodd"
                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                  clip-rule="evenodd"
                />
              </svg>
            </a>
            <!-- Add similar links for Twitter, YouTube -->
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li>
           <a :href="route('Support.index')" class="text-gray-400 hover:text-white transition-colors block py-1">
  Support
</a>
            </li>
            <li>
              <Link
                href="/faq"
                class="text-gray-400 hover:text-white transition-colors block py-1"
              >
                FAQ
              </Link>
            </li>
          </ul>
        </div>
        <!-- Resources -->
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">Resources</h3>
          <ul class="space-y-2">
            <li>
              <a
                href="/guide"
                class="text-gray-400 hover:text-white transition-colors"
                >User Guide</a
              >
            </li>
            <!-- Add other resource links -->
          </ul>
        </div>

        <!-- Contact Us -->
        <div>
          <h3 class="text-white text-lg font-semibold mb-4">Contact Us</h3>
          <address class="not-italic text-gray-400">
            <div class="flex items-start mb-3">
              <svg
                class="h-5 w-5 mr-2 mt-0.5 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
              <span>FGEI Directorate</span>
            </div>
            <div class="flex items-center mb-3">
              <svg
                class="h-5 w-5 mr-2 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                />
              </svg>
              <span>+92 51 1234567</span>
            </div>
            <div class="flex items-center">
              <svg
                class="h-5 w-5 mr-2 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                />
              </svg>
              <span>info@fgei.edu.pk</span>
            </div>
          </address>
        </div>
      </div>

      <!-- Divider -->
      <div class="border-t border-gray-800 my-6"></div>

      <!-- Bottom Row -->
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center mb-4 md:mb-0">
          <span class="text-white font-medium"
            >Federal Government Educational Institutions</span
          >
        </div>

        <div
          class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-6"
        >
          <p class="text-gray-400 text-sm">
            © {{ currentYear }} FGEI(Dte). All rights reserved.
          </p>
          <div class="flex space-x-4">
            <a
              href="/privacy"
              class="text-gray-400 hover:text-white text-sm transition-colors"
              >Privacy Policy</a
            >
            <a
              href="/terms"
              class="text-gray-400 hover:text-white text-sm transition-colors"
              >Terms of Service</a
            >
            <a
              href="/sitemap"
              class="text-gray-400 hover:text-white text-sm transition-colors"
              >Sitemap</a
            >
          </div>
        </div>
      </div>
    </div>

    <!-- Help Desk Floating Button -->
    <div class="fixed bottom-6 right-6 z-50">

      <button
        @click="toggleChat"
        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:scale-110"
        aria-label="Help Desk"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-8 w-8"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
          />
        </svg>
        <span
          v-if="unreadCount > 0"
          class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center"
        >
          {{ unreadCount }}
        </span>
      </button>
    </div>

    <!-- Chat Window -->
  </footer>
</template>

<script>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";


</script>

