<template>
  <div class="mb-6">
    <div class="flex items-start justify-between">
      <div>
        <div v-if="backHref" class="mb-2">
          <BackLink :href="backHref" :text="backText" />
        </div>
        <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
        <p v-if="subtitle" class="text-gray-600 mt-1">{{ subtitle }}</p>
      </div>
      <div class="flex items-center space-x-2">
        <slot name="actions" />
      </div>
    </div>
    <nav v-if="breadcrumbs?.length" class="mt-3" aria-label="Breadcrumb">
      <ol class="flex items-center space-x-2 text-sm text-gray-600">
        <li v-for="(b, i) in breadcrumbs" :key="i" class="flex items-center">
          <a v-if="b.url" :href="b.url" class="hover:text-teal-700">{{ b.name }}</a>
          <span v-else class="text-gray-800 font-medium">{{ b.name }}</span>
          <svg v-if="i < breadcrumbs.length - 1" class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </li>
      </ol>
    </nav>
  </div>
</template>

<script setup>
import BackLink from './BackLink.vue';

const props = defineProps({
  title: { type: String, required: true },
  subtitle: { type: String, default: '' },
  backHref: { type: String, default: '' },
  backText: { type: String, default: 'Back' },
  breadcrumbs: { type: Array, default: () => [] }
});
</script>
