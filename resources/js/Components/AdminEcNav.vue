<script setup lang="ts">
  import { Link } from "@inertiajs/vue3"
</script>

<template>
  <div class="flex justify-start gap-2 max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <Link :href="route('admin.category.index')" class="btn btn-sm"> category index</Link>
    <Link :href="route('admin.category.create')" class="btn btn-sm"> category create </Link>
    <Link :href="route('admin.product.index')" class="btn btn-sm"> product index </Link>
    <Link :href="route('admin.product.create')" class="btn btn-sm"> product create </Link>
    <Link :href="route('admin.cart.index')" class="btn btn-sm"> cart</Link>
    <Link :href="route('admin.stock.index')" class="btn btn-sm"> stock</Link>
    <Link :href="route('admin.warehouse.index')" class="btn btn-sm"> warehouse</Link>
</div>
</template>