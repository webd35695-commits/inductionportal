<template>
  <div class="flex h-screen bg-gray-50 font-sans">
    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-gray-200 flex flex-col shadow-sm z-10">
      <div class="p-6 border-b border-gray-100">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200">
            R
          </div>
          <h2 class="text-xl font-bold text-gray-800 tracking-tight">Analytics Hub</h2>
        </div>
        <p class="text-xs text-gray-500 mt-2 ml-11">Allocation & Verification</p>
      </div>
      
      <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
        <template v-for="(group, groupName) in reportGroups" :key="groupName">
          <div class="px-3 mb-2 mt-6 first:mt-0 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            {{ groupName }}
          </div>
          <a 
            v-for="(report, key) in group" 
            :key="key"
            @click="selectReport(key)"
            :class="[
              'group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 cursor-pointer',
              currentReport === key 
                ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' 
                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
            ]"
          >
            <span :class="['w-1.5 h-1.5 rounded-full mr-3 transition-colors', currentReport === key ? 'bg-blue-500' : 'bg-gray-300 group-hover:bg-gray-400']"></span>
            {{ report.label }}
          </a>
        </template>
      </nav>

      <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        <div class="flex items-center space-x-3">
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">System Status</p>
            <p class="text-xs text-green-600 flex items-center mt-0.5">
              <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
              Operational
            </p>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-hidden flex flex-col bg-gray-50 relative">
      <!-- Top Bar -->
      <header class="bg-white border-b border-gray-200 px-8 py-5 flex justify-between items-center shadow-sm z-10">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ reportsList[currentReport]?.label }}</h1>
          <p class="text-sm text-gray-500 mt-1">Real-time data verification and analysis</p>
        </div>
        <div class="flex items-center space-x-4">
          <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Last updated: {{ lastUpdated }}</span>
          <button 
            @click="fetchData(1)" 
            :disabled="loading"
            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 flex items-center text-sm font-medium shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg :class="['w-4 h-4 mr-2', loading ? 'animate-spin text-blue-600' : 'text-gray-500']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            {{ loading ? 'Refreshing...' : 'Refresh Data' }}
          </button>
        </div>
      </header>

      <!-- Content Area -->
      <div class="flex-1 overflow-auto p-8 relative">
        <!-- Loading Overlay -->
        <div v-if="loading && !reportData" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-20 flex items-center justify-center">
          <div class="flex flex-col items-center">
            <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-4"></div>
            <p class="text-gray-500 font-medium animate-pulse">Loading analytics...</p>
          </div>
        </div>

        <!-- Error State -->
        <div v-if="error" class="max-w-2xl mx-auto mt-10 p-6 bg-red-50 border border-red-200 rounded-xl text-center">
          <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
          </div>
          <h3 class="text-lg font-bold text-gray-900 mb-2">Failed to load report</h3>
          <p class="text-gray-600 mb-6">{{ error }}</p>
          <button @click="fetchData(1)" class="text-blue-600 font-medium hover:text-blue-800 underline">Try Again</button>
        </div>

        <!-- Data Display -->
        <div v-else-if="reportData" class="space-y-6">
          
          <!-- Summary Cards (if available in future) -->
          <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">...</div> -->

        <!-- Multi Post Applicants Report -->
        <div v-if="currentReport === 'multi_post_applicants'" class="space-y-4">
          <!-- Filters -->
          <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 grid grid-cols-1 md:grid-cols-5 gap-4">
            <input v-model="filters.name" @input="debouncedFetch" type="text" placeholder="Search Name" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <input v-model="filters.cnic" @input="debouncedFetch" type="text" placeholder="Search CNIC" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <input v-model="filters.roll_no" @input="debouncedFetch" type="text" placeholder="Search Roll No" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <input v-model="filters.center" @input="debouncedFetch" type="text" placeholder="Search Center" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            <input v-model="filters.city" @input="debouncedFetch" type="text" placeholder="Search City" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Candidate Profile</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact Info</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applications</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Allocation Status</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <template v-for="applicant in reportData.data" :key="applicant.user_id">
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer" @click="applicant.showDetails = !applicant.showDetails">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            {{ applicant.name.charAt(0) }}
                          </div>
                          <div class="ml-4">
                            <div class="text-sm font-bold text-gray-900">{{ applicant.name }}</div>
                            <div class="text-xs text-gray-500 font-mono mt-0.5">{{ applicant.cnic }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ applicant.email }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ applicant.mobile }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-medium">{{ applicant.posts_count }} Posts Applied</div>
                        <div class="text-xs text-blue-500 mt-0.5">Click to expand</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="['px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border', applicant.is_same_center ? 'bg-green-50 text-green-700 border-green-200' : 'bg-amber-50 text-amber-700 border-amber-200']">
                          <span :class="['w-1.5 h-1.5 rounded-full mr-2 my-auto', applicant.is_same_center ? 'bg-green-500' : 'bg-amber-500']"></span>
                          {{ applicant.is_same_center ? 'Unified Center' : 'Split Centers' }}
                        </span>
                      </td>
                    </tr>
                    <!-- Detailed Breakdown Row -->
                    <tr v-if="applicant.showDetails || true" class="bg-gray-50/50 shadow-inner transition-all duration-300">
                      <td colspan="4" class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                          <div v-for="(post, idx) in applicant.posts" :key="idx" class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-2">
                              <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                {{ post.post_name }}
                              </span>
                              <span :class="['text-xs font-medium', post.status === 'Allocated' ? 'text-green-600' : 'text-gray-400']">
                                {{ post.status }}
                              </span>
                            </div>
                            <div class="mt-2">
                              <p class="text-sm font-semibold text-gray-900 truncate cursor-pointer hover:text-blue-600 hover:underline" :title="post.center_name" @click.stop="showCenterDetails(post)">
                                {{ post.center_name }}
                              </p>
                              <div class="flex justify-between items-center mt-1">
                                <div class="flex items-center text-xs text-gray-500">
                                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                  {{ post.city_name }}
                                </div>
                                <span class="text-xs font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-600" v-if="post.roll_number !== 'N/A'">
                                  Roll: {{ post.roll_number }}
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
            <!-- Pagination -->
            <div v-if="reportData.links" class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ reportData.from }}</span>
                    to
                    <span class="font-medium">{{ reportData.to }}</span>
                    of
                    <span class="font-medium">{{ reportData.total }}</span>
                    results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button 
                      v-for="(link, i) in reportData.links" 
                      :key="i"
                      @click="link.url && fetchData(getPageFromUrl(link.url))"
                      :disabled="!link.url"
                      v-html="link.label"
                      :class="[
                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                        link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                        i === 0 ? 'rounded-l-md' : '',
                        i === reportData.links.length - 1 ? 'rounded-r-md' : '',
                        !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                      ]"
                    >
                    </button>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Center Details Modal -->
        <div v-if="selectedCenter" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
          <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="selectedCenter = null"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
              <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                  <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                  </div>
                  <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                      {{ selectedCenter.center_name }}
                    </h3>
                    <div class="mt-2">
                      <p class="text-sm text-gray-500 flex items-center mb-2">
                         <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                         {{ selectedCenter.city_name }}
                      </p>
                      <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-sm space-y-2">
                        <div class="flex justify-between">
                          <span class="text-gray-600">Post:</span>
                          <span class="font-medium text-gray-900">{{ selectedCenter.post_name }}</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-gray-600">Roll Number:</span>
                          <span class="font-mono font-medium text-gray-900">{{ selectedCenter.roll_number }}</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-gray-600">Status:</span>
                          <span class="px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">{{ selectedCenter.status }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" @click="selectedCenter = null">
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>

          <!-- Center Utilization Report -->
          <div v-else-if="currentReport === 'center_utilization' || currentReport === 'center_load_balance'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
             <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Center Details</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Capacity Status</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Utilization Rate</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(center, idx) in reportData.data || reportData" :key="idx" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ center.center_name }}</div>
                    <div class="text-xs text-gray-500 flex items-center mt-1">
                      <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                      {{ center.city_name }}
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                      <span class="font-bold">{{ center.allocated }}</span> 
                      <span class="text-gray-400 mx-1">/</span> 
                      <span class="text-gray-600">{{ center.capacity }}</span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5">Applicants Allocated</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap align-middle">
                    <div class="w-full max-w-xs">
                      <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-semibold text-gray-600">{{ center.utilization }}%</span>
                      </div>
                      <div class="w-full bg-gray-100 rounded-full h-2">
                        <div 
                          :class="['h-2 rounded-full transition-all duration-500', 
                            center.utilization > 90 ? 'bg-red-500' : 
                            center.utilization > 75 ? 'bg-amber-500' : 
                            'bg-blue-500'
                          ]" 
                          :style="`width: ${Math.min(center.utilization, 100)}%`"
                        ></div>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Pagination for Center Reports -->
            <div v-if="reportData.links" class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
               <!-- Reuse pagination component logic here or abstract it -->
               <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ reportData.from }}</span> to <span class="font-medium">{{ reportData.to }}</span> of <span class="font-medium">{{ reportData.total }}</span> results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button v-for="(link, i) in reportData.links" :key="i" @click="link.url && fetchData(getPageFromUrl(link.url))" :disabled="!link.url" v-html="link.label" :class="['relative inline-flex items-center px-4 py-2 border text-sm font-medium', link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', i === 0 ? 'rounded-l-md' : '', i === reportData.links.length - 1 ? 'rounded-r-md' : '', !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"></button>
                  </nav>
                </div>
              </div>
            </div>
          </div>

          <!-- Post Distribution Report -->
          <div v-else-if="currentReport === 'post_distribution'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Post Name</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Applicants</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Distribution</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(post, idx) in reportData.data" :key="idx" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ post.post_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ post.count }}</td>
                  <td class="px-6 py-4 whitespace-nowrap align-middle">
                    <div class="w-full max-w-xs bg-gray-100 rounded-full h-2">
                      <div class="bg-indigo-500 h-2 rounded-full" :style="`width: ${Math.min((post.count / 100) * 10, 100)}%`"></div> <!-- Normalized for visual -->
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
             <!-- Pagination -->
            <div v-if="reportData.links" class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
               <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ reportData.from }}</span> to <span class="font-medium">{{ reportData.to }}</span> of <span class="font-medium">{{ reportData.total }}</span> results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button v-for="(link, i) in reportData.links" :key="i" @click="link.url && fetchData(getPageFromUrl(link.url))" :disabled="!link.url" v-html="link.label" :class="['relative inline-flex items-center px-4 py-2 border text-sm font-medium', link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', i === 0 ? 'rounded-l-md' : '', i === reportData.links.length - 1 ? 'rounded-r-md' : '', !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"></button>
                  </nav>
                </div>
              </div>
            </div>
          </div>

          <!-- Unallocated List Report -->
          <div v-else-if="currentReport === 'unallocated_list'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Candidate</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Post</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Preferred City</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applied Date</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(app, idx) in reportData.data" :key="idx" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ app.candidate_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ app.post_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ app.preferred_city }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ app.applied_at }}</td>
                </tr>
              </tbody>
            </table>
             <!-- Pagination -->
            <div v-if="reportData.links" class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
               <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ reportData.from }}</span> to <span class="font-medium">{{ reportData.to }}</span> of <span class="font-medium">{{ reportData.total }}</span> results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button v-for="(link, i) in reportData.links" :key="i" @click="link.url && fetchData(getPageFromUrl(link.url))" :disabled="!link.url" v-html="link.label" :class="['relative inline-flex items-center px-4 py-2 border text-sm font-medium', link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', i === 0 ? 'rounded-l-md' : '', i === reportData.links.length - 1 ? 'rounded-r-md' : '', !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"></button>
                  </nav>
                </div>
              </div>
            </div>
          </div>

          <!-- City Wise Summary Report -->
          <div v-else-if="currentReport === 'city_wise_summary'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">City Name</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Applicants</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(city, idx) in reportData.data" :key="idx" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ city.city_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ city.applicants_count }}</td>
                </tr>
              </tbody>
            </table>
             <!-- Pagination -->
            <div v-if="reportData.links" class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
               <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ reportData.from }}</span> to <span class="font-medium">{{ reportData.to }}</span> of <span class="font-medium">{{ reportData.total }}</span> results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button v-for="(link, i) in reportData.links" :key="i" @click="link.url && fetchData(getPageFromUrl(link.url))" :disabled="!link.url" v-html="link.label" :class="['relative inline-flex items-center px-4 py-2 border text-sm font-medium', link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50', i === 0 ? 'rounded-l-md' : '', i === reportData.links.length - 1 ? 'rounded-r-md' : '', !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer']"></button>
                  </nav>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Duplicate Check Report -->
          <div v-else-if="currentReport === 'duplicate_check'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
             <div v-if="reportData.data.length === 0" class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-lg font-medium">No Duplicate Roll Numbers Found</p>
                <p class="text-sm">All allocations are unique.</p>
             </div>
             <table v-else class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Roll Number</th>
                  <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Issue</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, idx) in reportData.data" :key="idx" class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">{{ item.roll_number }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Duplicate Assignment Detected</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Overall Summary Report -->
          <div v-else-if="currentReport === 'overall_summary'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
              <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Applicants</div>
              <div class="text-3xl font-bold text-gray-900">{{ reportData.total_applicants }}</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
              <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Allocated</div>
              <div class="text-3xl font-bold text-green-600">{{ reportData.total_allocated }}</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
              <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Active Centers</div>
              <div class="text-3xl font-bold text-blue-600">{{ reportData.total_centers }}</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
              <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Posts</div>
              <div class="text-3xl font-bold text-indigo-600">{{ reportData.total_posts }}</div>
            </div>
          </div>
          
          <!-- City Preference Stats Report -->
          <div v-else-if="currentReport === 'city_preference_stats'" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
             <h3 class="text-lg font-medium text-gray-900 mb-6">Allocation Preference Analysis</h3>
             <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-100">
                   <div class="text-2xl font-bold text-green-700">{{ reportData.city_1 }}</div>
                   <div class="text-sm text-green-600 mt-1">Preferred City (1st Choice)</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                   <div class="text-2xl font-bold text-blue-700">{{ reportData.city_2 }}</div>
                   <div class="text-sm text-blue-600 mt-1">Alternative City (2nd Choice)</div>
                </div>
                <div class="text-center p-4 bg-amber-50 rounded-lg border border-amber-100">
                   <div class="text-2xl font-bold text-amber-700">{{ reportData.other }}</div>
                   <div class="text-sm text-amber-600 mt-1">Other Cities</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg border border-red-100">
                   <div class="text-2xl font-bold text-red-700">{{ reportData.unmatched }}</div>
                   <div class="text-sm text-red-600 mt-1">Unmatched / Pending</div>
                </div>
             </div>
          </div>
          
          <!-- Fallback for any other report -->
          <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900">Raw Data View</h3>
              <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md font-mono">JSON Format</span>
            </div>
            <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-auto text-xs font-mono max-h-[600px]">{{ JSON.stringify(reportData, null, 2) }}</pre>
          </div>
        </div>
        
        <!-- Empty State -->
        <div v-else-if="!loading" class="flex flex-col items-center justify-center h-96 text-gray-400">
          <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          <p class="text-lg font-medium">Select a report to view analytics</p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const reportGroups = {
  'Critical Verification': {
    'multi_post_applicants': { label: 'Multi-Post Verification' },
    'duplicate_check': { label: 'Duplicate Roll No Check' },
    'unallocated_list': { label: 'Unallocated Applicants' },
  },
  'Center Analytics': {
    'center_utilization': { label: 'Center Utilization' },
    'center_load_balance': { label: 'Load Balance Analysis' },
  },
  'Demographics': {
    'city_preference_stats': { label: 'City Preferences' },
    'city_wise_summary': { label: 'City-wise Summary' },
    'post_distribution': { label: 'Post Distribution' },
  },
  'System': {
    'overall_summary': { label: 'Overall Summary' },
  }
};

// Flatten for easy access
const reportsList = Object.values(reportGroups).reduce((acc, group) => ({ ...acc, ...group }), {});

const currentReport = ref('multi_post_applicants');
const reportData = ref(null);
const loading = ref(false);
const error = ref(null);
const lastUpdated = ref('Never');

const selectReport = (key) => {
  currentReport.value = key;
};

const fetchData = async (page = 1) => {
  loading.value = true;
  error.value = null;
  
  // Don't clear data immediately to avoid flash, unless changing report type
  // reportData.value = null; 

  try {
    const response = await axios.get(`/reports/data`, {
      params: {
        type: currentReport.value,
        page: page,
        per_page: 15
      }
    });
    reportData.value = response.data;
    lastUpdated.value = new Date().toLocaleTimeString();
  } catch (err) {
    error.value = 'Failed to load report data. Please try again.';
    console.error(err);
    reportData.value = null;
  } finally {
    loading.value = false;
  }
};

const getPageFromUrl = (url) => {
  if (!url) return 1;
  const urlObj = new URL(url);
  return urlObj.searchParams.get('page');
};

watch(currentReport, () => {
  reportData.value = null; // Clear old data when switching reports
  fetchData(1);
});

onMounted(() => {
  fetchData(1);
});
</script>
