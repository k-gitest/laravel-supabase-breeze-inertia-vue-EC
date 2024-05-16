<script setup lang="ts">
  import { Link } from "@inertiajs/vue3"
  import { usePage } from "@inertiajs/vue3"

  const { props } = usePage()
</script>

<template>
  <div class="flex justify-start gap-2 max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <Link :href="route('category.index')" class="btn btn-sm"> category index</Link>
    <Link :href="route('product.index')" class="btn btn-sm"> product index </Link>
    <template v-if="props.auth.user">
      <Link :href="route('cart.index')" class="btn btn-sm"> cart</Link>
    </template>
  </div>
</template>