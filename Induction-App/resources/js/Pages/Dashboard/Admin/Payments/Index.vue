<template>
<div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <PageHeader title="Application Payments" subtitle="Review fee transactions" />
      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="p-6 border-b border-gray-200">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-gray-50">
                <th class="p-3">#</th>
                <th class="p-3">Applicant</th>
                <th class="p-3">Application</th>
                <th class="p-3">Amount</th>
                <th class="p-3">Status</th>
                <th class="p-3">Reference</th>
                <th class="p-3">Paid At</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="p in payments.data" :key="p.id" class="border-t">
                <td class="p-3">{{ p.id }}</td>
                <td class="p-3">{{ p.applied_post?.user?.name }}</td>
                <td class="p-3">#{{ p.applied_post_id }} – {{ p.applied_post?.post?.name }}</td>
                <td class="p-3">Rs. {{ Number(p.amount).toFixed(2) }}</td>
                <td class="p-3">
                  <span :class="badgeClass(p.status)" class="px-2 py-1 rounded text-xs font-semibold">{{ p.status }}</span>
                </td>
                <td class="p-3">{{ p.reference_no || '-' }}</td>
                <td class="p-3">{{ p.paid_at ? new Date(p.paid_at).toLocaleString() : '-' }}</td>
              </tr>
            </tbody>
          </table>
          <div class="flex justify-between items-center mt-4">
            <button :disabled="!payments.prev_page_url" @click="go(payments.prev_page_url)" class="px-3 py-1 border rounded disabled:opacity-50">Previous</button>
            <button :disabled="!payments.next_page_url" @click="go(payments.next_page_url)" class="px-3 py-1 border rounded disabled:opacity-50">Next</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PageHeader from "@/Components/UI/PageHeader.vue";
import { router } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({ payments: Object });

const badgeClass = (status) => {
  const s = (status || '').toLowerCase();
  if (s === 'paid') return 'bg-green-100 text-green-700';
  if (s === 'failed' || s === 'cancelled') return 'bg-red-100 text-red-700';
  return 'bg-yellow-100 text-yellow-700';
};

const go = (url) => { if (url) router.visit(url, { preserveScroll: true }); };
</script>
