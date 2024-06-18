<script setup lang="ts">
  import { Head, usePage } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { PageData } from '@/types/page'
  import type { Favorite } from '@/types/favorite'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import ProductListCard from "@/Components/ProductListCard.vue"
  import Pagination from "@/Components/Pagination.vue"

  const { props } = usePage<PageProps & { pagedata: PageData<Favorite> }>()
</script>

<template>
  <Head title="FavoriteIndex" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Favorite Index</h2>
    </template>
    <EcLayout>
      <div v-if="props.pagedata.data.length">
        <div class="text-center mb-5">
          <p class="font-semibold text-xl text-gray-800">お気に入り一覧</p>
        </div>
        <div class="grid grid-cols-4 gap-5">
          <template v-for="favorite of props.pagedata.data" :key="favorite.id">
            <ProductListCard
              :image="favorite.product.image"
              :name="favorite.product.name"
              :id="favorite.id"
              :description="favorite.product.description"
              :price_excluding_tax="favorite.product.price_excluding_tax.toString()"
              :price_including_tax="favorite.product.price_including_tax.toString()"
              :category_name="favorite.product.category?.name"
              :created_at="favorite.product.created_at"
              :route_show="`product.show`"
              :mode="`favorite.delete`"
            />
          </template>
          <Pagination :links="props.pagedata.links" />
        </div>
      </div>
      <div v-else>
        お気に入りはありません
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>