<script setup lang="ts">
  import { Head, usePage, router, Link } from "@inertiajs/vue3";
  import type { Product } from '@/types/product'
  import type { PageData } from '@/types/page'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import AdminStockTableList from "@/Components/AdminStockTableList.vue"
  import Pagination from "@/Components/Pagination.vue"
  import SearchBox from "@/Components/SearchBox.vue"
  import { ref } from "vue"

  const { props } = usePage<PageProps & { pagedata: PageData<Product> }>()
  const filters = ref({...props.filters});

  const searchSubmit = () => {
    router.get(route('admin.search'), 
      filters.value,
      {
        preserveState: false,
        preserveScroll: true,
        onSuccess: () => {
          console.log('success');
        },
    })
  }
</script>
<template>
  <Head title="ProductAllList" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">ProductAllList</h2>
    </template>
    <div class="flex gap-5 w-full">
      <SearchBox 
        @searchSubmit="searchSubmit" 
        :categories="props.category" 
        v-model:filters="filters" 
        :price_ranges="props.price_ranges"
        />
      <div class="grow">
        <div class="flex justify-end">
          <Link :href="route('admin.product.create')" class="btn btn-sm">新規作成</Link>
        </div>
        <div v-show="props.errors">
          <p class="text-center text-sm text-red-600 dark:text-red-400">
            {{ props.errors.error }}
          </p>
        </div>
        <div v-show="props.flash.success">
          <p class="text-center text-sm text-red-600 dark:text-red-400">
            {{ props.flash.success }}
          </p>
        </div>
        <div v-if="props.pagedata.data.length">
          <AdminStockTableList :data="props.pagedata.data" />
        </div>
        <div v-else>
          <p class="text-center">該当商品がありません</p>
        </div>
        <Pagination :links="props.pagedata.links" />
      </div>
    </div>
  </AdminEcLayout>
</template>