<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3"
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
  import type { Category } from '@/types/category'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"

  const { props } = usePage<PageProps & { data: Category[] }>()
  const form = useForm({})

  const deleteCategory = (id: number) => {
    form.delete(route('category.destroy', {id}),{
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }
</script>

<template>
  <Head title="CategoryEdit" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">CategoryIndex</h2>
    </template>
    <EcLayout>
      <div v-if="props.data.length">
        <div v-show="props.flash.success">
          <p class="text-sm text-red-600 dark:text-red-400">
            {{ props.flash.success }}
          </p>
        </div>
        <ul v-for="category of props.data" :key="category.id">
          <li>{{ category.id }}</li>
          <li>{{ category.name }}</li>
          <li>{{ category.description }}</li>
          <li>{{ category.created_at }}</li>
          <li>{{ category.updated_at }}</li>
          <li><Link class="btn" :href="route('category.edit', {id: category.id})">編集</Link></li>
          <li><button @click="deleteCategory(category.id)">削除</button></li>
        </ul>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>