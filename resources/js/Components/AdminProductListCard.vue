<script setup lang="ts">
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  import { Link, router } from "@inertiajs/vue3";
  import type { Image } from "@/types/image";
  import { isoDateGenerator } from "@/lib/isoDateGenerator";

  defineProps<{
    image?: Image[];
    name: string;
    id: number;
    description: string;
    price_excluding_tax: string;
    price_including_tax: string;
    category_name?: string;
    created_at: string;
    route_show: string;
    route_edit?: string;
    route_destroy?: string;
    mode?: string;
    count?: number;
    stock?: number | null;
  }>();

  const emit = defineEmits<{
    addFavorite: [id: number]
  }>()

  const handleFavorite = (id: number) => {
    emit('addFavorite', id); 
  }

  const deleteFavorite = (id: number) => {
    router.delete(`/favorite/${id}`,{
       preserveState: false,
    })
  }
</script>

<template>
  <div class="card w-96 bg-base-100 shadow-xl max-w-48 relative">
    <div v-if="created_at >= isoDateGenerator()">
      <span class="absolute top-0 end-0 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white z-10">new</span>
      <!--
      <span class="absolute top-0 start-0 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-x-1/2 -translate-y-1/2 bg-red-500 text-white z-10">new</span>
      -->
    </div>

    <Link :href="route(route_show, {id: id})">
      <figure v-if="image && image.length">
        <img :src="supabaseURL + image[0].path" class="rounded-lg" />
      </figure>
      <figure v-else>
        <img :src="supabaseURL + supabaseNoImage" class="rounded-lg" />
      </figure>
      <div class="card-body p-2">
        <h2 class="card-title">
          {{ name }}
        </h2>
        <p>{{ description }}</p>
        <p>{{ price_excluding_tax }}</p>
        <p>{{ price_including_tax }}</p>
        <div class="card-actions justify-end">
          <div class="badge badge-outline text-xs">{{ category_name }}</div>
        </div>
        <div v-if="stock">
          在庫数：{{ stock }}
        </div>
        <div v-else>
          <p class="text-red-400">在庫を登録してください</p>
        </div>
        <button v-if="mode === 'favorite.enable'" @click.stop.prevent="handleFavorite(id)" class="btn btn-sm">お気に入りに追加<span class="badge">{{ count }}</span></button>
        <button v-if="mode === 'favorite.disable'" class="btn btn-sm" disabled>お気に入りに追加<span class="badge">{{ count }}</span></button>
        <button v-if="mode === 'favorite.delete'" @click.stop.prevent="deleteFavorite(id)" class="btn btn-sm">削除</button>
      </div>
    </Link>
  </div>
</template>