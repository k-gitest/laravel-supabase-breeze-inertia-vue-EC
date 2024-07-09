<script setup lang="ts">
  import { Head, Link, usePage, useForm, router } from "@inertiajs/vue3";
  import type { Product } from '@/types/product'
  import type { Warehouse } from '@/types/warehouse'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import EcImageGallery from "@/Components/EcImageGallery.vue"
  import WarehouseStockSelectbox from "@/Components/WarehouseStockSelectbox.vue"
  import StockTableHead from "@/Components/StockTableHead.vue"
  import { ref, computed } from 'vue';

  const { props } = usePage<PageProps & { data: Product, warehouse: Warehouse[] }>()

  const quantity = ref<Record<number, number>>({});
  const reservedQuantity = ref<Record<number, number>>({});

  if(props.data.stock){
      for (const stock of props.data.stock) {
        quantity.value[stock.id] = stock.quantity;
        reservedQuantity.value[stock.id] = stock.reserved_quantity;
      }
  }

  const form = useForm({
    id: 0,
    quantity: 0,
    reserved_quantity: 0,
  })
  
  const submit = (id: number, quantity: number, reserved: number) => {
    form.id = id;
    form.quantity = quantity;
    form.reserved_quantity = reserved;

    form.put(route('admin.stock.update'), {
      preserveState: (res) => {
        return Object.keys(res.props.errors).length > 0
      },
      onSuccess: () => {
        console.log("success")
      },
      onError: (res) => {
        console.log("error")
        props.errors = res as typeof props.errors;
      },
    });
  }

  const deleteProduct = () => {
    form.delete(route('admin.product.destroy', { id: props.data.id}), {
      preserveState: false,
      onSuccess: (res) => {
        console.log(res)
      }
    })
  }

  const deleteStock = (id: number) => {
    router.delete('/admin/stock/delete', {
      data: {
        id: id,
      },
      preserveState: false,
    })
  }

</script>

<template>
  <Head title="Stock Show" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Stock Show</h2>
    </template>
      <div v-if="props.data" class="flex gap-2">
        <EcImageGallery :images="props.data.image" />
        <div class="flex flex-col gap-2">
          <ul>
            <li>{{props.data.name}}</li>
            <li>{{props.data.description}}</li>
            <li>{{props.data.category?.name}}</li>
            <li>{{props.data.price_excluding_tax}}</li>
            <li>{{ props.data.price_including_tax }}</li>
            <li class="flex gap-2">
              <Link :href="route('admin.product.edit', { id: props.data.id })" class="btn">商品編集</Link>
              <button @click="deleteProduct" class="btn" :disabled="form.processing">商品削除</button>
            </li>
          </ul>
          
          <div v-if="props.data.stock && props.data.stock.length">
            <div class="overflow-x-auto">
              <table class="table">
                <StockTableHead />
                <tbody>
                  <template v-for="stock of props.data.stock" :key="stock.id">
                    <template v-if="stock.warehouse">
                    <tr>
                      <th>{{ stock.warehouse.id }}</th>
                      <td>{{ stock.warehouse.name }}</td>
                      <td>{{ stock.warehouse.location }}</td>
                      <td>
                        <input type="number" v-model="quantity[stock.id]" min="0" max="100" />
                      </td>
                      <td>
                        <input type="number" v-model="reservedQuantity[stock.id]" min="0" max="100" />
                      </td>
                      <td class="flex gap-2"><button @click="submit(stock.id, quantity[stock.id], reservedQuantity[stock.id])" class="btn btn-sm">変更</button>
                        <button @click="deleteStock(stock.id)" class="btn btn-sm">削除</button>
                      </td>
                    </tr>
                    </template>
                  </template>
                </tbody>
              </table>
            </div>
          </div>

          <div v-if="props.errors">
            <p class="text-sm text-red-600 dark:text-red-400"> 
              {{ props.errors.quantity }}
              {{ props.errors.reserved_quantity }}
            </p>
          </div>
          <div v-if="props.flash.success">
            <p class="text-sm text-red-600 dark:text-red-400"> 
              {{ props.flash.success }}
            </p>
          </div>
          
          <template v-if="props.data.stock && props.data.stock.length">
            <WarehouseStockSelectbox :warehouses="props.warehouse" :product_id="props.data.id" :stock="props.data.stock" />
          </template>
          <template v-else>
            在庫が登録されていません
            <WarehouseStockSelectbox :warehouses="props.warehouse" :product_id="props.data.id" />
          </template>
        </div>
        
      </div>
    </AdminEcLayout>
</template>