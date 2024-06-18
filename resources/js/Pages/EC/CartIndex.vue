<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3"
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
  import type { PageData } from '@/types/page'
  import type { Cart } from '@/types/cart'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import CartTableBody from "@/Components/CartTableBody.vue"
  import Pagination from "@/Components/Pagination.vue"

  const { props } = usePage<PageProps & { pagedata: PageData<Cart> }>()
</script>

<template>
  <Head title="CartIndex" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Cart</h2>
    </template>
    <EcLayout>
      <div class="text-center mb-5">
        <p class="font-semibold text-xl text-gray-800">カート</p>
        <p>カート内容です</p>
      </div>
      <div v-show="props.flash.success">
        <p class="text-sm text-red-600 dark:text-red-400">
          {{ props.flash.success }}
        </p>
      </div>
      <div v-if="props.pagedata.data" class="overflow-x-auto">
        <table class="table">
          <thead>
            <tr>
              <th>商品名</th>
              <th>数量</th>
              <th>料金</th>
              <th></th>
            </tr>
          </thead>

          <CartTableBody :carts="props.pagedata.data" />
          
          <tfoot>
            <tr>
              <th colspan="2">合計</th>
              <th colspan="2" class="text-right">
                <span class="text-lg">
                  {{ props.totalPrice.total_price_excluding_tax
               }}円(税抜き)
                </span><br>
                <span class="text-lg">
                  {{ props.totalPrice.total_price_including_tax }}円(税込み)
                </span>
              </th>
            </tr>
          </tfoot>
        </table>
        <div v-if="props.pagedata.data.length">
          <Link :href="route('payment.index')" class="btn">購入画面</Link>
        </div>
        <Pagination :links="props.pagedata.links" />
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>