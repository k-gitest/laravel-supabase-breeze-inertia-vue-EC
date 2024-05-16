<script setup lang="ts">
  import { Link, usePage } from "@inertiajs/vue3"

  const { props } = usePage()
</script>

<template>
  <div v-if="props.category.data.length">
    <ul class="menu bg-base-200 w-56 rounded-box">
    <template v-for="category of props.category.data" :key="category.id">
      <li>
        <Link :href="route('category.show', { id: category.id })">
        {{ category.name }}
        </Link>
      </li>
    </template>
    </ul>
  </div>
  
</template>