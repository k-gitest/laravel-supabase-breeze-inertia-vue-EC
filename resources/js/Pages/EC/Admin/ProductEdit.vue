<script setup lang="ts">
  import { Head, useForm, usePage, router, Link } from '@inertiajs/vue3'
  import { ref, computed } from 'vue'
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import EcImageGallery from "@/Components/EcImageGallery.vue"
  import AdminEcImageEditor from "@/Components/AdminEcImageEditor.vue"

  const { props } = usePage<PageProps & { data: Product }>()
  let preview: { name: string; url: string; }[] = []

  const form = useForm({
    name: props.data.name,
    description: props.data.description,
    price_excluding_tax: props.data.price_excluding_tax,
    tax_rate: props.data.tax_rate,
    category_id: props.data.category_id,
    image: [] as File[],
  })

  const submit = () => {
    form.post(route('admin.product.update', { id: props.data.id }), {
      headers: {
        'X-HTTP-Method-Override': 'PUT'
      },
      preserveState: false,
      onSuccess: (res) => {
        console.log(res)
      },
    })
    
  }

  const price_including_tax = computed(() => {
    return ((form.price_excluding_tax) * (1 + form.tax_rate / 100)).toFixed(2)
  })

  const deleteProduct = () => {
    form.delete(route('admin.product.destroy', { id: props.data.id}), {
      preserveState: false,
      onSuccess: (res) => {
        console.log(res)
      }
    })
  }

  const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = (target.files as FileList)[0]
    if(file){
      form.image.push(file)
      const blob = URL.createObjectURL(file)
      preview.push({name: file.name, url: blob})
      setTimeout(() => {
        URL.revokeObjectURL(blob)
      }, 2000)
    }
  }

  const handleFileDelete = (event: Event) => {
    const currentTarget = event.currentTarget as HTMLDivElement 
    form.image = form.image.filter(file => file.name !== currentTarget.id);
    preview = preview.filter(file => file.name !== currentTarget.id)
  }

</script>

<template>
  <Head title="ProductEdit" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Product Edit</h2>
    </template>
      <div v-if="props.data">
        <form @submit.prevent="submit" enctype="multipart/form-data" class="flex gap-2">
          <div class="flex flex-col gap-2">
            <EcImageGallery :images="props.data.image" />
          </div>
          
          <div class="flex flex-col items-start justify-start w-full max-w-md gap-2">
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
                <input type="number" id="tax_rate" v-model="form.tax_rate" class="border grow" />
                <span class="w-full">%</span>
              </div>
            </div>

            <AdminEcImageEditor 
              :images="props.data.image" 
              :formImage="form.image" 
              :preview="preview" 
              @handleFileChange="handleFileChange" 
              @handleFileDelete="handleFileDelete" 
              />
            
            <div v-show="props.errors">
              <p class="text-sm text-red-600 dark:text-red-400">
                {{ props.errors.name }}
                {{ props.errors.description }}
                {{ props.errors.price_excluding_tax }}
                {{ props.errors.tax_rate }}
                {{ props.errors.category_id }}
              </p>
            </div>
            <div v-show="props.flash">
              <p class="text-sm text-red-600 dark:text-red-400">
                {{ props.flash.success }}
              </p>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="btn" :disabled="form.processing">編集</button>
              <Link :href="route('admin.stock.show', {id: props.data.id})" class="btn">在庫</Link>
              <button @click="deleteProduct" class="btn" :disabled="form.processing">削除</button>
            </div>
          </div>
        </form> 
      </div>
      <template v-else>
        <p>カテゴリーが登録されていません</p>
      </template>
    </AdminEcLayout>
</template>