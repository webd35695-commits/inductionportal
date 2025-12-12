
<template>
  <div>
    <!-- Announcements -->
    <div
      class="bg-gradient-to-r from-teal-100 to-pink-100 text-teal-800 p-6 mb-8 rounded-xl flex justify-between items-center shadow-md"
      id="announcement-section"
    >
      <div class="flex items-center w-full">
        <button
          @click="prevAnnouncement"
          class="text-teal-700 hover:text-amber-400 mr-4 text-lg"
        >
          <i class="fas fa-chevron-left"></i>
        </button>
        <div class="flex-1">
          <h3 class="font-bold text-lg text-teal-900">Announcements</h3>
          <div id="announcement-content">
            <p
              v-for="(announcement, index) in announcements"
              :key="index"
              class="text-base announcement-text"
              :class="{ hidden: currentAnnouncement !== index }"
              :data-index="index"
            >
              {{ announcement.text }}
              <inertia-link :href="announcement.link" class="underline hover:text-amber-400 transition-colors">
                {{ announcement.linkText }}
              </inertia-link>
            </p>
          </div>
        </div>
        <button
          @click="nextAnnouncement"
          class="text-teal-700 hover:text-amber-400 ml-4 text-lg"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
        <span class="text-sm bg-teal-200 text-teal-800 px-3 py-1 rounded-full ml-4 font-semibold shadow-sm">
          {{ currentAnnouncement + 1 }}/{{ announcements.length }}
        </span>
      </div>
    </div>

    <!-- Active Job Listings -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
      <div
        v-for="job in jobs"
        :key="job.id"
        class="card p-6 rounded-xl shadow-lg text-center"
      >
        <div class="flex justify-center mb-4">
          <i class="fas fa-briefcase text-teal-600 text-3xl"></i>
        </div>
        <h4 class="font-bold text-lg text-gray-800">{{ job.title }}</h4>
        <p class="text-sm text-gray-600 mt-2">{{ job.deadline }}</p>
        <inertia-link :href="job.link" class="text-teal-700 hover:text-amber-400 mt-4 inline-block font-semibold transition-colors">
          Apply Now
        </inertia-link>
      </div>
    </div>

    <!-- Job Application Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="bg-white p-6 rounded-xl shadow-lg text-center"
      >
        <i :class="stat.icon" class="text-3xl mb-3"></i>
        <p class="text-gray-800 font-bold">{{ stat.label }}</p>
      </div>
    </div>

    <!-- My Job Applications -->
    <div class="bg-white p-8 rounded-xl shadow-lg">
      <h3 class="text-xl font-bold text-gray-800 mb-6">My Job Applications</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-teal-100 text-teal-800">
              <th class="p-4 font-semibold">Application ID</th>
              <th class="p-4 font-semibold">Job Title</th>
              <th class="p-4 font-semibold">Date Applied</th>
              <th class="p-4 font-semibold">Status</th>
              <th class="p-4 font-semibold">View</th>
              <th class="p-4 font-semibold">Online Payment</th>
              <th class="p-4 font-semibold">Download</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="app in applications"
              :key="app.id"
              class="border-t border-stone-200 hover:bg-red-50"
            >
              <td class="p-4 text-gray-700">{{ app.id }}</td>
              <td class="p-4 text-gray-700">{{ app.title }}</td>
              <td class="p-4 text-gray-700">{{ app.date }}</td>
              <td class="p-4">
                <span
                  class="status-badge"
                  :class="{
                    'bg-teal-200 text-teal-800': app.status === 'Call Letter Issued' || app.status === 'Approved',
                    'bg-pink-200 text-pink-800': app.status === 'Pending',
                  }"
                >
                  {{ app.status }}
                </span>
              </td>
              <td class="p-4">
                <inertia-link :href="app.viewLink">
                  <i class="fas fa-eye text-teal-700 hover:text-amber-400 cursor-pointer transition-colors"></i>
                </inertia-link>
              </td>
              <td class="p-4">
                <inertia-link :href="app.paymentLink">
                  <i class="fas fa-credit-card text-teal-700 hover:text-amber-400 cursor-pointer transition-colors"></i>
                </inertia-link>
              </td>
              <td class="p-4">
                <inertia-link :href="app.downloadLink">
                  <i class="fas fa-download text-teal-700 hover:text-amber-400 cursor-pointer transition-colors"></i>
                </inertia-link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Pagination -->
      <div class="flex justify-between items-center mt-6 text-sm text-gray-600">
        <span>
          Items per page
          <select v-model="itemsPerPage" class="border border-stone-300 rounded-md p-2 bg-white shadow-sm">
            <option>10</option>
            <option>20</option>
            <option>50</option>
          </select>
        </span>
        <span>{{ paginationInfo }}</span>
        <div class="flex space-x-1">
          <button
            @click="changePage('first')"
            class="px-3 py-1 border border-stone-300 rounded-l-md bg-white hover:bg-teal-50 transition-colors"
          >
            <i class="fas fa-angle-double-left"></i>
          </button>
          <button
            @click="changePage('prev')"
            class="px-3 py-1 border border-stone-300 bg-white hover:bg-teal-50 transition-colors"
          >
            <i class="fas fa-angle-left"></i>
          </button>
          <button
            @click="changePage('next')"
            class="px-3 py-1 border border-stone-300 bg-white hover:bg-teal-50 transition-colors"
          >
            <i class="fas fa-angle-right"></i>
          </button>
          <button
            @click="changePage('last')"
            class="px-3 py-1 border border-stone-300 rounded-r-md bg-white hover:bg-teal-50 transition-colors"
          >
            <i class="fas fa-angle-double-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const announcements = ref([
  {
    text: 'New Job Openings for EST, Assistant, and UDC Positions!',
    link: '#',
    linkText: 'Here',
  },
  {
    text: 'Scholarship Opportunities for 2025 Now Open!',
    link: '#',
    linkText: 'Here',
  },
  {
    text: 'Upcoming Webinar on Career Development on 10/04/2025!',
    link: '#',
    linkText: 'Here',
  },
]);

const currentAnnouncement = ref(0);
const totalAnnouncements = announcements.value.length;

const prevAnnouncement = () => {
  currentAnnouncement.value = (currentAnnouncement.value - 1 + totalAnnouncements) % totalAnnouncements;
};

const nextAnnouncement = () => {
  currentAnnouncement.value = (currentAnnouncement.value + 1) % totalAnnouncements;
};

const jobs = ref([
  { id: 1, title: 'Elementary School Teacher (BPS-14)', deadline: '2025-04-15', link: '#' },
  { id: 2, title: 'Assistant (BPS-15)', deadline: '2025-04-20', link: '#' },
  { id: 3, title: 'Upper Division Clerk (BPS-11)', deadline: '2025-04-25', link: '#' },
]);

const stats = ref([
  { label: '5 Total Applications', icon: 'fas fa-clipboard-list text-teal-600' },
  { label: '2 Pending Applications', icon: 'fas fa-hourglass-half text-pink-500' },
  { label: '2 Approved Applications', icon: 'fas fa-check-circle text-teal-600' },
  { label: '1 Call Letters Issued', icon: 'fas fa-id-card text-teal-700' },
]);

const applications = ref([
  {
    id: 'APP-001',
    title: 'Elementary School Teacher (BPS-14)',
    date: '2025-03-01',
    status: 'Call Letter Issued',
    viewLink: '#',
    paymentLink: '#',
    downloadLink: '#',
  },
  {
    id: 'APP-002',
    title: 'Assistant (BPS-15)',
    date: '2025-03-05',
    status: 'Pending',
    viewLink: '#',
    paymentLink: '#',
    downloadLink: '#',
  },
  {
    id: 'APP-003',
    title: 'Upper Division Clerk (BPS-11)',
    date: '2025-03-10',
    status: 'Approved',
    viewLink: '#',
    paymentLink: '#',
    downloadLink: '#',
  },
]);

const itemsPerPage = ref(10);
const currentPage = ref(1);
const totalItems = computed(() => applications.value.length);
const paginationInfo = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value + 1;
  const end = Math.min(currentPage.value * itemsPerPage.value, totalItems.value);
  return `${start}-${end} of ${totalItems.value}`;
});

const changePage = (direction) => {
  // Add pagination logic here if needed (e.g., fetch new data)
  console.log(`Change page: ${direction}`);
};
</script>

<style scoped>
.card {
  transition: all 0.4s ease;
  background: linear-gradient(135deg, #ffffff, #f9fafb);
}
.card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
}
.status-badge {
  padding: 4px 12px;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
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
