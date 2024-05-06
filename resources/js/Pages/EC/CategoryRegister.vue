<script setup lang="ts">
  import { Head, useForm, usePage } from '@inertiajs/vue3'
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { Category } from '@/types/category'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"

  const { props } = usePage()
  const form = useForm({
    name: '',
    description: '',
  })

  const submit = () => {
    form.post(route('category.store'), {
      onSuccess: (res) => {
        console.log("success", res)
      },
      
    })
  }
  
</script>

<template>
  <Head title="CategoryRegister" />
  <AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Category Register</h2>
    </template>
    <EcLayout>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="flex flex-col items-center justify-center w-full max-w-md gap-2">
          <div class="flex flex-col">
            <label for="name">カテゴリ名</label>
            <input type="text" id="name" v-model="form.name" class="border" />
          </div>
          <div class="flex flex-col">
            <label for="description">カテゴリの説明</label>
            <textarea type="text" id="description" v-model="form.description" class="border" />
          </div>
          <div v-show="form.errors">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.name }}
            </p>
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.description }}
            </p>
          </div>
          <div v-show="props.flash">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ props.flash.success }}
            </p>
          </div>
          <div>
            <button type="submit" class="btn" :disabled="form.processing">送信</button>
          </div>
        </div>
      </form>
    </EcLayout>
  </AuthenticatedLayout>
</template>