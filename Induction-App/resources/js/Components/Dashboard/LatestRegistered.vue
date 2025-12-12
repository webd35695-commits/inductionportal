<template>
  <div>
    <div class="space-y-4">
      <div v-for="(user, index) in users" :key="index" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50">
        <div class="flex items-center space-x-3">
          <img :src="user.avatar" :alt="user.name" class="w-10 h-10 rounded-full">
          <div>
            <p class="text-sm font-medium text-gray-800">{{ user.name }}</p>
            <p class="text-xs text-gray-500">{{ user.email }}</p>
          </div>
        </div>
        <span class="px-2 py-1 text-xs text-purple-800 bg-purple-100 rounded-full">{{ user.status }}</span>
      </div>
    </div>
    <div class="mt-4 text-center">
      <button class="text-sm font-medium text-purple-600 hover:text-purple-800">View All</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LatestRegistered',
  data() {
    return {
      users: [
        {
          name: 'Dianne Russell',
          email: 'dianne@example.com',
          avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
          status: 'Subscribed'
        },
        {
          name: 'Devon Lane',
          email: 'devon@example.com',
          avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
          status: 'Free'
        },
        {
          name: 'Courtney Henry',
          email: 'courtney@example.com',
          avatar: 'https://randomuser.me/api/portraits/women/63.jpg',
          status: 'Subscribed'
        },
        {
          name: 'Wade Warren',
          email: 'wade@example.com',
          avatar: 'https://randomuser.me/api/portraits/men/41.jpg',
          status: 'Free'
        }
      ]
    };
  }
};
</script>