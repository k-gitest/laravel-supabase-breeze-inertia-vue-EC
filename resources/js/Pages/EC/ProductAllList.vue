<script setup lang="ts">
  import { Head, Link, usePage } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"

  const { props } = usePage<PageProps & { data: Product[] }>()  
</script>
<template>
  <Head title="ProductAllList" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">ProductAllList</h2>
    </template>

    <EcLayout>
      <div v-if="props.data.length">
        <ul v-for="product of props.data" :key="product.id" class="bg-secondary-content rounded-lg p-2 mb-2">
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
          <li>
            <Link :href="route('product.show', { id: product.id })" class="btn">詳細</Link>
        </li>
        </ul>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>