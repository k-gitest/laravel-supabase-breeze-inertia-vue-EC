<script setup lang="ts">
  import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3'
  import type { PageData } from '@/types/page'
  import type { Contact } from '@/types/contact'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import Pagination from "@/Components/Pagination.vue"

  const { props } = usePage<PageProps & { pagedata: PageData<Contact> }>()
  
  const handleDelete = (id: string) => {
    router.delete(route('admin.contact.destroy',{id: id}), {
     preserveState: false,
     onSuccess: (res) => {
       console.log("success", res)
     },
    })
  }
</script>

<template>
  <Head title="Contacet Index" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Contact</h2>
    </template>
    <div v-if="props.errors">
      <p class="text-sm text-red-600 dark:text-red-400">
        {{ props.errors.error }}
      </p>
    </div>
    <div v-if="props.flash">
      <p class="text-sm text-red-600 dark:text-red-400">
        {{ props.flash.success }}
      </p>
    </div>
    <div v-if="props.pagedata.data">
      <div class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th>id</th>
              <th>名前</th>
              <th>e-mail</th>
              <th>本文</th>
              <th>日付</th>
              <th>添付</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <template v-for="contact of props.pagedata.data" :key="contact.id">
            <tr>
              <th>{{ contact.id }}</th>
              <td>{{ contact.name }}</td>
              <td>{{ contact.email }}</td>
              <td>{{ contact.message }}</td>
              <td>{{ contact.created_at }}</td>
              <td>{{ contact.attachments?.length ? "あり" : "なし" }}</td>
              <td class="flex gap-2">
                <Link :href="route('admin.contact.show', { id: contact.id })" class="btn btn-sm">閲覧</Link>
                <button @click="handleDelete(contact.id)"  class="btn btn-sm">削除</button>
              </td>
            </tr>
            </template>
          </tbody>
        </table>
        <Pagination :links="props.pagedata.links" />
      </div>
    </div>
    <div v-else>
      <p>お問い合わせ履歴はありません</p>
    </div>
  </AdminEcLayout>
</template>