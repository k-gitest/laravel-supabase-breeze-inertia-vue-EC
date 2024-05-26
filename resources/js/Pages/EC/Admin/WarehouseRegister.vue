<script setup lang="ts">
  import { Head, useForm, usePage } from '@inertiajs/vue3'
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { Warehouse } from '@/types/warehouse'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"

  const { props } = usePage<PageProps & { data: Warehouse }>()
  const form = useForm({
    name: '',
    location: '',
  })

  const submit = () => {
    form.post(route('admin.warehouse.store'), {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },

    })
  }

</script>

<template>
  <Head title="Warehouse Register" />
  <AdminEcLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Warehouse Register</h2>
    </template>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="flex flex-col items-center justify-center w-full max-w-md gap-2">
          <div class="flex flex-col">
            <label for="name">倉庫名</label>
            <input type="text" id="name" v-model="form.name" class="border" />
          </div>
          <div class="flex flex-col">
            <label for="location">倉庫の住所</label>
            <textarea type="text" id="location" v-model="form.location" class="border" />
          </div>
          <div v-show="form.errors">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.name }}
            </p>
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.location }}
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
    </AdminEcLayout>
</template>