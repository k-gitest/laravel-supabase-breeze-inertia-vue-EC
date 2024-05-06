<script setup lang="ts">
  import { Link } from "@inertiajs/vue3"
</script>

<template>
  <div class="flex justify-start gap-2 max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <Link :href="route('category.index')" class="btn btn-sm"> category index</Link>
    <Link :href="route('category.create')" class="btn btn-sm"> category create </Link>
    <Link :href="route('category.show', { id: 1 })" class="btn btn-sm"> category show </Link>
    <Link :href="route('product.index')" class="btn btn-sm"> product index </Link>
    <Link :href="route('product.create')" class="btn btn-sm"> product create </Link>
    <Link :href="route('product.edit', { id: 3 })" class="btn btn-sm"> product edit</Link>
    <Link :href="route('product.show', { id: 3 })" class="btn btn-sm"> product show</Link>
    <Link :href="route('cart.edit')" class="btn btn-sm"> cart</Link>
  </div>
</template>