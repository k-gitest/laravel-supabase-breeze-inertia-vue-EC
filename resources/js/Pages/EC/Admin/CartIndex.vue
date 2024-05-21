<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3"
  import type { Cart } from '@/types/cart'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import ProductListCard from "@/Components/ProductListCard.vue"
  import CartTableBody from "@/Components/CartTableBody.vue"

  const { props } = usePage<PageProps & { data: Cart[] }>()
</script>

<template>
  <Head title="CartIndex" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Cart</h2>
    </template>
      <div v-show="props.flash.success">
        <p class="text-sm text-red-600 dark:text-red-400">
          {{ props.flash.success }}
        </p>
      </div>
    <div v-if="props.data" class="overflow-x-auto">
      <table class="table">
        <thead>
          <tr>
            <th>商品名</th>
            <th>数量</th>
            <th>料金</th>
            <th></th>
          </tr>
        </thead>

        <CartTableBody :carts="props.data" />"
        
      </table>
    </div>
    </AdminEcLayout>
</template>