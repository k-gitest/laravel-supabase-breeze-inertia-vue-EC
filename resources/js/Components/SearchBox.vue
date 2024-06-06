<script setup lang="ts">
  import { useForm } from "@inertiajs/vue3"
  import type { Category } from "@/types/category";

  const props = defineProps<{
    categories: {
      data: Category[] | undefined,
    },
    filters?: { 
      q: string | undefined,
      category_ids: number[] | undefined,
      warehouse_check: boolean | undefined,
      price_range?: string[] | undefined,
    },
    price_ranges?: Record<string, [number, number | null]>,
  }>()

  const form = useForm({
    q: props.filters?.q || '',
    category_ids: props.filters?.category_ids || [],
    warehouse_check: props.filters?.warehouse_check || false,
    price_range: props.filters?.price_range || [],
  })
  
  const emit = defineEmits(['searchSubmit'])

  const submit = () => {
    emit('searchSubmit', form)
  }

</script>

<template>
  <form @submit.prevent="submit">
    <div>
      <input type="text" v-model="form.q" />
    </div>
    <div>
      <template v-for="category of categories?.data" :key="category.id">
        <label class="label cursor-pointer">
          <span class="label-text">{{ category.name }}</span>
          <input type="checkbox" v-model="form.category_ids" :value="category.id" class="checkbox checkbox-sm" />
        </label>
      </template>
    </div>
    
    <div>
      <template v-for="(range, key) of price_ranges" :key="key">
        <label class="label cursor-pointer">
          <span class="label-text">{{ range[0] }}～{{ range[1] }}円</span>
          <input type="checkbox" v-model="form.price_range" :value="key" class="checkbox checkbox-sm" />
        </label>
      </template>
    </div>

    <div>
      <label class="label cursor-pointer">
        <span class="label-text">店舗在庫を含める</span>
        <input type="checkbox" v-model="form.warehouse_check" id="warehouse_check" class="checkbox checkbox-sm" />
      </label>
    </div>
    <button type="submit" class="btn btn-sm">送信</button>
  </form>
</template>