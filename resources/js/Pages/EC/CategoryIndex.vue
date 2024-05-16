<script setup lang="ts">
  import { Head, usePage, Link } from "@inertiajs/vue3"
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
  import type { Category } from '@/types/category'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"

  const { props } = usePage<PageProps & { data: Category[] }>()
</script>

<template>
  <Head title="CategoryIndex" />
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
          <li><Link :href="route('category.show', { id: category.id })" class="btn">カテゴリ商品一覧</Link></li>
        </ul>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>