<script setup lang="ts">
  import { router, Link } from '@inertiajs/vue3'
  import type { Cart } from "@/types/cart"
  import { supabaseURL, supabaseNoImage } from "@/lib/supabase"
  
  defineProps<{
    carts: Cart[],
  }>()

  const deleteCartItem = (id: number) => {
    router.delete(`/cart/destroy/${id}`,{
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }
</script>

<template>
  <tbody>
    <template v-if="carts.length">
    <template v-for="cart of carts" :key="cart.id">
      <template v-if="cart.product">
        <tr>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar">
                <div class="mask mask-squircle w-12 h-12">
                  <div v-if="cart.product.image && cart.product.image.length">
                    <img :src="supabaseURL + cart.product.image[0].path" />
                  </div>
                  <div v-else>
                    <img :src="supabaseURL + supabaseNoImage" />
                  </div>
                </div>
              </div>
              <div>
                <div class="font-bold">{{ cart.product.name }}</div>
                <div class="text-sm opacity-50">{{ cart.product.description }}</div>
              </div>
            </div>
          </td>
          <td>{{ cart.quantity }}</td>
          <td>
            {{ cart.product.price_excluding_tax }}
            <br />
            <span class="badge badge-ghost badge-sm">{{ cart.product.price_including_tax }}</span>
          </td>
          <th class="flex gap-2">
            <Link class="btn btn-sm" :href="route('cart.edit', {id: cart.id})">編集</Link>
            <button class="btn btn-ghost btn-sm" @click="deleteCartItem(cart.id)">削除</button>
          </th>
        </tr>
      </template>
    </template>
    </template>
    <template v-else>
      カートには何も入っていません
    </template>
  </tbody>
</template>