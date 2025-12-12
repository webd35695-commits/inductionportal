<template>
  <table class="w-full">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700 uppercase tracking-wide border-b-2 border-gray-200">APPLICATION ID</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700 uppercase tracking-wide border-b-2 border-gray-200">APPLICATION TYPE</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700 uppercase tracking-wide border-b-2 border-gray-200">DATE</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700 uppercase tracking-wide border-b-2 border-gray-200">STATUS</th>
        <th class="px-6 py-4 text-center text-base font-semibold text-gray-700 uppercase tracking-wide border-b-2 border-gray-200">View</th>
        <th class="px-6 py-4 text-center text-base font-semibold text-gray-700 uppercase tracking-wide border-b-2 border-gray-200">Online Payment</th>
        <th class="px-6 py-4 text-center text-base font-semibold text-gray-700 uppercase tracking-wide border-b-2 border-gray-200">Download</th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <tr v-for="application in applications" :key="application.id" class="hover:bg-gray-100 transition-colors">
        <td class="px-6 py-4 text-lg font-medium text-gray-900 border-b-2 border-gray-100">{{ application.id }}</td>
        <td class="px-6 py-4 text-lg text-gray-700 border-b-2 border-gray-100">
          <div>
            <div class="font-medium">{{ application.type }} -</div>
            <div class="text-green-700 font-medium mt-1">{{ application.region }}</div>
          </div>
        </td>
        <td class="px-6 py-4 text-lg text-gray-700 border-b-2 border-gray-100">{{ application.date }}</td>
        <td class="px-6 py-4 border-b-2 border-gray-100">
          <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-green-100 text-green-800 border border-green-200">
            {{ application.status }}
          </span>
        </td>
        <td class="px-6 py-4 text-center border-b-2 border-gray-100">
          <button class="text-gray-600 hover:text-green-700 transition-colors p-2 rounded-full bg-gray-100">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
              <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
            </svg>
          </button>
        </td>
        <td class="px-6 py-4 text-center text-gray-600 border-b-2 border-gray-100">-</td>
        <td class="px-6 py-4 text-center border-b-2 border-gray-100">
          <button class="text-gray-600 hover:text-green-700 transition-colors p-2 rounded-full bg-gray-100">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
defineProps({
  applications: {
    type: Array,
    required: true
  }
});
</script>

