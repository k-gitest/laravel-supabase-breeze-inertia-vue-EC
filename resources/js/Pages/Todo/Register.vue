<script setup lang="ts">
  import { Head, usePage, useForm } from '@inertiajs/vue3';
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  
  const form = useForm({
      name: '',
  });
  
  const submit = () => {
      form.post(route('todo.store'), {
          onFinish: () => {
              form.reset('name');
          },
      });
  };
</script>

<template>
  <Head title="Todo Register" />
  <AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Todo</h2>
    </template>
    <main class="flex flex-col items-center justify-center w-full">
      <h1 class="text-4xl font-bold p-2">Todo Register</h1>
      
        <form @submit.prevent="submit">
          <div class="flex flex-col items-center justify-center w-full max-w-md gap-2">
            <div>
              <input type="text" id="name" v-model="form.name" class="border" />
            </div>
            <div v-show="form.errors">
                <p class="text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.name }}
                </p>
            </div>
            <div>
              <button type="submit" :disabled="form.processing" class="btn">送信</button>
            </div>
          </div>
        </form>
    </main>
  </AuthenticatedLayout>
</template>