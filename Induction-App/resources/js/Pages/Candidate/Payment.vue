<template>
  <div class="max-w-5xl mx-auto p-4 font-sans">
    <!-- Print Button -->
    <div class="text-right mb-4 print:hidden">
      <button @click="window.print()" class="px-6 py-2 bg-teal-700 text-white rounded hover:bg-teal-800">
        Print / Download Challan
      </button>
    </div>

    <!-- Challan Slip - All 3 copies on ONE page -->
    <div id="challan" class="border-4 border-double border-black bg-white p-4">
      <!-- Header -->
      <div class="text-center mb-3">
        <h1 class="text-2xl font-bold uppercase tracking-wider">Fee Deposit Challan</h1>
        <p class="text-base font-semibold mt-1">FGEI - Application Fee</p>
        <p class="text-xs text-gray-600">{{ application.post?.name }}</p>
      </div>

      <!-- Three Copies in Grid -->
      <div class="grid grid-cols-3 gap-3">
        <!-- Bank Copy -->
        <div class="border-2 border-gray-800 p-3">
          <h2 class="text-center font-bold text-sm mb-2 bg-gray-800 text-white py-1">BANK COPY</h2>

          <table class="w-full text-xs">
            <tbody>
              <tr class="border-b">
                <td class="py-1 font-semibold">Challan No.</td>
                <td class="py-1 font-mono">{{ challanNumber }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Name</td>
                <td class="py-1">{{ applicant_name }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Father Name</td>
                <td class="py-1">{{ application.father_name || '—' }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">CNIC</td>
                <td class="py-1 font-mono">{{ application.cnic || '—' }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">App ID</td>
                <td class="py-1 font-mono">{{ application.id }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Post</td>
                <td class="py-1 text-xs">{{ application.post?.name }}</td>
              </tr>
              <tr class="bg-gray-100 border-b-2 border-gray-800">
                <td class="py-2 font-bold">Amount</td>
                <td class="py-2 font-bold text-base">Rs. {{ amount.toFixed(2) }}/-</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Due Date</td>
                <td class="py-1 text-red-600 font-semibold">{{ dueDate }}</td>
              </tr>
            </tbody>
          </table>

          <div class="mt-3 text-xs">
            <p class="font-bold">Bank:</p>
            <p>1LINK Banks</p>
            <p class="font-bold mt-1">Account:</p>
            <p>Govt Treasury</p>
          </div>

          <div class="mt-3 border-t pt-2 grid grid-cols-2 gap-2">
            <div>
              <div class="h-12 border-b border-gray-400"></div>
              <p class="text-[9px] text-center mt-1">Bank Stamp</p>
            </div>
            <div class="flex items-center justify-center border border-gray-300 bg-white p-1">
              <img :src="qrCodeUrl" alt="QR Code" class="w-16 h-16" />
            </div>
          </div>

          <div class="mt-2 text-[8px] text-gray-600 border-t pt-1">
            <p>• Deposit before {{ dueDate }}</p>
            <p>• Non-refundable fee</p>
            <p class="mt-1">Generated: {{ new Date().toLocaleDateString('en-GB') }}</p>
          </div>
        </div>

        <!-- Department Copy -->
        <div class="border-2 border-gray-800 p-3">
          <h2 class="text-center font-bold text-sm mb-2 bg-gray-800 text-white py-1">DEPARTMENT COPY</h2>

          <table class="w-full text-xs">
            <tbody>
              <tr class="border-b">
                <td class="py-1 font-semibold">Challan No.</td>
                <td class="py-1 font-mono">{{ challanNumber }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Name</td>
                <td class="py-1">{{ applicant_name }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Father Name</td>
                <td class="py-1">{{ application.father_name || '—' }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">CNIC</td>
                <td class="py-1 font-mono">{{ application.cnic || '—' }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">App ID</td>
                <td class="py-1 font-mono">{{ application.id }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Post</td>
                <td class="py-1 text-xs">{{ application.post?.name }}</td>
              </tr>
              <tr class="bg-gray-100 border-b-2 border-gray-800">
                <td class="py-2 font-bold">Amount</td>
                <td class="py-2 font-bold text-base">Rs. {{ amount.toFixed(2) }}/-</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Due Date</td>
                <td class="py-1 text-red-600 font-semibold">{{ dueDate }}</td>
              </tr>
            </tbody>
          </table>

          <div class="mt-3 text-xs">
            <p class="font-bold">Bank:</p>
            <p>1LINK Banks</p>
            <p class="font-bold mt-1">Account:</p>
            <p>Govt Treasury</p>
          </div>

          <div class="mt-3 border-t pt-2 grid grid-cols-2 gap-2">
            <div>
              <div class="h-12 border-b border-gray-400"></div>
              <p class="text-[9px] text-center mt-1">Bank Stamp</p>
            </div>
            <div class="flex items-center justify-center border border-gray-300 bg-white p-1">
              <img :src="qrCodeUrl" alt="QR Code" class="w-16 h-16" />
            </div>
          </div>

          <div class="mt-2 text-[8px] text-gray-600 border-t pt-1">
            <p>• Deposit before {{ dueDate }}</p>
            <p>• Non-refundable fee</p>
            <p class="mt-1">Generated: {{ new Date().toLocaleDateString('en-GB') }}</p>
          </div>
        </div>

        <!-- Candidate Copy -->
        <div class="border-2 border-gray-800 p-3">
          <h2 class="text-center font-bold text-sm mb-2 bg-gray-800 text-white py-1">CANDIDATE COPY</h2>

          <table class="w-full text-xs">
            <tbody>
              <tr class="border-b">
                <td class="py-1 font-semibold">Challan No.</td>
                <td class="py-1 font-mono">{{ challanNumber }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Name</td>
                <td class="py-1">{{ applicant_name }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Father Name</td>
                <td class="py-1">{{ application.father_name || '—' }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">CNIC</td>
                <td class="py-1 font-mono">{{ application.cnic || '—' }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">App ID</td>
                <td class="py-1 font-mono">{{ application.id }}</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Post</td>
                <td class="py-1 text-xs">{{ application.post?.name }}</td>
              </tr>
              <tr class="bg-gray-100 border-b-2 border-gray-800">
                <td class="py-2 font-bold">Amount</td>
                <td class="py-2 font-bold text-base">Rs. {{ amount.toFixed(2) }}/-</td>
              </tr>
              <tr class="border-b">
                <td class="py-1 font-semibold">Due Date</td>
                <td class="py-1 text-red-600 font-semibold">{{ dueDate }}</td>
              </tr>
            </tbody>
          </table>

          <div class="mt-3 text-xs">
            <p class="font-bold">Bank:</p>
            <p>1LINK Banks</p>
            <p class="font-bold mt-1">Account:</p>
            <p>Govt Treasury</p>
          </div>

          <div class="mt-3 border-t pt-2 grid grid-cols-2 gap-2">
            <div>
              <div class="h-12 border-b border-gray-400"></div>
              <p class="text-[9px] text-center mt-1">Bank Stamp</p>
            </div>
            <div class="flex items-center justify-center border border-gray-300 bg-white p-1">
              <img :src="qrCodeUrl" alt="QR Code" class="w-16 h-16" />
            </div>
          </div>

          <div class="mt-2 text-[8px] text-gray-600 border-t pt-1">
            <p>• Deposit before {{ dueDate }}</p>
            <p>• Keep safe for upload</p>
            <p>• Non-refundable fee</p>
            <p class="mt-1">Generated: {{ new Date().toLocaleDateString('en-GB') }}</p>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-3 text-center text-[10px] text-gray-600 border-t pt-2">
        <p class="font-bold">IMPORTANT: Deposit this fee in any 1LINK participating bank branch before the due date</p>
        <p class="mt-1">This is a computer-generated challan and does not require any physical signature</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import Candidatelayout from "@/Layouts/Candidatelayout.vue";
import { computed } from 'vue';

defineOptions({ layout: Candidatelayout });

const props = defineProps({
  application: Object,
  amount: Number,
  applicant_name: String,
  latestPayment: Object
});

// Dynamic Challan Number
const challanNumber = computed(() => {
  if (props.latestPayment?.reference_no) {
    return props.latestPayment.reference_no;
  }
  return 'APP-' + props.application.id;
});

// Dynamic Due Date
const dueDate = computed(() => {
  if (props.application?.post?.end_date) {
    return new Date(props.application.post.end_date).toLocaleDateString('en-GB', {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    });
  }
  // Default to 10 days from now if no date found
  const date = new Date();
  date.setDate(date.getDate() + 10);
  return date.toLocaleDateString('en-GB', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
});

// Generate QR Code URL
const qrCodeUrl = computed(() => {
  const qrData = encodeURIComponent(
    `Challan:${challanNumber.value}|Name:${props.applicant_name}|Amount:${props.amount}|AppID:${props.application.id}|CNIC:${props.application.cnic}`
  );
  return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${qrData}`;
});
</script>

<style scoped>
@media print {
  body * { visibility: hidden; }
  #challan, #challan * { visibility: visible; }
  #challan {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    border: 3px double black !important;
    box-shadow: none !important;
    padding: 0.5cm !important;
  }
  .print\\:hidden { display: none !important; }

  /* Ensure everything fits on one page */
  @page {
    size: A4;
    margin: 0.5cm;
  }

  /* Compact spacing for print */
  table { font-size: 9pt !important; }
  h1 { font-size: 16pt !important; }
  h2 { font-size: 10pt !important; }
}

/* Screen view styling */
@media screen {
  #challan {
    max-width: 1200px;
  }
}
</style>
