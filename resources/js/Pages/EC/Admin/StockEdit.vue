<script setup lang="ts">
  import { Head, Link, usePage, useForm, router } from "@inertiajs/vue3";
  import type { Stock } from '@/types/stock'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import EcImageGallery from "@/Components/EcImageGallery.vue"

  const { props } = usePage<PageProps & { data: Stock }>()

  const form = useForm({
    id: props.data.id,
    quantity: props.data.quantity,
    reserved_quantity: props.data.reserved_quantity,
  })

  const submit = () => {
    form.put(route('admin.stock.update'), {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }

  const deleteStock = () => {
    router.delete('/admin/stock/delete', {
      data:{
        id: props.data.id
      },
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }
</script>

<template>
  <Head title="Stock Edit" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Stock Edit</h2>
    </template>
      <div v-if="props.data.product" class="flex gap-2">
        <EcImageGallery :images="props.data.product.image" />

        <div class="flex flex-col gap-2">
          <form @submit.prevent="submit" enctype="multipart/form-data">
          <ul>
            <li>{{props.data.product.name}}</li>
            <li>{{props.data.product.description}}</li>
            <li>{{props.data.product.category?.name}}</li>
            <li>{{props.data.product.price_excluding_tax}}</li>
            <li>{{ props.data.product.price_including_tax }}</li>
          </ul>
          <div v-if="props.data.warehouse">
            <div class="overflow-x-auto">
              <table class="table">
                <thead>
                  <tr>
                    <th></th>
                    <th>倉庫名</th>
                    <th>倉庫住所</th>
                    <th>在庫数</th>
                    <th>予約在庫数</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                    <tr>
                      <th>{{ props.data.warehouse.id }}</th>
                      <td>{{ props.data.warehouse.name }}</td>
                      <td>{{ props.data.warehouse.location }}</td>
                      <td>
                        <input type="number" v-model="form.quantity" class="border" min="0" max="100" />
                      </td>
                      <td>
                        <input type="number" v-model="form.reserved_quantity" class="border" min="0" max="100" />
                      </td>
                      <td class="flex gap-2">
                        <button type="submit" class="btn">編集</button>
                        <button @click.prevent="deleteStock" class="btn">削除</button>
                      </td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div v-show="form.errors">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.quantity }}
            </p>
          </div>
          <div v-show="props.errors">
            {{ props.errors.quantity }}
          </div>
          </form>
        </div>
        
      </div>
    </AdminEcLayout>
</template>