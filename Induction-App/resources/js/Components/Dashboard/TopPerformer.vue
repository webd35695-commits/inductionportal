<template>
  <div>
    <div class="flex flex-col items-center py-6">
      <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Top Performer" class="w-16 h-16 mb-3 border-2 border-purple-500 rounded-full">
      <h4 class="text-lg font-semibold text-gray-800">Dianne Russell</h4>
      <p class="mb-1 text-sm text-gray-500">Agent ID: 36254</p>
      <p class="text-xl font-bold text-purple-600">$20,000</p>
    </div>
    
    <div class="grid grid-cols-3 gap-2 text-center">
      <div v-for="(item, index) in stats" :key="index" class="p-2">
        <p class="text-sm text-gray-500">{{ item.label }}</p>
        <p class="font-medium text-gray-800">{{ item.value }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TopPerformer',
  data() {
    return {
      stats: [
        { label: 'Projects', value: '24' },
        { label: 'Tasks', value: '85' },
        { label: 'Clients', value: '16' }
      ]
    };
  }
};
</script>