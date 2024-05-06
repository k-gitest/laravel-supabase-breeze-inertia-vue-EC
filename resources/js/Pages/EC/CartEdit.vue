<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3"
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
  import type { Cart } from '@/types/cart'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"

  const { props } = usePage<PageProps & { data: Cart[] }>()
  console.log(props)
  const form = useForm({})

  const deleteCategory = (id: number) => {
    form.delete(route('cart.destroy', {id}),{
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }
</script>

<template>
  <Head title="CategoryEdit" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Cart</h2>
    </template>
    <EcLayout>
      <div v-show="props.flash.success">
        <p class="text-sm text-red-600 dark:text-red-400">
          {{ props.flash.success }}
        </p>
      </div>
      <div v-if="props.totalPrice">
        <p class="text-sm text-red-600 dark:text-red-400">
          税抜き合計：{{ props.totalPrice.total_price_excluding_tax
 }} 円
        </p>
        <p class="text-sm text-red-600 dark:text-red-400">
          税込み合計：{{ props.totalPrice.total_price_including_tax
           }} 円
        </p>
      </div>
      <div v-if="props.data">
        <ul v-for="cart of props.data" :key="cart.id">
          <li>{{ cart.id }}</li>
          <li>{{ cart.product_id }}</li>
          <li>{{ cart.quantity }}</li>
          <li>{{ cart.created_at }}</li>
          <li>{{ cart.updated_at }}</li>
          <li>
            <div v-if="cart.product">
              <p>{{ cart.product.name }}</p>
              <p>{{ cart.product.description }}</p>
              <p>{{ cart.product.price_excluding_tax
 }}</p>
              <p>{{ cart.product.price_including_tax
               }}</p>
              <p>{{ cart.product.tax_rate
                 }}</p>
            </div>
          </li>
          <li class="flex gap-2">
            <Link class="btn" :href="route('cart.edit', {id: cart.id})">編集</Link>
            <button @click="deleteCategory(cart.id)" class="btn">削除</button>
        </li>
        </ul>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>