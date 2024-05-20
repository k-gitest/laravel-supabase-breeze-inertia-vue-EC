<script setup lang="ts">
  import { Head, Link, usePage } from "@inertiajs/vue3"
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
  import type { OrderItem } from '@/types/order'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  import { ref } from 'vue'

  const { props } = usePage<PageProps & { data: OrderItem[] }>()
  const totalPrice = ref(0)
  props.data.map(product => {
    totalPrice.value += product.price_inclusing_tax * product.quantity
  })  
</script>

<template>
  <Head title="Order Detail" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Order Detail</h2>
    </template>
    <EcLayout>
      <div v-if="props.data && props.data.length" class="flex flex-col gap-5">
        <div class="text-center mb-5">
          <p class="font-semibold text-xl text-gray-800">オーダー</p>
          <p>{{props.data[0].created_at}}のオーダー内容</p>
        </div>

        <div class="overflow-x-auto">
          <table class="table">
            <thead>
              <tr>
                <th>商品名</th>
                <th>料金</th>
                <th>数量</th>
                <th>小計</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <template v-for="order_item of props.data" :key="order_item.id">
              <template v-if="order_item.product">
              <tr>
                <td>
                  <div class="flex items-center gap-3">
                    <div class="avatar">
                      <div class="mask mask-squircle w-12 h-12">
                        <template v-if="order_item.product.image && order_item.product.image.length">
                          <img :src="supabaseURL + order_item.product.image[0].path" />
                        </template>
                        <template v-else>
                          <img :src="supabaseURL + supabaseNoImage" />
                        </template>
                      </div>
                    </div>
                    <div>
                      <div class="font-bold">{{order_item.product.name}}</div>
                      <div class="text-sm opacity-50">{{order_item.product.description}}</div>
                    </div>
                  </div>
                </td>
                <td>
                  {{Math.round(order_item.product.price_excluding_tax)}}円(税抜き)<br>
                  {{Math.round(order_item.product.price_including_tax)}}円(税込み)
                </td>
                <td>{{order_item.quantity}}</td>
                <td>{{Math.round(order_item.product.price_including_tax)*order_item.quantity}}円(税込み)</td>
                <th>
                  <Link :href="route('product.show',{id:order_item.product.id})" class="btn btn-ghost btn-xs">details</Link>
                </th>
              </tr>
              </template>
              </template>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3">合計</th>
                <th>{{ Math.round(totalPrice) }}円(税込み)</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div v-else>
        商品がありません
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>