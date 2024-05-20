<script setup lang="ts">
  import { Head, Link, usePage } from "@inertiajs/vue3"
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
  import type { Order } from '@/types/order'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"

  const { props } = usePage<PageProps & { data: Order[] }>()
</script>

<template>
  <Head title="Order" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Order</h2>
    </template>
    <EcLayout>
      <div v-if="props.data" class="flex flex-col gap-5">
        <div class="text-center mb-5">
          <p class="font-semibold text-xl text-gray-800">オーダー</p>
          <p>オーダー状況</p>
        </div>
        <div class="overflow-x-auto">
          <table class="table">
            <thead>
              <tr>
                <th></th>
                <th>日付</th>
                <th>金額</th>
                <th>決済状況</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <template v-for="order of props.data" :key="order.id">
              <tr>
                <th>{{order.id}}</th>
                <td>{{order.created_at}}</td>
                <td>{{order.total_amount}}</td>
                <td>
                  <template v-if="order.status === 'succeeded'">
                    済み
                  </template>
                  <template v-else>
                    保留中
                  </template>
                </td>
                <td>
                  <Link :href="route('order.show',{id: order.id})" class="btn btn-sm">詳細</Link>
                </td>
              </tr>
              </template>
            </tbody>
          </table>
        </div>
      </div>
      <div v-else>
        注文はありません
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>