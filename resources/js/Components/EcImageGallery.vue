<script setup lang="ts">
  import type { Image } from '@/types/image'
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  import { ref } from "vue"
  
  const props = defineProps<{
    images: Image[] | undefined,
  }>()

  const selectedImage = ref()

  if(props.images && props.images.length){
    selectedImage.value = supabaseURL + props.images[0].path
  } else {
    selectedImage.value = supabaseURL + supabaseNoImage
  }
    
  const selectImage = (path: string) => {
    selectedImage.value = supabaseURL + path
  }
</script>

<template>
  <div v-if="images && images.length" class="flex gap-2">
    <div class="flex flex-col gap-2">
      <template v-for="image of images" :key="image.id">
        <div class="size-10 border overflow-hidden cursor-pointer" @click="selectImage(image.path)">
          <img :src="supabaseURL + image.path" class="object-cover" />
        </div>
      </template>
    </div>
    <div class="w-96 h-lg border flex justify-center items-center overflow-hidden">
      <img :src="selectedImage" class="object-cover w-full" />
    </div>
  </div>
  <div v-else class="max-w-48">
    <img :src="supabaseURL + supabaseNoImage" class="rounded-lg" />
  </div>
</template>