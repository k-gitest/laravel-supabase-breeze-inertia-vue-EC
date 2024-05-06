<script setup lang="ts">
  import { Head, useForm, usePage } from '@inertiajs/vue3'
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import { ref, onMounted, computed } from 'vue'
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"

  const { props } = usePage<PageProps & { data: Product }>()
  
  const form = useForm({
    name: props.data.name,
    description: props.data.description,
    price_excluding_tax: props.data.price_excluding_tax,
    tax_rate: props.data.tax_rate,
    category_id: props.data.category_id,
  })

  const submit = () => {
    form.put(route('product.update', { id: props.data.id }), {
      preserveState: false,
      onSuccess: (res) => {
        console.log(res)
      }
    })
  }
  
  const price_including_tax = computed(() => {
    return (form.price_excluding_tax * (1 + form.tax_rate / 100)).toFixed(2)
  })

  const deleteProduct = () => {
    form.delete(route('product.destroy', { id: props.data.id}), {
      preserveState: false,
      onSuccess: (res) => {
        console.log(res)
      }
    })
  }
  
</script>

<template>
  <Head title="ProductRegister" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Product Edit</h2>
    </template>
    <EcLayout>
      <div v-if="props.data">
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="flex flex-col items-center justify-center w-full max-w-md gap-2">
            <div class="flex flex-col">
              <label for="name">商品名</label>
              <input type="text" id="name" v-model="form.name" class="border" />
            </div>
            <div class="flex flex-col">
              <label for="description">商品の説明</label>
              <textarea type="text" id="description" v-model="form.description" class="border" />
            </div>
            
            <div class="flex flex-col">
              <label for="category">カテゴリ</label>
              <select id="category" v-model="form.category_id" class="select-sm border w-full max-w-xs">
                <option disabled>選択してください</option>
                <option v-for="category of props.category.data" :key="category.id" :value="category.id" :selected="category.id === form.category_id">
                  {{ category.name }}
                </option>
              </select>
            </div>
            
            <div class="flex flex-col">
              <label for="price_excluding_tax">税抜き価格</label>
              <input type="text" id="price_excluding_tax" v-model="form.price_excluding_tax" class="border" />
            </div>
            <div class="flex flex-col">
              <label for="price_including_tax">税込み価格</label>
              <input type="text" id="price_including_tax" v-model="price_including_tax" class="border" disabled />
            </div>
            <div class="flex flex-col">
              <label for="tax_rate">税率</label>
              <div clas="flex">
                <input type="text" id="tax_rate" v-model="form.tax_rate" class="border grow" />
                <span class="w-full">%</span>
              </div>
            </div>
            <div v-show="form.errors">
              <p class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.name }}
              </p>
              <p class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.description }}
              </p>
            </div>
            <div v-show="props.flash">
              <p class="text-sm text-red-600 dark:text-red-400">
                {{ props.flash.success }}
              </p>
            </div>
            <div>
              <button type="submit" class="btn" :disabled="form.processing">編集</button>
              <button @click="deleteProduct" class="btn" :disabled="form.processing">削除</button>
            </div>
          </div>
        </form> 
      </div>
      <template v-else>
        <p>カテゴリーが登録されていません</p>
      </template>
    </EcLayout>
  </AuthenticatedLayout>
</template>