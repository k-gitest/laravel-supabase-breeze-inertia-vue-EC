<script setup lang="ts">
  import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
  import type { PageData } from "@/types/page"
  import type { PageProps } from '@/types'
  import type { Todo } from '@/types/todo'
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import Pagination from "@/Components/Pagination.vue"

  const { props } = usePage<PageProps & { pagedata: PageData<Todo> }>();

  const form = useForm({})
  const destroy = async (id: string) => {
    await form.delete(route('todo.destroy', { id }), {
      preserveScroll: true,
      preserveState: false,
    })
  }
</script>

<template>
  <Head title="TodoList" />
  <AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Todo</h2>
    </template>
    <main class="flex flex-col items-center justify-center w-full">
      <h1 class="text-4xl font-bold p-2">todoリストのページ</h1>
      <div class="mb-5">
          <Link :href="route('todo.register')" class="btn">
              新規追加
          </Link>
      </div>
      <div v-show="props.errors">
        <p class="text-sm text-red-600 dark:text-red-400">
            {{ props.errors.name }}
            {{ props.errors.error }}
        </p>
      </div>
      <div v-show="props.flash.success">
        <p class="text-sm text-red-600 dark:text-red-400">
            {{ props.flash.success }}
        </p>
      </div>
      <div v-if="props.pagedata.data.length">
        <div class="grid grid-cols-3 gap-5">
          <template v-for="todo in props.pagedata.data" :key="todo.id">
            <div class="card w-96 bg-neutral text-neutral-content">
              <div class="card-body items-center text-center">
                <h2 class="card-title">{{ todo.id }}</h2>
                <p>{{ todo.name }}</p>
                <p>{{ todo.updated_at }}</p>
                <div class="card-actions justify-end">
                  <Link class="btn btn-sm" :href="route('todo.edit', {id: todo.id})">編集</Link>
                  <button class="btn btn-sm" @click="destroy(todo.id)" :disabled="form.processing">削除</button>
                </div>
              </div>
            </div>
          </template>
        </div>
        <Pagination :links="props.pagedata.links" />
      </div>
      <div v-else>
        <p>Todoデータがありません</p>
      </div>
    </main>
  </AuthenticatedLayout>
</template>