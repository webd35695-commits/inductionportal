<template>
  <Head title="Profile" />

  <AuthenticatedLayout>
      <div class="p-6 mt-6 bg-white border border-gray-100 rounded-xl shadow-lg">
        <h3 class="mb-6 text-lg font-semibold text-[#0e5723]">Account Statistics</h3>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
          <div class="p-4 transition-all duration-200 rounded-lg bg-[#0e5723]/5 hover:bg-[#36A2EB]/10">
            <div class="text-sm font-medium text-gray-500">Member since</div>
            <div class="mt-1 text-xl font-semibold text-[#0e5723]">
              {{ new Date(auth.user?.created_at).toLocaleDateString() }}
            </div>
          </div>
          <div class="p-4 transition-all duration-200 rounded-lg bg-[#0e5723]/5 hover:bg-[#36A2EB]/10">
            <div class="text-sm font-medium text-gray-500">Last active</div>
            <div class="mt-1 text-xl font-semibold text-[#0e5723]">
              {{ auth.user?.last_login_at ? new Date(auth.user.last_login_at).toLocaleString() : 'Today' }}
            </div>
          </div>
          <div class="p-4 transition-all duration-200 rounded-lg bg-[#0e5723]/5 hover:bg-[#36A2EB]/10">
            <div class="text-sm font-medium text-gray-500">Account status</div>
            <div class="mt-1 text-xl font-semibold text-[#0e5723]">Active</div>
          </div>
          <div class="p-4 transition-all duration-200 rounded-lg bg-[#0e5723]/5 hover:bg-[#36A2EB]/10">
            <div class="text-sm font-medium text-gray-500">Security</div>
            <div class="mt-1 text-xl font-semibold text-[#0e5723]">Verified</div>
          </div>
        </div>
      </div>

    <div class="container px-4 py-6 mx-auto">
      <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
        <!-- Update Profile Information -->
        <div class="p-6 bg-white border border-gray-100 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-[#0e5723]">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
              Profile Information
            </h3>
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-[#0e5723]/10 text-[#0e5723]">Required</span>
          </div>
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="max-w-xl"
          />
        </div>

        <!-- Update Password -->
        <div class="p-6 bg-white border border-gray-100 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-[#0e5723]">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
              Update Password
            </h3>
          </div>
          <UpdatePasswordForm class="max-w-xl" />
        </div>

        <!-- Account Activity -->
        <div class="p-6 bg-white border border-gray-100 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-[#0e5723]">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
              </svg>
              Account Activity
            </h3>
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-[#36A2EB]/10 text-[#36A2EB]">Live</span>
          </div>
          <div class="space-y-4">
            <div class="flex items-center p-4 transition-all duration-200 rounded-lg bg-gray-50 hover:bg-[#36A2EB]/10 group">
              <div class="p-3 mr-4 text-white rounded-lg  group-hover:bg-[#36A2EB] transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-800">Last login</p>
                <p class="text-xs text-gray-500">{{ auth.user?.last_login_at ? new Date(auth.user.last_login_at).toLocaleString() : 'Never' }}</p>
              </div>
            </div>
            <div class="flex items-center p-4 transition-all duration-200 rounded-lg bg-gray-50 hover:bg-[#36A2EB]/10 group">
              <div class="p-3 mr-4 text-white rounded-lg  group-hover:bg-[#36A2EB] transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-800">Account created</p>
                <p class="text-xs text-gray-500">{{ new Date(auth.user?.created_at).toLocaleString() }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Delete Account -->
        <div class="p-6 bg-white border border-gray-100 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-[#0e5723]">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              Delete Account
            </h3>
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Warning</span>
          </div>
          <DeleteUserForm class="max-w-xl" />
        </div>
      </div>

      <!-- Additional Stats Section -->

    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
    default: false,
  },
  status: {
    type: String,
    default: '',
  },
  auth: {
    type: Object,
    required: true,
    default: () => ({
      user: {
        last_login_at: null,
        created_at: null
      }
    }),
  },
});
</script>
