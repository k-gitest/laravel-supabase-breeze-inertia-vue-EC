<script setup lang="ts">
  import { useForm } from "@inertiajs/vue3"
  
  const props = defineProps<{
    sort_option?: string | undefined,
  }>()

  const emit = defineEmits(['sortSubmit'])
  
  const form = useForm({
    sort_option:  props.sort_option ?? '',
  })
  
  const submit = () => {
    emit('sortSubmit', form.sort_option)
  }

</script>

<template>
  <div>
    <select v-model="form.sort_option" @change="submit" class="select max-w-xs">
      <option value="newest">新着順</option>
      <option value="favorites_desc">お気に入りが多い順</option>
      <option value="favorites_asc">お気に入りが少ない順</option>
      <option value="price_desc">価格が高い順</option>
      <option value="price_asc">価格が安い順</option>
    </select>
  </div>
</template>