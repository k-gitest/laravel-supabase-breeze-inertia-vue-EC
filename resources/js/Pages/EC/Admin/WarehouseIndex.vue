<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3"
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
  import type { Warehouse } from '@/types/warehouse'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"

  const { props } = usePage<PageProps & { data: Warehouse[] }>()
  const form = useForm({})

  const deleteWarehouse = (id: number) => {
    form.delete(route('admin.warehouse.destroy', {id}),{
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }
</script>

<template>
  <Head title="Warehouse Index" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Warehouse Index</h2>
    </template>
    <div v-if="props.data.length">
      <div>
        <Link :href="route('admin.warehouse.create')" class="btn">倉庫を登録</Link>
      </div>
      <div v-show="props.flash.success">
        <p class="text-sm text-red-600 dark:text-red-400">
          {{ props.flash.success }}
        </p>
      </div>
      <ul v-for="warehouse of props.data" :key="warehouse.id">
        <li>{{ warehouse.id }}</li>
        <li>{{ warehouse.name }}</li>
        <li>{{ warehouse.location }}</li>
        <li class="flex gap-2">
          <Link class="btn btn-sm" :href="route('admin.warehouse.show', {id: warehouse.id})">一覧</Link>
          <Link class="btn btn-sm" :href="route('admin.warehouse.edit', {id: warehouse.id})">編集</Link>
          <button @click="deleteWarehouse(warehouse.id)" class="btn btn-sm">削除</button>
        </li>
      </ul>
    </div>
    <div v-else>
      <p>倉庫が登録されていません</p>
    </div>
    </AdminEcLayout>
</template>