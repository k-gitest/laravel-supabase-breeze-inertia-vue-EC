<script setup lang="ts">
  import { Head, usePage } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { Product } from '@/types/product'
  import type { Category } from '@/types/category'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"

  const { props } = usePage<PageProps & { category_name: string, data: Category }>()
</script>

<template>
  <Head title="CategoryProductList" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ props.category_name }}</h2>
    </template>
    <EcLayout>
      <div v-if="props.data">
        <p>{{ props.data.name }}</p>
        <p>{{ props.data.description }}</p>
        <ul v-for="product of props.data.product" :key="product.id">
          <li v-if="product.image && product.image.length">
            <img :src="supabaseURL + product.image[0].path" class="rounded-lg" />
          </li>
          <li v-else>
            <img :src="supabaseURL + supabaseNoImage" class="rounded-lg" />
          </li>
          <li>{{product.name}}</li>
          <li>{{product.description}}</li>
          <li>{{product.category?.name}}</li>
          <li>{{product.price_excluding_tax}}</li>
          <li>{{ product.price_including_tax }}</li>
        </ul>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>