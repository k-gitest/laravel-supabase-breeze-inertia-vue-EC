<script setup lang="ts">
  import { Head, usePage } from "@inertiajs/vue3";
  import type { Warehouse } from '@/types/warehouse'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import AdminProductListCard from "@/Components/AdminProductListCard.vue"

  const { props } = usePage<PageProps & { category_name: string, data: Warehouse }>()
  console.log(props)
</script>

<template>
  <Head title="Warehouse Show" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ props.data.name }}</h2>
    </template>
    <div v-if="props.data">
      <div class="text-center mb-5">
        <p class="font-semibold text-xl text-gray-800">{{ props.data.name }}</p>
        <p>{{ props.data.location }}</p>
      </div>
      <div v-if="props.data.stock && props.data.stock.length" class="grid grid-cols-4 gap-5 justify-center columns-3">
        <template v-for="stock of props.data.stock" :key="stock.id">
          <template v-if="stock.product">
            <AdminProductListCard 
              :image="stock.product.image" 
              :name="stock.product.name" 
              :id="stock.product.id" 
              :description="stock.product.description" 
              :price_excluding_tax="stock.product.price_excluding_tax.toString()" 
              :price_including_tax="stock.product.price_including_tax.toString()" 
              :category_name="stock.product.category?.name" 
              :created_at="stock.product.created_at"
              :route_show="'admin.stock.show'"
              :stock="stock.quantity"
            />
          </template>
        </template>
      </div>
      <div v-else>
        登録商品がありません
      </div>
    </div>  
  </AdminEcLayout>
</template>