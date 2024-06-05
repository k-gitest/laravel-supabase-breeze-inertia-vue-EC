<script setup lang="ts">
  import type { Image } from '@/types/image'
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  import { ref } from "vue"
  import { router } from '@inertiajs/vue3'

  const props = defineProps<{
    images: Image[] | undefined,
    formImage: [] | File[],
    preview: { name: string; url: string; }[] | [],
  }>()

  const emit = defineEmits(['handleFileChange', 'handleFileDelete'])
  
  const fileImageElement = ref<HTMLInputElement | null>(null);
  
  const clickHandleFile = (event: Event) => {
    event.preventDefault();
    fileImageElement.value?.click()
  }

  const fileChangeHandle = (event: Event) => {
    emit('handleFileChange', event)
  }

  const fileDeleteHandle = (event: Event) => {
    emit('handleFileDelete', event)
    if (fileImageElement.value) {
        fileImageElement.value.value = "";
    }
  }

  const imageDelete = (product_id: number, image_id: number, path: string) => {
    router.delete(`/admin/image/delete/${product_id}`, {
      data:{
        image_id: image_id, 
        path: path,
      },
      preserveState: false,
      onSuccess: (res) => {
        console.log(res)
      }
    })
  }
</script>

<template>
  <h3>登録画像</h3>
  <div v-if="props.images && props.images.length" class="grid grid-cols-3 gap-5">
    <template v-for="image of props.images" :key="image.id">
      <div class="indicator">
        <span class="indicator-item badge badge-neutral cursor-pointer" @click="imageDelete(image.product_id, image.id, image.path)">×</span> 
        <div class="avatar">
        <div class="w-24 rounded">
          <img :src="supabaseURL + image.path" />
        </div>
        </div>
      </div>
    </template>
  </div>
  <div v-else class="max-w-52 max-h-52">
    <img :src="supabaseURL + supabaseNoImage" />
  </div>
  <div class="flex flex-col">
    <input type="file" id="image" ref="fileImageElement" class="hidden" accept="image/*" @input="fileChangeHandle" />
    <button @click="clickHandleFile" class="btn btn-sm">画像を追加</button>
  </div>
  
  <div v-if="props.formImage.length > 0" class="flex gap-2">
    <template v-for="(file, index) in props.formImage" :key="file.name">
      <div :id="`${file.name}`" @click="fileDeleteHandle" class="max-w-32 flex flex-col justify-between items-center cursor-pointer">
        <img :src="props.preview[index].url" class="max-w-32" />
        <p class="cursor-pointer">{{ file.name }}</p>
      </div>
    </template>
  </div>
</template>