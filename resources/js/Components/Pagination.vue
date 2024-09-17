<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
    }
});
</script>

<template>
  <div class="mt-6 -mb-1 flex flex-wrap">
    <template v-for="link in links" v-bind:key="link">
      <div
        v-if="link.url === null"
        class="mr-1 mb-1 px-4 py-3 text-sm border rounded text-gray-400"
        v-html="link.label"
      >
      </div>
      <Link
        v-else
        class="mr-1 mb-1 px-4 py-3 text-sm border rounded hover:bg-gray-300 focus:border-indigo-500 focus:text-indigo-500"
        :class="{
          'bg-gray-300': link.active,
          'ml-autos': link.label === 'Next',
        }"
        :href="link.url"
        ><span v-html="link.label"></span></Link
      >
    </template>
  </div>
</template>