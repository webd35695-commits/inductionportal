<template>
  <footer class="fixed bottom-0 left-0 right-0 p-4 bg-[#000] border-t border-gray-700 shadow-lg">
    <div class="container mx-auto">
      <div class="flex flex-col items-center justify-center space-y-2 md:flex-row md:justify-between md:space-y-0">
        <p class="text-sm font-medium text-white">
          © 2025 FGEI Induction. All rights reserved.
        </p>
        <div class="flex items-center space-x-4">
          <p class="text-sm font-medium text-white">
            Developed by <span class="text-[#4fd1c5] font-bold">IT CELL</span>
          </p>
          <div class="flex space-x-3">
            <a href="#" class="text-white hover:text-[#4fd1c5] transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
              </svg>
            </a>


          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer',
};
</script>

<style scoped>
footer {
  z-index: 50;
  box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1), 0 -2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
}

footer:hover {
  box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.2), 0 -2px 4px -1px rgba(0, 0, 0, 0.12);
}
</style>
