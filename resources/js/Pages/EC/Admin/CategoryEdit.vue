<script setup lang="ts">
  import { Head, usePage, useForm } from "@inertiajs/vue3"
  import type { Category } from '@/types/category'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"

  const { props } = usePage<PageProps & { data: Category }>()

  const form = useForm({
    id: props.data.id,
    name: props.data.name,
    description: props.data.description,
    created_at: props.data.created_at,
    updated_at: props.data.updated_at,
  })

  const submit = () => {
    form.put(route('admin.category.update', { id: props.data.id }), {
      preserveState: (res) => {
        return Object.keys(res.props.errors).length > 0
      },
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }

</script>

<template>
  <Head title="CategoryEdit" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">CategoryEdit</h2>
    </template>

    <div v-if="props.data">
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="flex flex-col items-center justify-center w-full max-w-md gap-2">
          <div class="flex flex-col">
            <label for="id">ID</label>
            <input type="text" id="id" v-model="form.id" class="border" disabled />
          </div>
          <div class="flex flex-col">
            <label for="name">カテゴリ名</label>
            <input type="text" id="name" v-model="form.name" class="border" />
          </div>
          <div class="flex flex-col">
            <label for="description">カテゴリの説明</label>
            <textarea type="text" id="description" v-model="form.description" class="border" />
          </div>
          <div class="flex flex-col">
            <label for="created_at">作成日</label>
            <input type="text" id="created_at" v-model="form.created_at" class="border" disabled />
          </div>
          <div class="flex flex-col">
            <label for="updated_at">更新日</label>
            <input type="text" id="updated_at" v-model="form.updated_at" class="border" disabled />
          </div>
          <div v-show="form.errors">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.name }}
              {{ form.errors.description }}
            </p>
          </div>
          <div v-show="props.flash.success">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ props.flash.success }}
            </p>
          </div>
          <div>
            <button type="submit" class="btn" :disabled="form.processing">編集</button>
          </div>
        </div>
      </form>
    </div>
  </AdminEcLayout>
</template>