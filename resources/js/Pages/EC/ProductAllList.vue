<script setup lang="ts">
  import { Head, usePage, router } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import ProductListCard from "@/Components/ProductListCard.vue"

  const { props } = usePage<PageProps & { data: Product[] }>()

  const addFavorite = (id: number) => {
    router.visit(`/favorite`,{
      method: 'post',
      data: {
        product_id: id,
      },
      preserveState: false,
      preserveScroll: true,
      onSuccess: (res) => {
        router.reload();
      },
    })
  }
</script>

<template>
  <Head title="ProductAllList" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">ProductAllList</h2>
    </template>
    <EcLayout>
      <div v-if="props.data.length">
        <div class="text-center mb-5">
          <p class="font-semibold text-xl text-gray-800">商品一覧</p>
        </div>
        <div class="grid grid-cols-4 gap-5">
          <template v-for="product of props.data" :key="product.id">
            <ProductListCard
              :image="product.image"
              :name="product.name"
              :id="product.id"
              :description="product.description"
              :price_excluding_tax="product.price_excluding_tax.toString()"
              :price_including_tax="product.price_including_tax.toString()"
              :category_name="product.category?.name"
              :created_at="product.created_at"
              :route_show="`product.show`"
              :mode="product.favorite?.some(item=> item.user_id === props.auth.user?.id) ? `favorite.disable` : `favorite.enable`"
              @addFavorite="addFavorite"
              :count="product.favorite?.length"
              :stock="product?.stock_sum_quantity"
            />
          </template>
        </div>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>