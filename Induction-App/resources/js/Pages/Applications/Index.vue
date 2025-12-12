<template>
  <div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-semibold text-gray-900">Application Management Dashboard</h1>
      <div class="flex gap-3">
        <button
          @click="showStatsModal = true"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center"
        >
          <ChartBarIcon class="h-5 w-5 mr-2" />
          Statistics
        </button>
        <button
          @click="showAuditLog = true"
          class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center"
        >
          <ClockIcon class="h-5 w-5 mr-2" />
          Audit Log
        </button>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Total Applications</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.total }}</p>
          </div>
          <DocumentIcon class="h-12 w-12 text-blue-500 opacity-20" />
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Approved</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ stats.approved }}</p>
          </div>
          <CheckCircleIcon class="h-12 w-12 text-green-500 opacity-20" />
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Rejected</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ stats.rejected }}</p>
          </div>
          <XCircleIcon class="h-12 w-12 text-red-500 opacity-20" />
        </div>
      </div>
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ stats.pending }}</p>
          </div>
          <ClockIcon class="h-12 w-12 text-yellow-500 opacity-20" />
        </div>
      </div>
    </div>

    <!-- Advanced Filters -->
    <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-900">Filters & Search</h2>
        <button
          @click="toggleAdvancedFilters"
          class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center"
        >
          {{ showAdvancedFilters ? 'Hide' : 'Show' }} Advanced Filters
          <ChevronDownIcon :class="{'rotate-180': showAdvancedFilters}" class="h-4 w-4 ml-1 transition-transform" />
        </button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Search by Name</label>
          <div class="relative">
            <input
              v-model="filters.name"
              type="text"
              placeholder="Enter name"
              class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              @input="debouncedSearch"
            >
            <MagnifyingGlassIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Search by CNIC</label>
          <div class="relative">
            <input
              v-model="filters.cnic"
              type="text"
              placeholder="Enter CNIC"
              class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              @input="debouncedSearch"
            >
            <IdentificationIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Filter by Post</label>
          <div class="relative">
            <select
              v-model="filters.post"
              class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              @change="applyFilters"
            >
              <option value="">All Posts</option>
              <option v-for="post in posts" :key="post.id" :value="post.id">{{ post.name }}</option>
            </select>
            <BriefcaseIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Filter by Status</label>
          <div class="relative">
            <select
              v-model="filters.status"
              class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              @change="applyFilters"
            >
              <option value="">All Statuses</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
              <option value="reverted">Reverted</option>
            </select>
            <CheckCircleIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Filter by Date</label>
          <div class="relative">
            <input
              type="date"
              v-model="filters.date"
              class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              @change="applyFilters"
            >
            <CalendarIcon class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
          </div>
        </div>
      </div>

      <!-- Advanced Filters -->
      <div v-if="showAdvancedFilters" class="mt-4 pt-4 border-t border-gray-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Date Range From</label>
            <input
              type="date"
              v-model="filters.dateFrom"
              class="w-full px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
              @change="applyFilters"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Date Range To</label>
            <input
              type="date"
              v-model="filters.dateTo"
              class="w-full px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
              @change="applyFilters"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Search by Email</label>
            <input
              v-model="filters.email"
              type="email"
              placeholder="Enter email"
              class="w-full px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
              @input="debouncedSearch"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Search by Mobile</label>
            <input
              v-model="filters.mobile"
              type="text"
              placeholder="Enter mobile"
              class="w-full px-4 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500"
              @input="debouncedSearch"
            >
          </div>
        </div>
      </div>

      <!-- Filter Actions -->
      <div class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-500">
          <span v-if="loading">Searching...</span>
          <span v-else-if="totalResults > 0">
            Showing {{ currentPageResults }} of {{ totalResults.toLocaleString() }} results
          </span>
          <span v-else>No results found</span>
        </div>

        <div class="flex gap-3">
          <button
            @click="resetFilters"
            class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center"
          >
            <ArrowPathIcon class="h-4 w-4 mr-2" />
            Reset Filters
          </button>

          <button
            @click="exportResults"
            :disabled="!applications.length || loading"
            class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 transition-colors flex items-center"
          >
            <ArrowDownTrayIcon class="h-4 w-4 mr-2" />
            Export Results
          </button>
        </div>
      </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div v-if="selectedApplications.length > 0" class="mb-4 bg-indigo-50 border border-indigo-200 rounded-lg p-4">
      <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
          <span class="text-sm font-medium text-indigo-900">
            {{ selectedApplications.length }} application(s) selected
          </span>
          <button
            @click="clearSelection"
            class="text-sm text-indigo-600 hover:text-indigo-800 underline"
          >
            Clear Selection
          </button>
        </div>
        <div class="flex gap-3">
          <button
            @click="openBulkApproveModal"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center"
          >
            <CheckIcon class="h-4 w-4 mr-2" />
            Bulk Approve
          </button>
          <button
            @click="openBulkRejectModal"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center"
          >
            <XMarkIcon class="h-4 w-4 mr-2" />
            Bulk Reject
          </button>
          <button
            @click="openBulkRevertModal"
            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center"
          >
            <ArrowPathIcon class="h-4 w-4 mr-2" />
            Bulk Revert
          </button>
          <button
            @click="confirmBulkDelete"
            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center"
          >
            <TrashIcon class="h-4 w-4 mr-2" />
            Bulk Delete
          </button>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-12">
      <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-indigo-600"></div>
      <span class="ml-3 text-gray-600">Loading applications...</span>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
      <div class="flex items-center">
        <ExclamationCircleIcon class="h-5 w-5 text-red-400" />
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Error loading applications</h3>
          <p class="text-sm text-red-600 mt-1">{{ error }}</p>
          <button @click="retrySearch" class="mt-2 text-sm text-red-600 hover:text-red-500 underline">
            Try again
          </button>
        </div>
      </div>
    </div>

    <!-- Applications Table -->
    <div v-else-if="applications.length > 0" class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left">
                <input
                  type="checkbox"
                  @change="toggleSelectAll"
                  :checked="isAllSelected"
                  class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >
              </th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                <button @click="sortBy('name')" class="flex items-center hover:text-gray-800">
                  Name
                  <ChevronUpIcon v-if="sortField === 'name' && sortDirection === 'asc'" class="ml-1 h-4 w-4" />
                  <ChevronDownIcon v-if="sortField === 'name' && sortDirection === 'desc'" class="ml-1 h-4 w-4" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">CNIC</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Post</th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                <button @click="sortBy('status')" class="flex items-center hover:text-gray-800">
                  Status
                  <ChevronUpIcon v-if="sortField === 'status' && sortDirection === 'asc'" class="ml-1 h-4 w-4" />
                  <ChevronDownIcon v-if="sortField === 'status' && sortDirection === 'desc'" class="ml-1 h-4 w-4" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">
                <button @click="sortBy('date')" class="flex items-center hover:text-gray-800">
                  Date
                  <ChevronUpIcon v-if="sortField === 'date' && sortDirection === 'asc'" class="ml-1 h-4 w-4" />
                  <ChevronDownIcon v-if="sortField === 'date' && sortDirection === 'desc'" class="ml-1 h-4 w-4" />
                </button>
              </th>
              <th class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="application in applications" :key="application.id"
                :class="{'bg-indigo-50': isSelected(application.id)}"
                class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <input
                  type="checkbox"
                  :checked="isSelected(application.id)"
                  @change="toggleSelection(application.id)"
                  class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ application.name }}</div>
                <div class="text-sm text-gray-500">{{ application.email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ application.cnic }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ application.post }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClasses(application.status)">
                  {{ application.statusText }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                {{ formatDate(application.date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex flex-wrap gap-2">
                  <button
                    @click="openDetailsModal(application)"
                    class="px-2 py-1 text-indigo-600 hover:text-indigo-800 flex items-center"
                    title="View Details"
                  >
                    <EyeIcon class="h-4 w-4 mr-1" />
                    View
                  </button>
                  <button
                    v-if="application.status !== 'approved'"
                    @click="updateStatus(application, 'approved')"
                    class="px-2 py-1 text-green-600 hover:text-green-800 flex items-center"
                    title="Approve Application"
                  >
                    <CheckIcon class="h-4 w-4 mr-1" />
                    Approve
                  </button>
                  <button
                    v-if="application.status !== 'rejected'"
                    @click="openRejectModal(application)"
                    class="px-2 py-1 text-red-600 hover:text-red-800 flex items-center"
                    title="Reject Application"
                  >
                    <XMarkIcon class="h-4 w-4 mr-1" />
                    Reject
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-100 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="previousPage"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Previous
          </button>
          <button
            @click="nextPage"
            :disabled="!hasNextPage"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
          >
            Next
          </button>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div class="flex items-center gap-4">
            <p class="text-sm text-gray-600">
              Showing page <span class="font-medium">{{ currentPage }}</span> of
              <span class="font-medium">{{ totalPages }}</span>
            </p>
            <select
              v-model="itemsPerPage"
              @change="changeItemsPerPage"
              class="border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500"
            >
              <option :value="10">10 per page</option>
              <option :value="25">25 per page</option>
              <option :value="50">50 per page</option>
              <option :value="100">100 per page</option>
            </select>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px">
              <button
                @click="previousPage"
                :disabled="currentPage === 1"
                class="relative inline-flex items-center px-2 py-2 rounded-l-lg border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
              >
                <ChevronLeftIcon class="h-5 w-5" />
              </button>

              <button
                v-for="page in visiblePages"
                :key="page"
                @click="goToPage(page)"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                  page === currentPage
                    ? 'bg-indigo-50 border-indigo-500 text-indigo-600 z-10'
                    : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>

              <button
                @click="nextPage"
                :disabled="!hasNextPage"
                class="relative inline-flex items-center px-2 py-2 rounded-r-lg border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
              >
                <ChevronRightIcon class="h-5 w-5" />
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && !error && !applications.length" class="text-center py-12">
      <DocumentIcon class="mx-auto h-12 w-12 text-gray-400" />
      <h3 class="mt-2 text-sm font-medium text-gray-900">No applications found</h3>
      <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria or filters.</p>
    </div>

    <!-- Details Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4" @click.self="closeDetailsModal">
      <div class="bg-white rounded-xl shadow-xl w-full max-w-7xl max-h-[90vh] flex flex-col">

        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
          <h2 class="text-xl font-semibold text-gray-900">Application Details</h2>
          <button @click="closeDetailsModal" class="text-gray-400 hover:text-gray-600 transition-colors">
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>

        <!-- Modal Body - Scrollable -->
        <div v-if="selectedApplication" class="overflow-y-auto px-6 py-4 flex-1">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <!-- Left Column -->
            <div class="space-y-4">

              <!-- Application Status Section -->
              <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                  <DocumentIcon class="h-5 w-5 mr-2 text-gray-600" />
                  Application Status
                </h3>
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Applied Post</label>
                    <p class="mt-0.5 text-sm font-semibold text-gray-900">{{ selectedApplication.post }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Current Status</label>
                    <span :class="getStatusClasses(selectedApplication.status)" class="mt-0.5 inline-block">
                      {{ statusText(selectedApplication.status) }}
                    </span>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Applied Date</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ formatDate(selectedApplication.applied_at) }}</p>
                  </div>
                  <div v-if="selectedApplication.rejection_reason">
                    <label class="block text-xs font-medium text-gray-600">Rejection Reason</label>
                    <p class="mt-0.5 text-sm text-red-700">{{ selectedApplication.rejection_reason }}</p>
                  </div>
                </div>
              </div>

              <!-- Personal Information Section -->
              <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                  <IdentificationIcon class="h-5 w-5 mr-2 text-blue-600" />
                  Personal Information
                </h3>
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Full Name</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.name }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Father's Name</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.father_name }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">CNIC</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.cnic }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Gender</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.gender }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Date of Birth</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ formatDate(selectedApplication.date_of_birth) }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Marital Status</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.marital_status }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Religion</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.religion }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Disability</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.disability }}</p>
                  </div>
                </div>
              </div>

              <!-- Contact Information Section -->
              <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                  <svg class="h-5 w-5 mr-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  Contact Information
                </h3>
                <div class="space-y-3">
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-xs font-medium text-gray-600">Email</label>
                      <p class="mt-0.5 text-sm text-gray-900 break-all">{{ selectedApplication.email }}</p>
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-gray-600">Mobile</label>
                      <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.mobile }}</p>
                    </div>
                    <div class="col-span-2">
                      <label class="block text-xs font-medium text-gray-600">Phone</label>
                      <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.phone }}</p>
                    </div>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Postal Address</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.postal_address }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-medium text-gray-600">Permanent Address</label>
                    <p class="mt-0.5 text-sm text-gray-900">{{ selectedApplication.permanent_address }}</p>
                  </div>
                </div>
              </div>

            </div>

            <!-- Right Column -->
            <div>
              <!-- Qualifications Section -->
              <div class="bg-purple-50 p-4 rounded-lg border border-purple-200 h-full">
                <h3 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                  <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                  </svg>
                  Educational Qualifications
                </h3>
                <div v-if="selectedApplication.qualifications && selectedApplication.qualifications.length > 0" class="space-y-2 max-h-[calc(90vh-200px)] overflow-y-auto pr-2">
                  <div v-for="(qual, index) in selectedApplication.qualifications" :key="index"
                       class="bg-white p-3 rounded-lg border border-purple-200 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                      <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                        {{ qual.degree_type }}
                      </span>
                      <span class="text-xs text-gray-500">{{ qual.passing_year }}</span>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 mb-1">{{ qual.degree_name }}</p>
                    <p class="text-xs text-gray-600">{{ qual.institute_name }}</p>
                  </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <p class="mt-2 text-sm">No qualification records found</p>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
          <button @click="closeDetailsModal" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            Close
          </button>
        </div>

      </div>
    </div>

    <!-- Rejection Modal -->
    <div v-if="showRejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50" @click.self="closeRejectModal">
      <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Provide Rejection Reason</h3>
        <textarea
          v-model="rejectionReason"
          rows="4"
          class="w-full border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500"
          placeholder="Enter reason for rejection"
        ></textarea>
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="closeRejectModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
            Cancel
          </button>
          <button
            @click="confirmReject"
            :disabled="!rejectionReason.trim()"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50"
          >
            Submit
          </button>
        </div>
      </div>
    </div>

    <!-- Bulk Action Modal -->
    <div v-if="showBulkActionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50" @click.self="closeBulkActionModal">
      <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ bulkActionTitle }}</h3>
        <p class="text-sm text-gray-600 mb-4">
          You are about to {{ bulkActionType }} {{ selectedApplications.length }} application(s).
        </p>
        <textarea
          v-if="bulkActionType === 'reject'"
          v-model="bulkRejectionReason"
          rows="4"
          class="w-full border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 mb-4"
          placeholder="Enter reason for rejection (required)"
        ></textarea>
        <div class="flex justify-end space-x-3">
          <button @click="closeBulkActionModal" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
            Cancel
          </button>
          <button
            @click="confirmBulkAction"
            :disabled="bulkActionType === 'reject' && !bulkRejectionReason.trim()"
            :class="[
              'px-4 py-2 text-white rounded-lg disabled:opacity-50',
              bulkActionType === 'approve' ? 'bg-green-600 hover:bg-green-700' :
              bulkActionType === 'reject' ? 'bg-red-600 hover:bg-red-700' :
              'bg-purple-600 hover:bg-purple-700'
            ]"
          >
            Confirm {{ bulkActionType }}
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics Modal -->
    <div v-if="showStatsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50" @click.self="showStatsModal = false">
      <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-4xl max-h-[95vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-semibold text-gray-900">Application Statistics</h2>
          <button @click="showStatsModal = false" class="text-gray-400 hover:text-gray-600">
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
          <div class="bg-blue-50 p-4 rounded-lg">
            <p class="text-sm font-medium text-blue-600">Total Applications</p>
            <p class="text-3xl font-bold text-blue-900 mt-2">{{ stats.total }}</p>
          </div>
          <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-sm font-medium text-green-600">Approved</p>
            <p class="text-3xl font-bold text-green-900 mt-2">{{ stats.approved }}</p>
          </div>
          <div class="bg-red-50 p-4 rounded-lg">
            <p class="text-sm font-medium text-red-600">Rejected</p>
            <p class="text-3xl font-bold text-red-900 mt-2">{{ stats.rejected }}</p>
          </div>
          <div class="bg-yellow-50 p-4 rounded-lg">
            <p class="text-sm font-medium text-yellow-600">Pending</p>
            <p class="text-3xl font-bold text-yellow-900 mt-2">{{ stats.pending }}</p>
          </div>
          <div class="bg-purple-50 p-4 rounded-lg">
            <p class="text-sm font-medium text-purple-600">Reverted</p>
            <p class="text-3xl font-bold text-purple-900 mt-2">{{ stats.reverted }}</p>
          </div>
          <div class="bg-indigo-50 p-4 rounded-lg">
            <p class="text-sm font-medium text-indigo-600">Approval Rate</p>
            <p class="text-3xl font-bold text-indigo-900 mt-2">{{ stats.approvalRate }}%</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Audit Log Modal -->
    <div v-if="showAuditLog" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50" @click.self="showAuditLog = false">
      <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-4xl max-h-[95vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-semibold text-gray-900">Audit Log</h2>
          <button @click="showAuditLog = false" class="text-gray-400 hover:text-gray-600">
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>

        <div class="space-y-4">
          <div v-for="log in auditLogs" :key="log.id" class="border-l-4 border-indigo-500 bg-gray-50 p-4 rounded-r-lg">
            <div class="flex justify-between items-start">
              <div>
                <p class="text-sm font-medium text-gray-900">{{ log.action }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ log.description }}</p>
                <p class="text-xs text-gray-500 mt-2">By: {{ log.user }} | {{ formatDate(log.timestamp) }}</p>
              </div>
              <span :class="getAuditBadgeClass(log.type)" class="px-2 py-1 text-xs rounded-full">
                {{ log.type }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { debounce } from 'lodash';
import {
  MagnifyingGlassIcon,
  IdentificationIcon,
  BriefcaseIcon,
  CheckCircleIcon,
  CalendarIcon,
  ArrowPathIcon,
  ArrowDownTrayIcon,
  ExclamationCircleIcon,
  ChevronUpIcon,
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  DocumentIcon,
  EyeIcon,
  CheckIcon,
  XMarkIcon,
  TrashIcon,
  XCircleIcon,
  ClockIcon,
  ChartBarIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
  posts: {
    type: Array,
    default: () => []
  },
  baseUrl: {
    type: String,
    default: '/api/applications'
  }
});

// State
const applications = ref([]);
const loading = ref(false);
const error = ref(null);
const totalResults = ref(0);
const currentPage = ref(1);
const totalPages = ref(1);
const itemsPerPage = ref(10);
const selectedApplications = ref([]);
const showAdvancedFilters = ref(false);

const filters = ref({
  name: '',
  cnic: '',
  post: '',
  status: '',
  date: '',
  dateFrom: '',
  dateTo: '',
  email: '',
  mobile: ''
});

const sortField = ref('date');
const sortDirection = ref('desc');

// Modal states
const showModal = ref(false);
const selectedApplication = ref(null);
const showRejectModal = ref(false);
const rejectionReason = ref('');
const rejectingApplication = ref(null);
const showBulkActionModal = ref(false);
const bulkActionType = ref('');
const bulkRejectionReason = ref('');
const showStatsModal = ref(false);
const showAuditLog = ref(false);

// Statistics
const stats = ref({
  total: 0,
  approved: 0,
  rejected: 0,
  pending: 0,
  reverted: 0,
  approvalRate: 0
});

const auditLogs = ref([
  {
    id: 1,
    action: 'Bulk Approval',
    description: '15 applications approved',
    user: 'Admin User',
    timestamp: new Date(),
    type: 'approval'
  },
  {
    id: 2,
    action: 'Application Rejected',
    description: 'Application #12345 rejected - Incomplete documents',
    user: 'Admin User',
    timestamp: new Date(),
    type: 'rejection'
  }
]);

// Computed
const currentPageResults = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value + 1;
  const end = Math.min(start + applications.value.length - 1, totalResults.value);
  return `${start}-${end}`;
});

const hasNextPage = computed(() => currentPage.value < totalPages.value);

const isAllSelected = computed(() => {
  return applications.value.length > 0 &&
         selectedApplications.value.length === applications.value.length;
});

const visiblePages = computed(() => {
  const pages = [];
  const maxVisible = 5;
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
  let end = Math.min(totalPages.value, start + maxVisible - 1);

  if (end - start < maxVisible - 1) {
    start = Math.max(1, end - maxVisible + 1);
  }

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
});

const bulkActionTitle = computed(() => {
  const titles = {
    approve: 'Bulk Approve Applications',
    reject: 'Bulk Reject Applications',
    revert: 'Bulk Revert Applications'
  };
  return titles[bulkActionType.value] || 'Bulk Action';
});

// Methods
const fetchApplications = async (resetPage = false) => {
  if (resetPage) currentPage.value = 1;

  loading.value = true;
  error.value = null;

  try {
    const params = new URLSearchParams({
      page: currentPage.value.toString(),
      per_page: itemsPerPage.value.toString(),
      sort_field: sortField.value,
      sort_direction: sortDirection.value,
      ...Object.fromEntries(
        Object.entries(filters.value).filter(([_, value]) => value !== '')
      )
    });

    const response = await fetch(`${props.baseUrl}?${params}`);
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

    const data = await response.json();

    applications.value = data.data.map(app => ({
      ...app,
      statusText: statusText(app.status)
    }));
    totalResults.value = data.total || 0;
    totalPages.value = data.last_page || 1;
    currentPage.value = data.current_page || 1;

    // Update statistics
    updateStatistics(data.statistics);

  } catch (err) {
    error.value = err.message || 'Failed to fetch applications';
    console.error('Fetch error:', err);
  } finally {
    loading.value = false;
  }
};

const updateStatistics = (statsData) => {
  if (statsData) {
    stats.value = {
      total: statsData.total || 0,
      approved: statsData.approved || 0,
      rejected: statsData.rejected || 0,
      pending: statsData.pending || 0,
      reverted: statsData.reverted || 0,
      approvalRate: statsData.total > 0
        ? Math.round((statsData.approved / statsData.total) * 100)
        : 0
    };
  }
};

const applyFilters = () => {
  selectedApplications.value = [];
  fetchApplications(true);
};

const debouncedSearch = debounce(() => {
  applyFilters();
}, 500);

const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
  applyFilters();
};

const resetFilters = () => {
  filters.value = {
    name: '',
    cnic: '',
    post: '',
    status: '',
    date: '',
    dateFrom: '',
    dateTo: '',
    email: '',
    mobile: ''
  };
  sortField.value = 'date';
  sortDirection.value = 'desc';
  selectedApplications.value = [];
  applyFilters();
};

const toggleAdvancedFilters = () => {
  showAdvancedFilters.value = !showAdvancedFilters.value;
};

const retrySearch = () => {
  fetchApplications();
};

const exportResults = async () => {
  try {
    const params = new URLSearchParams({
      export: 'csv',
      ...Object.fromEntries(
        Object.entries(filters.value).filter(([_, value]) => value !== '')
      )
    });

    const response = await fetch(`${props.baseUrl}/export?${params}`);
    if (!response.ok) throw new Error('Export failed');

    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `applications_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);

  } catch (err) {
    console.error('Export failed:', err);
    alert('Export failed. Please try again.');
  }
};

// Selection methods
const toggleSelection = (id) => {
  const index = selectedApplications.value.indexOf(id);
  if (index > -1) {
    selectedApplications.value.splice(index, 1);
  } else {
    selectedApplications.value.push(id);
  }
};

const toggleSelectAll = () => {
  if (isAllSelected.value) {
    selectedApplications.value = [];
  } else {
    selectedApplications.value = applications.value.map(app => app.id);
  }
};

const isSelected = (id) => {
  return selectedApplications.value.includes(id);
};

const clearSelection = () => {
  selectedApplications.value = [];
};

// Bulk actions
const openBulkApproveModal = () => {
  bulkActionType.value = 'approve';
  showBulkActionModal.value = true;
};

const openBulkRejectModal = () => {
  bulkActionType.value = 'reject';
  bulkRejectionReason.value = '';
  showBulkActionModal.value = true;
};

const openBulkRevertModal = () => {
  bulkActionType.value = 'revert';
  showBulkActionModal.value = true;
};

const closeBulkActionModal = () => {
  showBulkActionModal.value = false;
  bulkActionType.value = '';
  bulkRejectionReason.value = '';
};

const confirmBulkAction = async () => {
  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) throw new Error('CSRF token not found');

    const body = {
      ids: selectedApplications.value,
      status: bulkActionType.value === 'approve' ? 'approved' :
              bulkActionType.value === 'reject' ? 'rejected' : 'reverted'
    };

    if (bulkActionType.value === 'reject') {
      body.rejection_reason = bulkRejectionReason.value.trim();
    }

    const response = await fetch(`${props.baseUrl}/bulk-update`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify(body)
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'Bulk action failed');
    }

    const data = await response.json();

    // Add to audit log
    auditLogs.value.unshift({
      id: Date.now(),
      action: `Bulk ${bulkActionType.value}`,
      description: `${selectedApplications.value.length} applications ${bulkActionType.value}ed`,
      user: 'Current User',
      timestamp: new Date(),
      type: bulkActionType.value
    });

    closeBulkActionModal();
    clearSelection();
    fetchApplications();

    alert(`Successfully ${bulkActionType.value}ed ${selectedApplications.value.length} application(s)`);

  } catch (err) {
    console.error('Bulk action failed:', err);
    alert(`Failed to ${bulkActionType.value} applications: ${err.message}`);
  }
};

const confirmBulkDelete = async () => {
  if (!confirm(`Are you sure you want to delete ${selectedApplications.value.length} application(s)? This action cannot be undone.`)) {
    return;
  }

  try {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) throw new Error('CSRF token not found');

    const response = await fetch(`${props.baseUrl}/bulk-delete`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ ids: selectedApplications.value })
    });

    if (!response.ok) throw new Error('Bulk delete failed');

    clearSelection();
    fetchApplications();

    alert('Applications deleted successfully');

  } catch (err) {
    console.error('Bulk delete failed:', err);
    alert('Failed to delete applications');
  }
};

// Status update
const updateStatus = async (application, status) => {
  try {
    const body = { status };
    if (status === 'rejected' && !rejectionReason.value.trim()) {
      openRejectModal(application);
      return;
    }
    if (status === 'rejected') {
      body.rejection_reason = rejectionReason.value.trim();
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) throw new Error('CSRF token not found');

    const response = await fetch(`${props.baseUrl}/${application.id}/status`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify(body)
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'Status update failed');
    }

    const data = await response.json();

    applications.value = applications.value.map(app =>
      app.id === application.id
        ? { ...app, status: data.application.status, statusText: statusText(data.application.status) }
        : app
    );

    if (status === 'rejected') {
      closeRejectModal();
    }

    fetchApplications(false);

  } catch (err) {
    console.error('Status update failed:', err);
    alert(`Failed to update status: ${err.message}`);
  }
};

// Pagination
const nextPage = () => {
  if (hasNextPage.value) {
    currentPage.value++;
    fetchApplications();
  }
};

const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    fetchApplications();
  }
};

const goToPage = (page) => {
  currentPage.value = page;
  fetchApplications();
};

const changeItemsPerPage = () => {
  fetchApplications(true);
};

// Modals
const openDetailsModal = async (application) => {
  try {
    const response = await fetch(`${props.baseUrl}/${application.id}`);
    if (!response.ok) throw new Error('Failed to fetch details');

    const data = await response.json();
    selectedApplication.value = {
      ...data,
      statusText: statusText(data.status)
    };
    showModal.value = true;
  } catch (err) {
    console.error('Error:', err);
    alert('Failed to load application details');
  }
};

const closeDetailsModal = () => {
  showModal.value = false;
  selectedApplication.value = null;
};

const openRejectModal = (application) => {
  rejectingApplication.value = application;
  showRejectModal.value = true;
  rejectionReason.value = '';
};

const closeRejectModal = () => {
  showRejectModal.value = false;
  rejectingApplication.value = null;
  rejectionReason.value = '';
};

const confirmReject = () => {
  if (rejectingApplication.value && rejectionReason.value.trim()) {
    updateStatus(rejectingApplication.value, 'rejected');
  }
};

// Utility
const getStatusClasses = (status) => {
  const baseClasses = 'px-2 py-1 rounded-full text-xs font-semibold';
  const statusClasses = {
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
    reverted: 'bg-purple-100 text-purple-800',
    pending: 'bg-yellow-100 text-yellow-800'
  };
  return `${baseClasses} ${statusClasses[status] || 'bg-gray-100 text-gray-800'}`;
};

const getAuditBadgeClass = (type) => {
  const classes = {
    approval: 'bg-green-100 text-green-800',
    rejection: 'bg-red-100 text-red-800',
    update: 'bg-blue-100 text-blue-800'
  };
  return classes[type] || 'bg-gray-100 text-gray-800';
};

const statusText = (status) => {
  return status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Pending';
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  return new Date(dateString).toLocaleDateString('en-GB', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

// Lifecycle
onMounted(() => {
  fetchApplications();
});
</script>
