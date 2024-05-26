<script setup lang="ts">
  import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import { ref, onMounted, computed } from 'vue'
  import type { Warehouse } from '@/types/warehouse'
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import WarehouseStockSelectbox from "@/Components/WarehouseStockSelectbox.vue"

  const { props } = usePage<PageProps & { data: {warehouse: Warehouse[]; product: Product[];} }>()

  const form = useForm({
    warehouse_id: '',
    product_id: '',
    quantity: 0,
    reserved_quantity: 0,
  })

  const submit = () => {
    form.post(route('admin.stock.store'), {
      preserveState: false,
      onSuccess: (res) => {
        console.log(res)
      }
    })
  }

</script>

<template>
  <Head title="Stock Register" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Stock Register</h2>
    </template>
    <div v-if="props.data">
      <WarehousetStockSelectbox :warehouse="props.data.warehouse" :product_id="null" />
    </div>
    <div v-else>
      <p>データがありません</p>
    </div>
  </AdminEcLayout>
</template>