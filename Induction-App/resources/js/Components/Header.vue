<template>
  <header class="flex items-center justify-between p-4 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center">
      <span class="text-xl font-semibold text-gray-800">Induction</span>
    </div>
    <div class="flex items-center space-x-6">
      <div class="relative">
        <input
          type="text"
          placeholder="Search..."
          class="py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
        >
        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>

      <div class="flex items-center space-x-4">
        <!-- Messages Dropdown -->
        <div class="relative group">
          <button class="relative text-gray-600 hover:text-purple-700">
            <!-- Messages Icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
            <span class="absolute flex items-center justify-center w-4 h-4 text-xs text-white bg-purple-500 rounded-full -top-1 -right-1">3</span>
          </button>
          <div class="absolute right-0 z-50 hidden w-64 py-1 mt-2 bg-white rounded-md shadow-lg group-hover:block">
            <div class="px-4 py-2 border-b border-gray-100">
              <p class="text-sm font-medium text-gray-800">Messages</p>
            </div>
            <a href="#" class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
              <img src="https://randomuser.me/api/portraits/women/32.jpg" class="w-8 h-8 mr-2 rounded-full">
              <div>
                <p class="font-medium">Jane Cooper</p>
                <p class="text-xs text-gray-500">Meeting at 2pm</p>
              </div>
            </a>
            <a href="#" class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
              <img src="https://randomuser.me/api/portraits/men/41.jpg" class="w-8 h-8 mr-2 rounded-full">
              <div>
                <p class="font-medium">Wade Warren</p>
                <p class="text-xs text-gray-500">Project update</p>
              </div>
            </a>
            <div class="px-4 py-2 border-t border-gray-100">
              <a href="#" class="text-xs text-purple-600 hover:text-purple-800">View all messages</a>
            </div>
          </div>
        </div>

        <!-- Notifications Dropdown -->
        <div class="relative group">
          <button class="relative text-gray-600 hover:text-purple-700">
            <!-- Notifications Icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute flex items-center justify-center w-4 h-4 text-xs text-white bg-red-500 rounded-full -top-1 -right-1">5</span>
          </button>
          <div class="absolute right-0 z-50 hidden py-1 mt-2 bg-white rounded-md shadow-lg w-72 group-hover:block">
            <div class="px-4 py-2 border-b border-gray-100">
              <p class="text-sm font-medium text-gray-800">Notifications</p>
            </div>
            <a href="#" class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
              <div class="p-2 mr-2 bg-purple-100 rounded-full">
                <!-- Alert Icon -->
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium">New login detected</p>
                <p class="text-xs text-gray-500">2 minutes ago</p>
              </div>
            </a>
            <a href="#" class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
              <div class="p-2 mr-2 bg-blue-100 rounded-full">
                <!-- Check Icon -->
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <div>
                <p class="font-medium">Task completed</p>
                <p class="text-xs text-gray-500">1 hour ago</p>
              </div>
            </a>
            <div class="px-4 py-2 border-t border-gray-100">
              <a href="#" class="text-xs text-purple-600 hover:text-purple-800">View all notifications</a>
            </div>
          </div>
        </div>

        <!-- User Dropdown -->
        <div class="relative group">
          <button class="flex items-center space-x-2 focus:outline-none">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-8 h-8 rounded-full">
            <span class="text-sm font-medium text-gray-700">Admin</span>
            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div class="absolute right-0 z-50 hidden w-48 py-1 mt-2 bg-white rounded-md shadow-lg group-hover:block">
            <a href="#" class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
              <!-- User Profile Icon -->
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              Profile
            </a>
            <a href="#" class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
              <!-- Settings Icon -->
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              Settings
            </a>
            <div class="border-t border-gray-100"></div>
            <a href="#" class="flex items-center block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
              <!-- Logout Icon -->
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'Header',
};
</script>

<style scoped>
/* Smooth transitions for dropdowns */
.group:hover .group-hover\:block {
  display: block;
  animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
