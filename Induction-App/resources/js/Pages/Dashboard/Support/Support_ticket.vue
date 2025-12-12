<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-teal-50 to-gray-50 py-8">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Support Ticket</h1>
          <p class="text-gray-600">Tell us about your issue and we'll help you resolve it</p>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600">
          <a href="#" class="hover:text-teal-600 transition-colors">Home</a>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <Link :href="route('Support.index')" class="hover:text-teal-600 transition-colors">Support Tickets</Link>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
          <span class="text-gray-900 font-medium">Create Ticket</span>
        </nav>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- Sidebar - Recent Tickets -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sticky top-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Recent Tickets
            </h3>

            <div class="space-y-3">
              <div class="border border-gray-200 rounded-lg p-3 hover:border-teal-300 hover:shadow-sm transition-all cursor-pointer">
                <div class="flex items-start justify-between mb-2">
                  <span class="text-sm font-medium text-gray-900 line-clamp-1">#713838</span>
                  <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                </div>
                <p class="text-xs text-gray-600 mb-2 line-clamp-1">Payment Issue</p>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-emerald-600 font-medium">Open</span>
                  <span class="text-gray-500">10h ago</span>
                </div>
              </div>

              <div class="border border-gray-200 rounded-lg p-3 hover:border-teal-300 hover:shadow-sm transition-all cursor-pointer">
                <div class="flex items-start justify-between mb-2">
                  <span class="text-sm font-medium text-gray-900 line-clamp-1">#566223</span>
                  <span class="w-2 h-2 bg-gray-500 rounded-full"></span>
                </div>
                <p class="text-xs text-gray-600 mb-2 line-clamp-1">Invoice Query</p>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-gray-600 font-medium">Closed</span>
                  <span class="text-gray-500">1y ago</span>
                </div>
              </div>

              <div class="border border-gray-200 rounded-lg p-3 hover:border-teal-300 hover:shadow-sm transition-all cursor-pointer">
                <div class="flex items-start justify-between mb-2">
                  <span class="text-sm font-medium text-gray-900 line-clamp-1">#618935</span>
                  <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                </div>
                <p class="text-xs text-gray-600 mb-2 line-clamp-1">Technical Support</p>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-amber-600 font-medium">On Hold</span>
                  <span class="text-gray-500">1y ago</span>
                </div>
              </div>
            </div>

            <!-- Help Box -->
            <div class="mt-6 p-4 bg-gradient-to-br from-teal-50 to-blue-50 rounded-lg border border-teal-100">
              <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                  <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <h4 class="text-sm font-semibold text-gray-900 mb-1">Need Quick Help?</h4>
                  <p class="text-xs text-gray-600">Check our FAQ or knowledge base before creating a ticket.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Form Header -->
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
              <h2 class="text-xl font-semibold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Ticket Information
              </h2>
            </div>

            <div class="p-6">
              <!-- Contact Info (Read-only) -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      Name
                    </label>
                    <input
                      type="text"
                      v-model="ticketForm.name"
                      disabled
                      class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      Email Address
                    </label>
                    <input
                      type="email"
                      v-model="ticketForm.email"
                      disabled
                      class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-600 cursor-not-allowed"
                    />
                  </div>
                </div>
              </div>

              <!-- Ticket Details -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ticket Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Department <span class="text-red-500">*</span>
                    </label>
                    <select
                      v-model="ticketForm.department"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                    >
                      <option value="">Select Department</option>
                      <option value="Admin">Admin</option>
                      <option value="Induction">Induction Department</option>
                      <option value="Technical Support">Technical Support</option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Related Service
                    </label>
                    <select
                      v-model="ticketForm.relatedService"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                    >
                      <option value="">None</option>
                      <option value="hosting">Technical Issue</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Priority <span class="text-red-500">*</span>
                    </label>
                    <select
                      v-model="ticketForm.priority"
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                    >
                      <option value="low">🟢 Low</option>
                      <option value="medium">🔵 Medium</option>
                      <option value="high">🟠 High</option>
                      <option value="urgent">🔴 Urgent</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Message Section -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Message</h3>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Subject <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="text"
                    v-model="ticketForm.subject"
                    placeholder="Brief description of your issue"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                  />
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                  </label>

                  <!-- Toolbar -->
                  <div class="border border-gray-300 rounded-t-lg bg-gray-50 px-3 py-2 flex items-center gap-1">
                    <button type="button" class="p-2 hover:bg-gray-200 rounded transition-colors" title="Bold">
                      <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h4a4 4 0 004-4 4 4 0 00-1.5-3.1A3.5 3.5 0 0014 7.5V4a2 2 0 00-2-2H6zm2 2h4v3.5a1.5 1.5 0 01-3 0V6H8V4zm0 6h3a2 2 0 010 4H8v-4z" />
                      </svg>
                    </button>
                    <button type="button" class="p-2 hover:bg-gray-200 rounded transition-colors" title="Italic">
                      <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.5 4h3l-.5 2h-1l-2 8h1l-.5 2h-3l.5-2h1l2-8h-1L8.5 4z" />
                      </svg>
                    </button>
                    <button type="button" class="p-2 hover:bg-gray-200 rounded transition-colors" title="Underline">
                      <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 18h12v2H4v-2zm6-16C6.686 2 4 4.686 4 8v4c0 3.314 2.686 6 6 6s6-2.686 6-6V8c0-3.314-2.686-6-6-6z" />
                      </svg>
                    </button>
                    <div class="w-px h-6 bg-gray-300 mx-2"></div>
                    <button type="button" class="p-2 hover:bg-gray-200 rounded transition-colors" title="Link">
                      <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5z" />
                      </svg>
                    </button>
                    <button type="button" class="p-2 hover:bg-gray-200 rounded transition-colors" title="Bullet List">
                      <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 000 2h.01a1 1 0 100-2H3zM6 4a1 1 0 000 2h11a1 1 0 100-2H6zM3 10a1 1 0 100 2h.01a1 1 0 100-2H3zM6 10a1 1 0 100 2h11a1 1 0 100-2H6zM3 16a1 1 0 100 2h.01a1 1 0 100-2H3zM6 16a1 1 0 100 2h11a1 1 0 100-2H6z" />
                      </svg>
                    </button>
                    <button type="button" class="p-2 hover:bg-gray-200 rounded transition-colors" title="Numbered List">
                      <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 000 2v1a1 1 0 001 1h1a1 1 0 100-2H4V4a1 1 0 00-1 0zM3 13v-1a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 110 2H3a1 1 0 01-1-1v-2a1 1 0 011-1zM8 4a1 1 0 000 2h9a1 1 0 100-2H8zM8 10a1 1 0 000 2h9a1 1 0 100-2H8zM8 16a1 1 0 000 2h9a1 1 0 100-2H8z" />
                      </svg>
                    </button>
                  </div>

                  <!-- Text Area -->
                  <textarea
                    v-model="ticketForm.message"
                    @input="updateWordCount"
                    rows="8"
                    placeholder="Please describe your issue in detail. Include any error messages, steps to reproduce, or relevant information..."
                    class="w-full px-4 py-3 border border-gray-300 border-t-0 rounded-b-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent resize-none transition-all"
                  ></textarea>

                  <div class="flex justify-between items-center text-xs text-gray-500 mt-2">
                    <span>{{ wordCount }} words</span>
                    <span class="text-emerald-600">✓ Auto-saved</span>
                  </div>
                </div>
              </div>

              <!-- Attachments Section -->
              <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Attachments</h3>

                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 hover:border-teal-400 transition-all">
                  <div class="text-center">
                    <input
                      type="file"
                      @change="onFileChange"
                      ref="fileInput"
                      class="hidden"
                      accept=".jpg,.jpeg,.png,.gif,.txt,.pdf,.doc,.docx"
                    />

                    <div v-if="!selectedFile" class="cursor-pointer" @click="selectFile">
                      <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                      </svg>
                      <p class="text-sm text-gray-600 mb-2">
                        <span class="text-teal-600 font-medium hover:text-teal-700">Click to upload</span> or drag and drop
                      </p>
                      <p class="text-xs text-gray-500">PDF, JPG, PNG, DOC, DOCX (max 2MB)</p>
                    </div>

                    <div v-else class="flex items-center justify-center gap-3">
                      <div class="flex items-center gap-2 px-4 py-2 bg-teal-50 rounded-lg border border-teal-200">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900">{{ selectedFileName }}</span>
                      </div>
                      <button
                        @click="removeFile"
                        type="button"
                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>

                <p class="text-xs text-gray-500 mt-3 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Allowed file types: .jpg, .jpeg, .png, .gif, .txt, .pdf, .doc, .docx (Max: 2MB)
                </p>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                <button
                  @click="submitTicket"
                  type="button"
                  class="flex-1 sm:flex-none px-8 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-teal-700 hover:to-teal-800 transition-all flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Submit Ticket
                </button>
                <Link
                  :href="route('Support.index')"
                  class="flex-1 sm:flex-none px-8 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all text-center"
                >
                  Cancel
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import Candidatelayout from '@/Layouts/Candidatelayout.vue';

defineOptions({
  layout: Candidatelayout,
});

const { auth } = usePage().props;

const ticketForm = ref({
  name: auth.user ? auth.user.name : '',
  email: auth.user ? auth.user.email : '',
  department: '',
  relatedService: '',
  priority: 'medium',
  subject: '',
  message: '',
});

const fileInput = ref(null);
const selectedFile = ref(null);
const selectedFileName = ref('');
const wordCount = ref(0);

const selectFile = () => {
  fileInput.value?.click();
};

const onFileChange = (e) => {
  const f = e.target.files?.[0];
  selectedFile.value = f || null;
  selectedFileName.value = f ? f.name : '';
};

const removeFile = () => {
  selectedFile.value = null;
  selectedFileName.value = '';
  if (fileInput.value) fileInput.value.value = '';
};

const updateWordCount = () => {
  const text = ticketForm.value.message || '';
  wordCount.value = text.trim().split(/\s+/).filter(word => word.length > 0).length;
};

const submitTicket = () => {
  const fd = new FormData();
  fd.append('department', ticketForm.value.department || '');
  fd.append('subject', ticketForm.value.subject || '');
  fd.append('description', ticketForm.value.message || '');
  if (selectedFile.value) fd.append('attachment', selectedFile.value);

  router.post(route('Support.store'), fd, { forceFormData: true });
};
</script>
