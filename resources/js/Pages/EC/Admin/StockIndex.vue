<script setup lang="ts">
  import { Head, usePage, Link } from "@inertiajs/vue3";
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import AdminProductListCard from "@/Components/AdminProductListCard.vue"

  const { props } = usePage<PageProps & { data: Product[] }>()
</script>
<template>
  <Head title="Stock Index" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Stock Index</h2>
    </template>
    <div class="mb-2">
      <Link :href="route('admin.stock.create')" class="btn">在庫登録</Link>
    </div>
    <div v-if="props.data.length" class="grid grid-cols-4 gap-5 justify-center columns-3">
      <template v-for="product of props.data" :key="product.id">
        <AdminProductListCard 
          :image="product.image" 
          :name="product.name" 
          :id="product.id" 
          :description="product.description" 
          :price_excluding_tax="product.price_excluding_tax.toString()" 
          :price_including_tax="product.price_including_tax.toString()" 
          :category_name="product.category?.name" 
          :created_at="product.created_at"
          :route_show="'admin.stock.show'"
          :route_edit="'admin.product.edit'"
          :route_destroy="'admin.product.destroy'"
          :stock="product?.stock_sum_quantity"
        />
      </template>
    </div>
  </AdminEcLayout>
</template>