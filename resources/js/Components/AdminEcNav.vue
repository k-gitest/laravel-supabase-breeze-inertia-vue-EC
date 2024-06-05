<script setup lang="ts">
  import { Link } from "@inertiajs/vue3"
</script>

<template>
  <div class="flex justify-start gap-2 max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <Link :href="route('admin.category.index')" class="btn btn-sm"> category </Link>
    <Link :href="route('admin.product.index')" class="btn btn-sm"> product </Link>
    <Link :href="route('admin.cart.index')" class="btn btn-sm"> cart</Link>
    <Link :href="route('admin.warehouse.index')" class="btn btn-sm"> warehouse</Link>
  </div>
</template>