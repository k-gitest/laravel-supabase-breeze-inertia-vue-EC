<script setup lang="ts">
  import { Head, useForm, usePage } from '@inertiajs/vue3'
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import { ref, onMounted, computed } from 'vue'
  import type { Category } from '@/types/category'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"

  const { props } = usePage<PageProps & { data: Category[] }>()
  const fileImageElement = ref<HTMLInputElement | null>(null);

  const form = useForm({
    name: '',
    description: '',
    price_excluding_tax: 0,
    tax_rate: 0,
    category_id: '',
    image: [] as File[],
    error: '',
  })

  let preview: { name: string; url: string; }[] = []

  const submit = () => {
    form.post(route('admin.product.store'), {
      onSuccess: (res) => {
        console.log(res)
      }
    })
  }

  const price_including_tax = computed(() => {
    return (form.price_excluding_tax * (1 + form.tax_rate / 100)).toFixed(2)
  })

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

  const  handleFileDelete = (event: Event) => {
    const currentTarget = event.currentTarget as HTMLDivElement 
    form.image = form.image.filter(file => file.name !== currentTarget.id);
    preview = preview.filter(file => file.name !== currentTarget.id)
    if (fileImageElement.value) {
        fileImageElement.value.value = "";
    }
  }

</script>

<template>
  <Head title="ProductRegister" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Product Register</h2>
    </template>
      <div v-if="props.data.length === 0">
        <p>カテゴリーが登録されていません</p>
      </div>
      <template v-else>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="flex gap-2">
          <div class="flex flex-col">
            <label for="image">商品画像</label>
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

          <div class="flex flex-col items-center justify-center w-full max-w-md gap-2">
            <div class="flex flex-col">
              <label for="name">商品名</label>
              <input type="text" id="name" v-model="form.name" class="border" />
            </div>
            <div class="flex flex-col">
              <label for="description">商品の説明</label>
              <textarea type="text" id="description" v-model="form.description" class="border" />
            </div>
            <div v-if="props.data.length" class="flex flex-col">
              <label for="category">カテゴリ</label>
              <select id="category" v-model="form.category_id" class="select-sm border w-full max-w-xs">
                <option disabled selected>選択してください</option>
                <option v-for="category of props.data" :key="category.id" :value="category.id">{{ category.name }}</option>
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
                {{ form.errors.error }}
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
              <button type="submit" class="btn" :disabled="form.processing">送信</button>
            </div>
          </div>
        </div>
      </form>
      </template>
    </AdminEcLayout>
</template>