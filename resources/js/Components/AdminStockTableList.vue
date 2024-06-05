<script setup lang="ts">
  import { Link, router } from "@inertiajs/vue3";
  import type { Product } from '@/types/product'
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  import { isoDateGenerator } from "@/lib/isoDateGenerator";
  import { ref, computed } from 'vue'

  const props = defineProps<{
    data: Product[],
  }>()

  const selectedItems = ref<number[]>([]);

  const allSelected = computed({
    get: () => props.data.length > 0 && selectedItems.value.length === props.data.length,
    set: (value: boolean) => {
      if (value) {
        selectedItems.value = props.data.map(item => item.id);
      } else {
        selectedItems.value = [];
      }
    },
  });

  const toggleAll = () => {
    allSelected.value = !allSelected.value;
  };

  const bulkDestroy = () => {
    router.delete('/admin/product/bulk/delete', {
      data: {ids: selectedItems.value},
      preserveScroll: true,
      preserveState: false,
    })
  }
</script>
<template>
  <div class="overflow-x-auto">
    <div class="flex justify-end">
      <Link :href="route('admin.product.create')" class="btn btn-sm">新規作成</Link>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>
            <label>
              <input type="checkbox" @change="toggleAll" :checked="allSelected" class="checkbox checkbox-xs" />
            </label>
          </th>
          <th>ID</th>
          <th>商品名</th>
          <th>説明</th>
          <th>価格</th>
          <th>税率</th>
          <th>在庫</th>
          <th>編集</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="product of props.data" :key="product.id">
        <tr>
          <th>
            <label>
              <input type="checkbox" v-model="selectedItems" :value="product.id" class="checkbox checkbox-xs" />
            </label>
          </th>
          <td>{{ product.id }}</td>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar relative">
                <div class="mask mask-squircle w-12 h-12">
                  <div v-if="product.image && product.image.length">
                    <img :src="supabaseURL + product.image[0].path" />
                  </div>
                  <div v-else>
                    <img :src="supabaseURL + supabaseNoImage" />
                  </div>
                </div>
                <div v-if="product.created_at >= isoDateGenerator()">
                  <span class="absolute top-0 start-0 inline-flex items-center py-0.4 px-1.5 rounded-full text-xs font-medium transform -translate-x-1/2 -translate-y-1/2 bg-red-500 text-white z-10">new</span>
                </div>
              </div>
              <div>
                <div class="font-bold">{{ product.name }}</div>
                <div class="text-sm opacity-50">{{ product.category?.name }}</div>
              </div>
            </div>
          </td>
          <td>
            {{ product.description }}
          </td>
          <td>
            {{ product.price_excluding_tax }}<br>
            <span class="badge badge-ghost badge-sm">{{ product.price_including_tax }}</span>
          </td>
          <th>
            {{ product.tax_rate }}
          </th>
          <th>
            <template v-if="product.stock_sum_quantity">
              {{ product.stock_sum_quantity }}
            </template>
            <template v-else>
              未登録
            </template>
          </th>
          <th>
            <Link :href="route('admin.product.edit', {id: product.id})" class="btn btn-ghost btn-xs">商品</Link>
            <Link :href="route('admin.stock.show', {id: product.id})" class="btn btn-ghost btn-xs">在庫</Link>
            <Link :href="route('admin.product.destroy', {id: product.id})" class="btn btn-ghost btn-xs">削除</Link>
          </th>
        </tr>
        </template>
      </tbody>
    </table>
    <template v-if="selectedItems.length > 0">
      <button @click="bulkDestroy" class="btn btn-sm">チェックした商品を一括削除</button>
    </template>
  </div>
</template>