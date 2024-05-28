<script setup lang="ts">
  import { Head, Link, usePage, useForm, router } from "@inertiajs/vue3";
  import type { Product } from '@/types/product'
  import type { Warehouse } from '@/types/warehouse'
  import type { Stock } from '@/types/stock'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import EcImageGallery from "@/Components/EcImageGallery.vue"
  import WarehouseStockSelectbox from "@/Components/WarehouseStockSelectbox.vue"
  import { ref, computed } from 'vue';

  const props = defineProps<{
    warehouse: Warehouse[],
    product_id: number | undefined | null,
    stock: Stock[],
  }>()

  const form = useForm({
    warehouse_id: '',
    product_id: props.product_id,
    quantity: '',
    reserved_quantity: '',
  })

  const stockSubmit = () => {
    form.post(route('admin.stock.store'), {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }

  const isFormValid = computed(() => {
    return form.quantity !== null && form.quantity !== '' &&
           form.reserved_quantity !== null && form.reserved_quantity !== '';
  });
  
  const isWarehouseIdPresent = (id: number) => {
    if(props.stock && props.stock.length){
      const result = props.stock.some(stock => {
        if(stock.warehouse) return stock.warehouse.id === id
      })
      return !result
    } else {
      return true
    }
  }
</script>

<template>
  <div>
    <div v-if="props.warehouse && props.warehouse.length">
      <form @submit.prevent="stockSubmit">
        <div class="flex flex-col gap-2">
          <div class="flex flex-col gap-2">
            <label for="warehouse">倉庫を選択</label>
            <select id="warehouse" v-model="form.warehouse_id" class="select select-bordered select-sm w-full">
              <option disabled selected value="">倉庫を選択してください</option>
              <template v-for="warehouse of props.warehouse" :key="warehouse.id">
                <option v-if="isWarehouseIdPresent(warehouse.id)" 
                   :value="warehouse.id">
                  {{ warehouse.name }}
                </option>
              </template>
            </select>
          </div>
          <div v-if="form.warehouse_id" class="flex flex-col gap-2">
            <label for="stock_quantity">在庫数：
              <input id="stock_quantity" type="number" v-model="form.quantity" class="border" min="0" max="100" />
            </label>
            <label for="stock_reserve_quantity">予約在庫数：
              <input id="stock_reserved_quantity" type="number" v-model="form.reserved_quantity" class="border" min="0" max="100" />
            </label>
          </div>
          <button type="submit" class="btn" :disabled="!isFormValid || form.processing">登録</button>
          <div v-show="form.errors">
            <p class="text-red-400">{{ form.errors.quantity }}</p>
            <p class="text-red-400">{{ form.errors.reserved_quantity }}</p>
          </div>
        </div>
      </form>
    </div>
    <div v-else>
      <p>倉庫が登録されていません</p>
      <Link :href="route('admin.warehouse.create')" class="btn">倉庫を登録する</Link>
    </div>
  </div>
</template>