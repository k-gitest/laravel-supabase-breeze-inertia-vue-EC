<script setup lang="ts">
  import { Link } from '@inertiajs/vue3'
  import type { PageLink } from '@/types/page'

  defineProps<{
    links: PageLink[];
  }>();

</script>

<template>
  <div v-if="links && links.length">
    <div class="join flex justify-center p-5">
      <template v-for="link of links" :key="link.label">
        <Link 
          v-if="link.url" 
          class="join-item btn btn-sm" 
          :class="{ 'bg-neutral text-white hover:text-white hover:bg-neutral': link.active}" 
          :href="link.url" 
          v-html="link.label" 
          />
        <div 
          v-else
          class="join-item btn btn-sm text-slate-400 hover:text-slate-400" 
          v-html="link.label"
          />
      </template>
    </div>
  </div>
</template>




