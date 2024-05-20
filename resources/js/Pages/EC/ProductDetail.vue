<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import EcImageGallery from "@/Components/EcImageGallery.vue"

  const { props } = usePage<PageProps & { data: Product }>()

  const form = useForm({
    product_id: props.data.id,
    user_id: props?.auth?.user?.id,
    quantity: 1,
  })

  const submit = () => {
    form.post(route('cart.store'), {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }
  
</script>

<template>
  <Head title="ProductAllList" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">ProductDetail</h2>
    </template>
    <EcLayout>
      <div v-if="props.data" class="flex gap-2">
        <EcImageGallery :images="props.data.image" />
        <div>
          <ul>
            <li>{{props.data.name}}</li>
            <li>{{props.data.description}}</li>
            <li>{{props.data.category?.name}}</li>
            <li>{{props.data.price_excluding_tax}}</li>
            <li>{{ props.data.price_including_tax }}</li>
          </ul>
          <div v-if="props.auth.user">
            <div v-if="props.isInCart">
              <button class="btn" disabled>カートに追加済み</button>
            </div>
            <div v-else>
              <form @submit.prevent="submit">
                <div class="flex flex-col gap-2">
                  <label for="quantity">数量
                    <input type="text" id="quantity" v-model="form.quantity" />
                  </label>
                  <button class="btn">カートに追加</button>
                </div>
              </form>
            </div>
            <div v-show="form.errors">
              <p class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.quantity }}
                {{ form.errors.user_id }}
                {{ form.errors.product_id }}
              </p>
            </div>
            <div v-show="props.errors">
              {{ props.errors.quantity }}
              {{ props.errors.user_id }}
              {{ props.errors.product_id }}
            </div>
          </div>
          <div v-else>
            <Link class="btn" :href="route('login')">ログインして購入</Link>
          </div>
        </div>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>