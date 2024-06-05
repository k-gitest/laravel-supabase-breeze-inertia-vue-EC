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
    },
  }>()
  
  const form = useForm({
    q: props.filters?.q || '',
    category_ids: props.filters?.category_ids || [],
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
    <button type="submit" class="btn btn-sm">送信</button>
  </form>
</template>