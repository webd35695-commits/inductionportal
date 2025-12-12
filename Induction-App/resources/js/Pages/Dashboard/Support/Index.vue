<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">My Support Tickets</h1>
            <p class="text-gray-600">Track and manage your support requests</p>
          </div>

          <!-- Search Bar -->
          <div class="relative w-full md:w-96">
            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
              type="text"
              v-model="searchTerm"
              placeholder="Search tickets..."
              class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all shadow-sm"
            />
          </div>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mt-4">
          <a href="#" class="hover:text-teal-600 transition-colors">Portal Home</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <a href="#" class="hover:text-teal-600 transition-colors">Client Area</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-900 font-medium">Support Tickets</span>
        </nav>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Tickets</p>
              <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.total }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Open</p>
              <p class="text-2xl font-bold text-emerald-600 mt-1">{{ stats.open }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">On Hold</p>
              <p class="text-2xl font-bold text-amber-600 mt-1">{{ stats.onHold }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Closed</p>
              <p class="text-2xl font-bold text-gray-600 mt-1">{{ stats.closed }}</p>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter & Action Bar -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700">View:</label>
            <select
              v-model="selectedView"
              class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
            >
              <option value="all">All Entries</option>
              <option value="open">Open</option>
              <option value="on-hold">On Hold</option>
              <option value="closed">Closed</option>
            </select>
          </div>

          <Link
            :href="route('Support.Reply')"
            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg hover:from-teal-700 hover:to-teal-800 transition-all group"
          >
            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Open New Ticket
          </Link>
        </div>
      </div>

      <!-- Tickets Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gradient-to-r from-teal-600 to-teal-700">
              <tr>
                <th
                  @click="sortBy('department')"
                  class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider cursor-pointer hover:bg-teal-800 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    Department
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                  </div>
                </th>
                <th
                  @click="sortBy('subject')"
                  class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider cursor-pointer hover:bg-teal-800 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    Subject
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                  </div>
                </th>
                <th
                  @click="sortBy('status')"
                  class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider cursor-pointer hover:bg-teal-800 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    Status
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                  </div>
                </th>
                <th
                  @click="sortBy('created_at')"
                  class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider cursor-pointer hover:bg-teal-800 transition-colors"
                >
                  <div class="flex items-center gap-2">
                    Created
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                  </div>
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="ticket in filteredTickets"
                :key="ticket.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                    {{ ticket.department || 'General' }}
                  </span>
                </td>

              <td class="px-6 py-4">
  <div class="flex items-start gap-3">
    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-600 rounded-lg flex items-center justify-center">
      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
      </svg>
    </div>
    <div class="min-w-0 flex-1 relative">
      <Link
        :href="route('Support.show', ticket.id)"
        class="text-sm font-semibold text-teal-600 hover:text-teal-800 transition-colors block"
      >
        #{{ ticket.ticket_no }}
      </Link>
      <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ ticket.description }}</p>

      <!-- Green Unread Badge -->
      <span
        v-if="ticket.unread_messages_count > 0"
        class="absolute -top-3 -left-4 inline-flex items-center justify-center min-w-7 h-7 px-2 text-xs font-bold text-white bg-emerald-500 rounded-full shadow-lg animate-pulse ring-4 ring-white"
      >
        {{ ticket.unread_messages_count > 99 ? '99+' : ticket.unread_messages_count }}
      </span>
    </div>
  </div>
</td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium border"
                    :class="getStatusClass(ticket.status)"
                  >
                    <span class="w-2 h-2 rounded-full mr-2" :class="getStatusDotClass(ticket.status)"></span>
                    {{ ticket.status }}
                  </span>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ formatDate(ticket.created_at) }}</div>
                  <div class="text-xs text-gray-500">{{ getTimeAgo(ticket.created_at) }}</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                  <Link
                    :href="route('Support.show', ticket.id)"
                    class="inline-flex items-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors shadow-sm"
                  >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Empty State -->
          <div v-if="filteredTickets.length === 0" class="text-center py-16">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tickets found</h3>
            <p class="text-sm text-gray-500 mb-6">Try adjusting your search or filter criteria, or create a new ticket.</p>
            <Link
              :href="route('Support.Reply')"
              class="inline-flex items-center px-6 py-3 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition-colors"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Create Your First Ticket
            </Link>
          </div>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-sm text-gray-700">
              <span>Show</span>
              <select
                v-model="itemsPerPage"
                class="px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
              >
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
              </select>
              <span>entries</span>
            </div>

            <div class="flex items-center gap-2">
              <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                Previous
              </button>

              <button
                v-for="page in visiblePages"
                :key="page"
                @click="goToPage(page)"
                :class="[
                  'px-4 py-2 border rounded-lg text-sm font-medium transition-colors',
                  page === currentPage
                    ? 'bg-teal-600 text-white border-teal-600'
                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>

              <button
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
              >
                Next
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Candidatelayout from '@/Layouts/Candidatelayout.vue';

defineOptions({
  layout: Candidatelayout,
});

const props = defineProps({
  tickets: {
    type: [Array, Object],
    required: true,
  },
});

const searchTerm = ref('');
const selectedView = ref('all');
const itemsPerPage = ref(10);
const currentPage = ref(1);
const sortField = ref('');
const sortDirection = ref('asc');

const rows = computed(() => Array.isArray(props.tickets?.data) ? props.tickets.data : (props.tickets || []));

const stats = computed(() => ({
  total: rows.value.length,
  open: rows.value.filter(t => t.status === 'Open').length,
  onHold: rows.value.filter(t => t.status === 'On Hold').length,
  closed: rows.value.filter(t => t.status === 'Closed').length
}));

const filteredTickets = computed(() => {
  let filtered = rows.value;

  if (searchTerm.value) {
    const term = searchTerm.value.toLowerCase();
    filtered = filtered.filter(
      (ticket) =>
        ticket.ticket_no?.toLowerCase().includes(term) ||
        ticket.department?.toLowerCase().includes(term) ||
        ticket.description?.toLowerCase().includes(term) ||
        ticket.status?.toLowerCase().includes(term)
    );
  }

  if (selectedView.value !== 'all') {
    filtered = filtered.filter(
      (ticket) =>
        ticket.status?.toLowerCase().replace(' ', '-') === selectedView.value
    );
  }

  if (sortField.value) {
    filtered = [...filtered].sort((a, b) => {
      const aVal = a[sortField.value];
      const bVal = b[sortField.value];
      const result = aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
      return sortDirection.value === 'asc' ? result : -result;
    });
  }

  return filtered;
});

const totalPages = computed(() =>
  Math.ceil(filteredTickets.value.length / itemsPerPage.value)
);

const visiblePages = computed(() => {
  const pages = [];
  const start = Math.max(1, currentPage.value - 2);
  const end = Math.min(totalPages.value, start + 4);
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
});

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
};

const goToPage = (page) => {
  currentPage.value = Math.max(1, Math.min(page, totalPages.value));
};

const getStatusClass = (status) => {
  const classes = {
    'Open': 'bg-emerald-100 text-emerald-800 border-emerald-200',
    'On Hold': 'bg-amber-100 text-amber-800 border-amber-200',
    'Closed': 'bg-gray-100 text-gray-800 border-gray-200'
  };
  return classes[status] || 'bg-gray-100 text-gray-800 border-gray-200';
};

const getStatusDotClass = (status) => {
  const classes = {
    'Open': 'bg-emerald-500',
    'On Hold': 'bg-amber-500',
    'Closed': 'bg-gray-500'
  };
  return classes[status] || 'bg-gray-500';
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
};

const getTimeAgo = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const diffInMs = now - date;
  const diffInMins = Math.floor(diffInMs / 60000);
  const diffInHours = Math.floor(diffInMins / 60);
  const diffInDays = Math.floor(diffInHours / 24);

  if (diffInMins < 60) return `${diffInMins}m ago`;
  if (diffInHours < 24) return `${diffInHours}h ago`;
  return `${diffInDays}d ago`;
};
</script>
