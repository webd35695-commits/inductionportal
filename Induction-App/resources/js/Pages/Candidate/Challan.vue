<template>
  <div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow print:shadow-none print:p-0">
    <div class="flex justify-between items-start print:hidden">
      <div>
        <h2 class="text-xl font-bold">Bank Challan</h2>
        <p class="text-sm text-gray-600">Print and pay via any 1LINK member bank/channel</p>
      </div>
      <div class="space-x-2">
        <button @click="window.print()" class="px-3 py-1 bg-gray-100 rounded">Print</button>
        <inertia-link :href="route('candidate.dashboard')" class="px-3 py-1 bg-gray-100 rounded">Back</inertia-link>
      </div>
    </div>

    <!-- Three copies layout -->
    <div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4 print:grid print:grid-cols-3">
      <ChallanCopy :copyLabel="'Bank Copy'" :application="application" :payment="payment" :dueDate="dueDate" />
      <ChallanCopy :copyLabel="'Office Copy'" :application="application" :payment="payment" :dueDate="dueDate" />
      <ChallanCopy :copyLabel="'Applicant Copy'" :application="application" :payment="payment" :dueDate="dueDate" />
    </div>

    <div class="text-xs text-gray-500 mt-4 print:hidden">
      Tip: Use A4 paper, portrait, 100% scale. Cut along dotted lines after printing.
    </div>
  </div>
</template>

<script setup>
import Candidatelayout from "@/Layouts/Candidatelayout.vue";

defineOptions({ layout: Candidatelayout });

const props = defineProps({ application: Object, payment: Object });

const dueDate = new Date(Date.now() + 3*24*60*60*1000).toLocaleDateString();

const badgeClass = (status) => {
  const s = (status || '').toLowerCase();
  if (s === 'paid') return 'bg-green-100 text-green-700';
  if (s === 'failed' || s === 'cancelled') return 'bg-red-100 text-red-700';
  return 'bg-yellow-100 text-yellow-700';
};
</script>

<script>
export default {
  components: {
    ChallanCopy: {
      props: ['copyLabel','application','payment','dueDate'],
      data(){ return { now: new Date() } },
      computed: {
        amountWords(){ return toWords(this.payment?.amount || 0) + ' only'; },
        issueDate(){ const dt = this.payment?.created_at || this.now; return new Date(dt).toLocaleDateString(); },
        challanNo(){ return this.payment?.reference_no || `CH-${String(this.payment?.id||'').padStart(6,'0')}`; },
        paid(){ return String(this.payment?.status||'').toLowerCase()==='paid'; }
      },
      methods:{ badgeClass },
      template: `
      <div class="border rounded-lg p-4 relative">
        <div v-if="paid" class="absolute inset-0 pointer-events-none flex items-center justify-center opacity-10">
          <div class="text-6xl font-extrabold text-green-600 rotate-[-20deg]">PAID</div>
        </div>
        <div class="flex items-start justify-between">
          <div>
            <div class="text-sm font-semibold text-gray-800">Federal Government Educational Institutions</div>
            <div class="text-xs text-gray-600">Recruitment & Induction Portal</div>
          </div>
          <div class="text-right">
            <div class="text-xs text-gray-600">Challan No.</div>
            <div class="font-semibold">{{ challanNo }}</div>
            <div class="text-xs">Copy: {{ copyLabel }}</div>
          </div>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
          <div>
            <div><span class="font-semibold">Applicant:</span> {{ application.user?.candidateProfile?.name || application.user?.name }}</div>
            <div><span class="font-semibold">Application ID:</span> {{ application.id }}</div>
            <div><span class="font-semibold">Post:</span> {{ application.post?.name }}</div>
            <div><span class="font-semibold">Issue Date:</span> {{ issueDate }}</div>
          </div>
          <div>
            <div><span class="font-semibold">Amount:</span> Rs. {{ Number(payment.amount||0).toFixed(2) }}</div>
            <div><span class="font-semibold">Amount (in words):</span> {{ amountWords }}</div>
            <div><span class="font-semibold">Status:</span>
              <span :class="badgeClass(payment.status)" class="px-2 py-0.5 rounded text-xs font-semibold">{{ payment.status || 'pending' }}</span>
            </div>
            <div v-if="!paid"><span class="font-semibold">Due Date:</span> {{ dueDate }}</div>
          </div>
        </div>
        <div class="mt-3 text-xs text-gray-700">
          <div><span class="font-semibold">Bank/Channel:</span> Any 1LINK member bank</div>
          <div><span class="font-semibold">Payment Provider:</span> 1LINK</div>
          <div class="border-t mt-2 pt-2">
            <p class="font-medium">Instructions</p>
            <ol class="list-decimal ml-5 space-y-1">
              <li>Present this challan at any 1LINK member bank branch or use 1LINK channels.</li>
              <li>Use the Challan No. above for payment. Keep the bank receipt.</li>
              <li>After payment, status will change to Paid automatically.</li>
            </ol>
          </div>
        </div>
        <div class="mt-3 border-t border-dashed pt-2 text-[11px] text-gray-500 text-center">Cut here</div>
      </div>
      `
    }
  }
}

function toWords(num){
  num = Math.round(Number(num)||0);
  if (num===0) return 'Zero Rupees';
  const a=['','One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
  const b=['','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
  const u=(n)=>n<20?a[n]:(b[Math.floor(n/10)]+' '+a[n%10]).trim();
  const s=(n,w)=> n? (n<100?u(n): (u(Math.floor(n/100))+' Hundred '+u(n%100)).trim())+' '+w+' ':'';
  let words='';
  const crore=Math.floor(num/10000000); num%=10000000; words+=s(crore,'Crore');
  const lakh=Math.floor(num/100000); num%=100000; words+=s(lakh,'Lakh');
  const thousand=Math.floor(num/1000); num%=1000; words+=s(thousand,'Thousand');
  const hundred=Math.floor(num/100); num%=100; words+= hundred? a[hundred]+' Hundred '+u(num): u(num);
  return (words+' Rupees').replace(/\s+/g,' ').trim();
}
</script>

<style>
@media print{
  .print\:hidden{ display:none !important; }
  .print\:shadow-none{ box-shadow:none !important; }
  .print\:p-0{ padding:0 !important; }
}
</style>
