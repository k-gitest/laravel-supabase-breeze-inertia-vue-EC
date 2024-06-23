<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3"
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
  import type { Cart } from '@/types/cart'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  import EcImageGallery from "@/Components/EcImageGallery.vue"

  const { props } = usePage<PageProps & { data: Cart }>()
  const form = useForm({
    quantity: props.data.quantity,
    product_id: props.data.product_id,
  })

  const submit = () => {
    form.put(route('cart.update', { id: props.data.id }), {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }

  const deleteCartItem = (id: number) => {
    form.delete(route('cart.destroy', {id}),{
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }
</script>

<template>
  <Head title="CartEdit" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Cart Edit</h2>
    </template>
    <EcLayout>
      <div v-show="props.flash.success">
        <p class="text-sm text-red-600 dark:text-red-400">
          {{ props.flash.success }}
        </p>
      </div>
      <div v-if="props.data && props.data.product" class="flex gap-5">
        <div>
          <EcImageGallery :images="props.data.product.image" />
        </div>
        <div>
          <form @submit.prevent="submit" enctype="multipart/form-data">
          <ul>
            <li>{{ props.data.id }}</li>
            <li>{{ props.data.product.name }}</li>
            <li>{{ props.data.product.description }}</li>
            <li>{{ props.data.product.price_excluding_tax }}</li>
            <li>{{ props.data.product.price_including_tax }}</li>
            <li>{{ props.data.product.tax_rate }}</li>
            <li>
              <input type="number" v-model="form.quantity" />
            </li>
            <li>{{ props.data.created_at }}</li>
            <li class="flex gap-2">
              <button type="submit" class="btn">数量変更</button>
              <button @click="deleteCartItem(props.data.id)" class="btn">カートから削除</button>
            </li>
          </ul>
          </form>
        </div>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>