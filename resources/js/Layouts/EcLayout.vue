<script setup lang="ts">
  import EcNav from "@/Components/EcNav.vue"
</script>

<template>
  <EcNav />
  <div class="flex gap-2 max-w-7xl px-4 lg:px-8 mx-auto">
    <main class="flex flex-col items-center justify-start w-full p-2">
      <slot />
    </main>
  </div>  
</template>