<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3"
  import type { Cart } from '@/types/cart'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"

  const { props } = usePage<PageProps & { data: Cart }>()
  const form = useForm({
    quantity: props.data.quantity,
  })

  const submit = () => {
    form.put(route('admin.cart.update', { id: props.data.id }),{
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }

  const deleteCategory = (id: number) => {
    form.delete(route('admin.cart.destroy', {id}),{
      preserveState: false,
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
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Cart</h2>
    </template>
      <div v-show="props.flash.success">
        <p class="text-sm text-red-600 dark:text-red-400">
          {{ props.flash.success }}
        </p>
      </div>
      <div v-if="props.data">
        <form @submit.prevent="submit">
        <ul>
          <li>{{ props.data.id }}</li>
          <li>{{ props.data.product_id }}</li>
          <li>
            <input type="number" v-model="form.quantity" min="1" max="99" />
          </li>
          <li>{{ props.data.created_at }}</li>
          <li>{{ props.data.updated_at }}</li>
          <li>
            <div v-if="props.data.product">
              <p>{{ props.data.product.name }}</p>
              <p>{{ props.data.product.description }}</p>
              <p>{{ props.data.product.price_excluding_tax
 }}</p>
              <p>{{ props.data.product.price_including_tax
               }}</p>
              <p>{{ props.data.product.tax_rate
                 }}</p>
            </div>
          </li>
          <li class="flex gap-2">
            <button type="submit" class="btn" :disabled="form.processing">変更</button>
            <button @click="deleteCategory(props.data.id)" class="btn">削除</button>
        </li>
        </ul>
        </form>
      </div>
    </AdminEcLayout>
</template>