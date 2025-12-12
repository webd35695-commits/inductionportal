<template>
  <div class="p-5 bg-white border border-gray-100 shadow-sm rounded-xl">
    <div class="flex items-start justify-between">
      <div>
        <div class="mb-1 text-sm font-medium text-gray-500">{{ label }}</div>
        <div class="text-2xl font-bold text-gray-800">{{ value }}</div>
      </div>
      <div class="p-2 rounded-lg" :class="iconBgColor">
        <svg v-if="icon === 'users'" class="w-6 h-6" :class="iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <svg v-else-if="icon === 'subscription'" class="w-6 h-6" :class="iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        <svg v-else-if="icon === 'free-users'" class="w-6 h-6" :class="iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
        </svg>
        <svg v-else-if="icon === 'income'" class="w-6 h-6" :class="iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <svg v-else class="w-6 h-6" :class="iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
        </svg>
      </div>
    </div>
    <div class="flex items-center mt-4">
      <span class="text-sm font-medium" :class="trendPositive ? 'text-green-600' : 'text-red-600'">
        {{ trend }}
      </span>
      <span class="ml-1 text-xs text-gray-500">{{ trendLabel }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    label: String,
    value: [String, Number],
    trend: [String, Number],
    trendLabel: String,
    icon: String,
    trendPositive: Boolean
  },
  computed: {
    iconBgColor() {
      return this.trendPositive ? 'bg-green-100' : 'bg-red-100';
    },
    iconColor() {
      return this.trendPositive ? 'text-green-600' : 'text-red-600';
    }
  }
};
</script>