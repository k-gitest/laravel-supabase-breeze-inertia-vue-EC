<script setup lang="ts">
  import { Head, usePage } from "@inertiajs/vue3";
  import type { Category } from '@/types/category'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import AdminProductListCard from "@/Components/AdminProductListCard.vue"

  const { props } = usePage<PageProps & { category_name: string, data: Category }>()
</script>

<template>
  <Head title="CategoryProductList" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ props.category_name }}</h2>
    </template>
    <div v-if="props.data">
      <div class="text-center mb-5">
        <p class="font-semibold text-xl text-gray-800">{{ props.data.name }}</p>
        <p>{{ props.data.description }}</p>
      </div>
      <div v-if="props.data.product && props.data.product.length" class="grid grid-cols-4 gap-5 justify-center columns-3">

        <template v-for="product of props.data.product" :key="product.id">
          <AdminProductListCard 
            :image="product.image" 
            :name="product.name" 
            :id="product.id" 
            :description="product.description" 
            :price_excluding_tax="product.price_excluding_tax.toString()" 
            :price_including_tax="product.price_including_tax.toString()" 
            :category_name="product.category?.name" 
            :created_at="product.created_at"
            :route_show="'admin.product.show'"
          />
        </template>
      </div>
      <div v-else>
        登録商品がありません
      </div>
    </div>  
  </AdminEcLayout>
</template>