<script setup lang="ts">
  import { Head, usePage } from "@inertiajs/vue3";
  import type { Warehouse } from '@/types/warehouse'
  import type { PageData } from "@/types/page"
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import AdminStockTableList from "@/Components/AdminStockTableList.vue"
  import Pagination from "@/Components/Pagination.vue"

  const { props } = usePage<PageProps & { category_name: string, pagedata: PageData }>()
</script>

<template>
  <Head title="Warehouse Show" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Warehouse Show</h2>
    </template>
    <div v-if="props.pagedata.data.length">
      <AdminStockTableList :data="props.pagedata.data" />
    </div>
    <div v-else>
      <p class="text-center">該当商品がありません</p>
    </div>
    <Pagination :links="props.pagedata.links" />
  </AdminEcLayout>
</template>