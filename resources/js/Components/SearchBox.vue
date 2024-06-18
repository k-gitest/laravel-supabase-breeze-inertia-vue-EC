<script setup lang="ts">
  import type { Category } from "@/types/category";

  defineProps<{
    categories: {
      data: Category[] | undefined,
    },
    price_ranges?: Record<string, [number, number | null]>,
  }>()
  
  const filters = defineModel<{
    q: string,
    category_ids: number[],
    warehouse_check: boolean,
    price_range: string[],
    sort_option: string;
  }>('filters',{
    default:{
      q: '',
      category_ids: [],
      warehouse_check: false,
      price_range: [],
      sort_option: 'newest',
    }
  })
  
  const emit = defineEmits(['searchSubmit'])

  const submit = () => {
    emit('searchSubmit', filters)
  }

</script>

<template>
  <form @submit.prevent="submit">
    <div>
      <input type="text" v-model="filters.q" />
    </div>
    <div>
      <template v-for="category of categories?.data" :key="category.id">
        <label class="label cursor-pointer">
          <span class="label-text">{{ category.name }}</span>
          <input type="checkbox" v-model="filters.category_ids" :value="category.id" class="checkbox checkbox-sm" />
        </label>
      </template>
    </div>
    
    <div>
      <template v-for="(range, key) of price_ranges" :key="key">
        <label class="label cursor-pointer">
          <span class="label-text">{{ range[0] }}～{{ range[1] }}円</span>
          <input type="checkbox" v-model="filters.price_range" :value="key" class="checkbox checkbox-sm" />
        </label>
      </template>
    </div>

    <div>
      <label class="label cursor-pointer">
        <span class="label-text">店舗在庫を含める</span>
        <input type="checkbox" v-model="filters.warehouse_check" id="warehouse_check" class="checkbox checkbox-sm" />
      </label>
    </div>
    <button type="submit" class="btn btn-sm">送信</button>
  </form>
</template>