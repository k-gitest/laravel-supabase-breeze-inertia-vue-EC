<script setup lang="ts">
  import { Head, usePage, router } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { PageData } from '@/types/page'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import ProductListCard from "@/Components/ProductListCard.vue"
  import Pagination from "@/Components/Pagination.vue"
  import SearchBox from "@/Components/SearchBox.vue"

  const { props } = usePage<PageProps & { pagedata: PageData }>()

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

  const searchSubmit = (formdata: { q: string, category_ids: number[], warehouse_check: boolean, price_range: string[]  }) => {
    router.get(route('search'), 
      { q: formdata.q, category_ids: formdata.category_ids, warehouse_check: formdata.warehouse_check, price_range: formdata.price_range },
      {
        preserveState: false,
        preserveScroll: true,
        onSuccess: () => {
          console.log('success');
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
      <div class="flex gap-5 w-full">
        <SearchBox @searchSubmit="searchSubmit" :categories="props.category" :filters="props?.filters" />
        <div class="grow">
          <div v-if="props.pagedata.data.length">
            <div class="text-center mb-5">
              <p class="font-semibold text-xl text-gray-800">商品一覧</p>
            </div>
            <div class="grid grid-cols-4 gap-5 justify-items-center">
              <template v-for="product of props.pagedata.data" :key="product.id">
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
            <div>
              <Pagination :links="props.pagedata.links" />
            </div>
          </div>
          <div v-else>
            <p class="text-center">該当商品がありません</p>
          </div>
        </div>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>