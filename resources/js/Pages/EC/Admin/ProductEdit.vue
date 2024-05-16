<script setup lang="ts">
  import { Head, useForm, usePage, router } from '@inertiajs/vue3'
  import { ref, computed } from 'vue'
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  import { Link } from "@inertiajs/vue3";

  const { props } = usePage<PageProps & { data: Product }>()
  console.log(props)
  const fileImageElement = ref<HTMLInputElement | null>(null);
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
    form.delete(route('product.destroy', { id: props.data.id}), {
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

  const clickHandleFile = (event: Event) => {
    event.preventDefault();
    fileImageElement.value?.click()
  }

  const handleFileDelete = (event: Event) => {
    const currentTarget = event.currentTarget as HTMLDivElement 
    form.image = form.image.filter(file => file.name !== currentTarget.id);
    preview = preview.filter(file => file.name !== currentTarget.id)
    if (fileImageElement.value) {
        fileImageElement.value.value = "";
    }
  }

  const imageForm = useForm({})

  const imageDelete = (product_id: number, image_id: number, path: string) => {
    imageForm.delete(route('admin.image.destroy', { id: product_id, image_id: image_id, path: path}),{
      preserveState: false,
      onSuccess: (res) => {
        console.log(res)
      }
    })
  }
</script>

<template>
  <Head title="ProductRegister" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Product Edit</h2>
    </template>
      <div v-if="props.data">
        <form @submit.prevent="submit" enctype="multipart/form-data" class="flex gap-2">
          <div class="flex flex-col gap-2">
            <div v-if="props.data.image && props.data.image.length" class="grid grid-cols-3 gap-2">
              <template v-for="image of props.data.image" :key="image.id">
                <div class="max-w-28">
                  <button type="button" @click="imageDelete(image.product_id, image.id, image.path)">
                    <img :src="supabaseURL + image.path"/>
                  </button>
                </div>
              </template>
            </div>
            <div v-else class="max-w-52 max-h-52">
              <img :src="supabaseURL + supabaseNoImage" />
            </div>
            <div class="flex flex-col">
              <label for="image">商品画像を追加</label>
              <input type="file" id="image" ref="fileImageElement" class="hidden" accept="image/*" @input="handleFileChange" />
              <button @click="clickHandleFile" class="btn">ファイルを選択</button>
            </div>
            <div v-if="form.image.length > 0" class="flex gap-2">
              <template v-for="(file, index) in form.image" :key="file.name">
                <div :id="`${file.name}`" @click="handleFileDelete" class="max-w-32 flex flex-col justify-between items-center cursor-pointer">
                  <img :src="preview[index].url" class="max-w-32" />
                  <p class="cursor-pointer">{{ file.name }}</p>
                </div>
              </template>
            </div>
          </div>
          
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
                <input type="number" id="tax_rate" v-model="form.tax_rate" class="border grow" />
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
    </AdminEcLayout>
</template>