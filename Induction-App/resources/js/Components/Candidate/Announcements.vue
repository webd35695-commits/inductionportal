<template>
  <div class="bg-gradient-to-r from-teal-100 to-pink-100 text-teal-800 p-6 mb-8 rounded-xl flex justify-between items-center shadow-md">
    <div class="flex items-center w-full">
      <button @click="prevAnnouncement" class="text-teal-700 hover:text-amber-400 mr-4 text-lg">
        <i class="fas fa-chevron-left"></i>
      </button>
      <div class="flex-1">
        <h3 class="font-bold text-lg text-teal-900">Announcements</h3>
        <div>
          <p v-for="(announcement, index) in announcements" :key="index" class="text-base announcement-text" :class="{ 'hidden': currentAnnouncementIndex !== index }" :data-index="index">
            {{ announcement.text }} <a :href="announcement.link" class="underline hover:text-amber-400 transition-colors">Click Here</a>
          </p>
        </div>
      </div>
      <button @click="nextAnnouncement" class="text-teal-700 hover:text-amber-400 ml-4 text-lg">
        <i class="fas fa-chevron-right"></i>
      </button>
      <span class="text-sm bg-teal-200 text-teal-800 px-3 py-1 rounded-full ml-4 font-semibold shadow-sm">{{ currentAnnouncementIndex + 1 }}/{{ announcements.length }}</span>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const currentAnnouncementIndex = ref(0);
let carouselInterval = null;

const announcements = ref([
  { text: 'New Job Openings for EST, Assistant, and UDC Positions!', link: '#' },
  { text: 'Scholarship Opportunities for 2025 Now Open!', link: '#' },
  { text: 'Upcoming Webinar on Career Development on 10/04/2025!', link: '#' },
  { text: 'Facing Difficulty to use website? Download User Guide to learn how to use website and submit application.', link: '#' },
]);

const startCarousel = () => {
  carouselInterval = setInterval(() => {
    currentAnnouncementIndex.value = (currentAnnouncementIndex.value + 1) % announcements.value.length;
  }, 4000);
};

const stopCarousel = () => {
  if (carouselInterval) clearInterval(carouselInterval);
};

const prevAnnouncement = () => {
  currentAnnouncementIndex.value = (currentAnnouncementIndex.value - 1 + announcements.value.length) % announcements.value.length;
};

const nextAnnouncement = () => {
  currentAnnouncementIndex.value = (currentAnnouncementIndex.value + 1) % announcements.value.length;
};

onMounted(() => startCarousel());
onUnmounted(() => stopCarousel());
</script>

<style>
.announcement-text {
  transition: opacity 0.3s ease;
}
.announcement-text.hidden {
  opacity: 0;
}
.announcement-text:not(.hidden) {
  opacity: 1;
}
</style>
