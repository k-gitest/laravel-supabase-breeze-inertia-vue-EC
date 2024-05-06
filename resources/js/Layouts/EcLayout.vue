<script setup lang="ts">
  import EcNav from "@/Components/EcNav.vue"
</script>

<template>
  <EcNav />
  <main class="flex flex-col items-center justify-center w-full p-2">
    <slot />
  </main>
</template>