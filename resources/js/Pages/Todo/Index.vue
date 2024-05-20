<script setup lang="ts">
  import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3';
  import type { PageProps } from '@/types'
  import type { Todo } from '@/types/todo'
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

  const { component, props: { data: todoList }, url, version } = usePage<PageProps & { data: Todo[] }>();

  const form = useForm({})
  const destroy = async (id: string) => {
    await form.delete(route('todo.destroy', { id }), {
      preserveScroll: true,
      preserveState: false,
      onFinish: () => {
      },
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
      <Link :href="route('todo.register')" class="btn">
          新規追加
      </Link>
      <div v-if="todoList.length" >
        <ul v-for="todo in todoList" :key="todo.id">
          <li>{{todo.id}}</li>
          <li>{{todo.name}}</li>
          <li>{{todo.created_at}}</li>
          <li>{{todo.updated_at}}</li>
          <li><Link class="btn" :href="route('todo.edit', {id: todo.id})">編集</Link></li>
          <li><button class="btn" @click="destroy(todo.id)" :disabled="form.processing">削除</button></li>
        </ul>
      </div>
      <div v-else>
        <p>Todoデータがありません</p>
      </div>
    </main>
  </AuthenticatedLayout>
</template>