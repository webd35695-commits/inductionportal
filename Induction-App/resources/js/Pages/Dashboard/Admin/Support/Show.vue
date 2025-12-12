<template>
  <!-- ADMIN VERSION -->
  <div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <PageHeader title="Support Ticket Details" :subtitle="`Ticket ${ticket.ticket_no}`" />

    <!-- Toast Notification -->
    <transition name="toast">
      <div v-if="showToast" class="fixed top-6 right-6 z-50 bg-white rounded-lg shadow-2xl border border-gray-200 p-4 min-w-[320px] max-w-md">
        <div class="flex items-start gap-3">
          <div class="flex-shrink-0 w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <div class="flex-1">
            <h4 class="font-semibold text-gray-900">{{ toastMessage }}</h4>
            <p class="text-sm text-gray-600 mt-1">{{ toastSubMessage }}</p>
          </div>
          <button @click="showToast = false" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
    </transition>

    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Sidebar - Ticket Info -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow border border-gray-200 p-6 sticky top-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
              <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Ticket Information
            </h3>

            <div class="space-y-4">
              <div>
                <label class="text-xs font-semibold text-gray-500 uppercase">Ticket ID</label>
                <p class="mt-1 text-sm font-mono bg-gray-50 px-3 py-2 rounded border">{{ ticket.ticket_no }}</p>
              </div>

              <div>
                <label class="text-xs font-semibold text-gray-500 uppercase">Applicant</label>
                <div class="mt-1 flex items-center gap-2">
                  <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-800">{{ ticket.sender?.name || 'N/A' }}</p>
                    <p class="text-xs text-gray-500">ID: {{ ticket.sender_id }}</p>
                  </div>
                </div>
              </div>

              <div>
                <label class="text-xs font-semibold text-gray-500 uppercase">Department</label>
                <p class="mt-1 text-sm bg-gray-50 px-3 py-2 rounded border">{{ ticket.department || 'General' }}</p>
              </div>

              <div>
                <label class="text-xs font-semibold text-gray-500 uppercase">Priority</label>
                <span :class="getPriorityClass(ticket.priority)" class="inline-flex mt-1 items-center px-3 py-1 rounded-full text-xs font-semibold">
                  {{ ticket.priority || 'Medium' }}
                </span>
              </div>

              <div>
                <label class="text-xs font-semibold text-gray-500 uppercase">Status</label>
                <select
                  v-model="selectedStatus"
                  class="mt-1 w-full appearance-none bg-white border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-teal-500"
                >
                  <option value="Open">Open</option>
                  <option value="In Progress">In Progress</option>
                  <option value="On Hold">On Hold</option>
                  <option value="Resolved">Resolved</option>
                  <option value="Closed">Closed</option>
                </select>
              </div>

              <div>
                <label class="text-xs font-semibold text-gray-500 uppercase">Created</label>
                <p class="mt-1 text-sm text-gray-700">{{ formatDate(ticket.created_at) }}</p>
              </div>

              <button
                @click="updateStatus"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors shadow-sm flex items-center justify-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Status
              </button>
            </div>
          </div>
        </div>

        <!-- Right Side - Ticket Details & Messages -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Ticket Subject & Description -->
          <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">{{ ticket.subject }}</h2>
            <div class="prose prose-sm max-w-none">
              <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ ticket.description }}</p>
            </div>
            <div class="mt-4 flex items-center gap-2">
              <span :class="getStatusClass(selectedStatus)" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold">
                <span class="w-2 h-2 rounded-full mr-2" :class="getStatusDotClass(selectedStatus)"></span>
                {{ selectedStatus }}
              </span>
            </div>
          </div>

          <!-- Messages Section -->
          <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                  </svg>
                  Conversation ({{ messages.length }})
                </h3>
                <button @click="fetchMessages" class="text-white hover:text-teal-100 transition-colors" title="Refresh messages">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                  </svg>
                </button>
              </div>
            </div>

            <div class="p-6">
              <div ref="scroll" class="space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar">
                <div v-if="messages.length === 0" class="text-center py-12">
                  <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                  </svg>
                  <p class="text-gray-500 font-medium">No messages yet</p>
                </div>

                <div
                  v-for="m in messages"
                  :key="m.id"
                  class="flex gap-3 animate-fade-in"
                  :class="{ 'flex-row-reverse': isAdminMessage(m) }"
                >
                  <div class="flex-shrink-0">
                    <div :class="isAdminMessage(m) ? 'bg-teal-100' : 'bg-blue-100'" class="w-10 h-10 rounded-full flex items-center justify-center">
                      <svg class="w-5 h-5" :class="isAdminMessage(m) ? 'text-teal-600' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                    </div>
                  </div>

                  <div class="flex-1 max-w-xl">
                    <div :class="isAdminMessage(m) ? 'items-end' : 'items-start'" class="flex flex-col">
                      <div class="flex items-center gap-2 mb-1">
                        <span class="text-sm font-semibold" :class="isAdminMessage(m) ? 'text-teal-700' : 'text-blue-700'">
                          {{ m.sender?.name || 'User' }}
                          <span v-if="isAdminMessage(m)" class="ml-1 text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded">Admin</span>
                        </span>
                        <span class="text-xs text-gray-500">{{ formatMessageTime(m.created_at) }}</span>
                      </div>

                      <div
                        :class="isAdminMessage(m) ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-800'"
                        class="rounded-2xl px-4 py-3 shadow-sm"
                      >
                        <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ m.message }}</p>

                        <div v-if="m.attachment" class="mt-3 pt-3 border-t" :class="isAdminMessage(m) ? 'border-teal-500' : 'border-gray-200'">
                          <a
                            :href="storageUrl(m.attachment)"
                            target="_blank"
                            class="inline-flex items-center gap-2 text-sm font-medium hover:underline"
                            :class="isAdminMessage(m) ? 'text-teal-100' : 'text-teal-600'"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            View Attachment
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Admin Reply Input -->
              <div class="mt-6 pt-6 border-t border-gray-200">
                <div v-if="file" class="mb-3 flex items-center gap-2 bg-teal-50 border border-teal-200 rounded-lg px-4 py-2">
                  <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                  </svg>
                  <span class="flex-1 text-sm text-teal-800 font-medium">{{ file.name }}</span>
                  <button @click="removeFile" class="text-teal-600 hover:text-teal-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>

                <div class="flex gap-3">
                  <div class="flex-1 relative">
                    <textarea
                      v-model="message"
                      @keydown.enter.ctrl="send"
                      rows="3"
                      class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent resize-none transition-all"
                      placeholder="Type your admin response... (Ctrl+Enter to send)"
                    ></textarea>
                    <span class="absolute bottom-2 right-2 text-xs text-gray-400">{{ message.length }} / 5000</span>
                  </div>

                  <div class="flex flex-col gap-2">
                    <input
                      type="file"
                      ref="fileInput"
                      @change="onFileChange"
                      class="hidden"
                      accept=".jpg,.jpeg,.png,.gif,.txt,.pdf,.doc,.docx"
                    />
                    <button
                      @click="$refs.fileInput?.click()"
                      class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-3 rounded-xl transition-colors flex items-center justify-center h-12 w-12"
                      title="Attach file"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                      </svg>
                    </button>
                    <button
                      @click="send"
                      :disabled="!canSend"
                      class="bg-teal-600 hover:bg-teal-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white p-3 rounded-xl transition-colors flex items-center justify-center flex-1"
                      title="Send message"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                      </svg>
                    </button>
                  </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Supported files: PDF, DOC, DOCX, JPG, PNG, TXT (max 2MB)</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PageHeader from "@/Components/UI/PageHeader.vue";
import { ref, onMounted, onUnmounted, nextTick, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({ ticket: Object });
const page = usePage();

const message = ref('');
const fileInput = ref(null);
const file = ref(null);
const messages = ref(props.ticket.messages || []);
const scroll = ref(null);
const selectedStatus = ref(props.ticket.status);
const showToast = ref(false);
const toastMessage = ref('');
const toastSubMessage = ref('');

let timer = null;

const canSend = computed(() => {
  return (message.value?.trim().length > 0 || file.value !== null);
});

const triggerToast = (main = 'Success!', sub = 'Your action was completed.') => {
  toastMessage.value = main;
  toastSubMessage.value = sub;
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 4000);
};

const scrollToBottom = () => {
  nextTick(() => {
    const el = scroll.value;
    if (el) el.scrollTop = el.scrollHeight;
  });
};

const fetchMessages = async () => {
  try {
    const { data } = await axios.get(route('Support.messages.index', props.ticket.id));
    messages.value = data;
    scrollToBottom();
  } catch (error) {
    console.error('Error fetching messages:', error);
  }
};

const onFileChange = (e) => {
  const f = e.target.files?.[0];
  if (f) {
    if (f.size > 2048 * 1024) {
      triggerToast('File Too Large', 'Please select a file smaller than 2MB');
      return;
    }
    file.value = f;
  }
};

const removeFile = () => {
  file.value = null;
  if (fileInput.value) fileInput.value.value = '';
};

const send = async () => {
  if (!canSend.value) return;

  const fd = new FormData();
  if (message.value?.trim()) fd.append('message', message.value.trim());
  if (file.value) fd.append('attachment', file.value);

  try {
    await axios.post(route('Support.messages.store', props.ticket.id), fd);

    message.value = '';
    file.value = null;
    if (fileInput.value) fileInput.value.value = '';

    await fetchMessages();
    triggerToast('Response Sent!', 'Your admin response has been delivered.');
  } catch (error) {
    console.error('Error sending message:', error);
    triggerToast('Error', 'Failed to send message. Please try again.');
  }
};

const updateStatus = () => {
  router.put(
    route('Support.update', props.ticket.id),
    { status: selectedStatus.value },
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: () => {
        triggerToast('Status Updated!', `Ticket status changed to "${selectedStatus.value}"`);
      },
      onError: (errors) => {
        console.error(errors);
        triggerToast('Error', 'Failed to update status');
      },
    }
  );
};

const storageUrl = (path) => path ? `/storage/${path}` : '#';

const isAdminMessage = (msg) => {
  // Check if sender has admin role
  return msg.sender_id === page.props.auth?.user?.id;
};

const formatDate = (date) => {
  if (!date) return '';
  const d = new Date(date);
  return d.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const formatMessageTime = (date) => {
  if (!date) return '';
  const d = new Date(date);
  const now = new Date();
  const diff = now - d;
  const hours = Math.floor(diff / (1000 * 60 * 60));

  if (hours < 24) {
    return d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  }
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
};

const getStatusClass = (status) => {
  const classes = {
    'Open': 'bg-blue-100 text-blue-800',
    'In Progress': 'bg-yellow-100 text-yellow-800',
    'On Hold': 'bg-orange-100 text-orange-800',
    'Resolved': 'bg-green-100 text-green-800',
    'Closed': 'bg-gray-100 text-gray-800'
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const getStatusDotClass = (status) => {
  const classes = {
    'Open': 'bg-blue-500',
    'In Progress': 'bg-yellow-500',
    'On Hold': 'bg-orange-500',
    'Resolved': 'bg-green-500',
    'Closed': 'bg-gray-500'
  };
  return classes[status] || 'bg-gray-500';
};

const getPriorityClass = (priority) => {
  const classes = {
    'Low': 'bg-gray-100 text-gray-700',
    'Medium': 'bg-blue-100 text-blue-700',
    'High': 'bg-orange-100 text-orange-700',
    'Critical': 'bg-red-100 text-red-700'
  };
  return classes[priority] || 'bg-blue-100 text-blue-700';
};

onMounted(() => {
  timer = setInterval(fetchMessages, 7000);
  scrollToBottom();
});

onUnmounted(() => {
  if (timer) clearInterval(timer);
});
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}

.toast-enter-active, .toast-leave-active {
  transition: all 0.3s ease;
}

.toast-enter-from {
  transform: translateX(100%);
  opacity: 0;
}

.toast-leave-to {
  transform: translateX(100%);
  opacity: 0;
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.3s ease;
}
</style>
