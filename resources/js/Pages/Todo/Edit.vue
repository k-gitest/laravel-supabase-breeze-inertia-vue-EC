<script setup lang="ts">
  import { Head, usePage, useForm } from '@inertiajs/vue3';
  import type { PageProps } from '@/types'
  import type { Todo } from '@/types/todo'
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

  const { props: { todoList: todo }  } = usePage<PageProps & { todoList: Todo }>();
  
  const form = useForm({
    name: todo.name,
  })

  const submit = () => {
    form.put(route('todo.update', { id: todo.id }), {
      onFinish: () => {
        form.reset('name')
      }
    })
  }
</script>

<template>
  <Head title="Todo Edit" />
  <AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Todo</h2>
    </template>
    <main class="flex flex-col justify-center items-center w-full">
      <h1 class="text-4xl font-bold p-2">Todo Edit</h1>
      <div>
        <form @submit.prevent="submit">
          <div class="flex flex-col items-center justify-center w-full max-w-md gap-2">
            <div>
              <input type="text" id="name" v-model="form.name" class="border" />
            </div>
            <div>
              <button type="submit" class="btn">送信</button>
            </div>
          </div>
        </form>
      </div>
    </main>
  </AuthenticatedLayout>
</template>