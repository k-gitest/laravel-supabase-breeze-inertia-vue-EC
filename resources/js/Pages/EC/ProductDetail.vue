<script setup lang="ts">
  import { Head, Link, usePage, useForm, router } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import EcImageGallery from "@/Components/EcImageGallery.vue"
  import ProductListCard from "@/Components/ProductListCard.vue"

  const { props } = usePage<PageProps & { data: Product, recommendedProducts: Product[] }>()

  const form = useForm({
    product_id: props.data.id,
    user_id: props?.auth?.user?.id,
    quantity: 1,
  })

  const commentForm = useForm({
    product_id: props.data.id,
    comment: "",
    title: "",
  })

  const favoriteForm = useForm({
    product_id: props.data.id,
  })

  const submit = () => {
    form.post(route('cart.store'), {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
    })
  }

  const commentSubmit = () => {
    commentForm.post(route('comment.store'), {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      }
    })
  }

  const favoriteSubmit = () => {
    form.post(route('favorite.store'), {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      }
    })
  }

  const addFavorite = (id: number) => {
    router.visit(`/favorite`,{
      method: 'post',
      data: {
        product_id: id,
      },
      preserveState: false,
      preserveScroll: true,
      onSuccess: (res) => {
        router.reload();
      },
    })
  }
  
</script>

<template>
  <Head title="ProductDetail" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">ProductDetail</h2>
    </template>
    <EcLayout>
      <div v-if="props.data" class="flex gap-2">
        <EcImageGallery :images="props.data.image" />
        <div>
          <ul>
            <li>{{props.data.name}}</li>
            <li>{{props.data.description}}</li>
            <li>{{props.data.category?.name}}</li>
            <li>{{props.data.price_excluding_tax}}</li>
            <li>{{ props.data.price_including_tax }}</li>
          </ul>
          <div v-if="props.data.stock_sum_quantity && props.data.stock_sum_quantity > 0 && props.data.stock_sum_quantity < 5">
            在庫数：残り僅か
          </div>
          <div v-else-if="props.data.stock_sum_quantity === 0 || !props.data.stock_sum_quantity">
            <p class="text-red-400">売り切れ</p>
          </div>
          <div v-if="props.auth.user" class="flex flex-col gap-2">
            <div v-if="props.isInCart">
              <button class="btn" disabled>カートに追加済み</button>
            </div>
            <div v-else>
              <form @submit.prevent="submit">
                <div class="flex flex-col gap-2">
                  <label for="quantity">数量
                    <input type="number" min="1" max="99" id="quantity" v-model="form.quantity" />
                  </label>
                  <button class="btn">カートに追加</button>
                </div>
              </form>
            </div>
            <div v-if="props.isInFavorite">
              <button class="btn" disabled>お気に入りに追加済み</button>
            </div>
            <div v-else>
              <form @submit.prevent="favoriteSubmit">
                <button type="submit" class="btn w-full">お気に入りに追加</button>
              </form>
            </div>
            <div v-show="form.errors">
              <p class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.quantity }}
                {{ form.errors.user_id }}
                {{ form.errors.product_id }}
              </p>
            </div>
            <div v-show="props.flash.success">
              <p class="text-sm text-red-600 dark:text-red-400">
              {{ props.flash.success }}
              </p>
            </div>
            <div v-if="props.isInComment">
              <p>既にコメントを投稿しています</p>
            </div>
            <div v-else>
              <form @submit.prevent="commentSubmit">
                <div class="flex flex-col gap-2">
                  <p>コメントを投稿する</p>
                  <input v-model="commentForm.title" type="text" placeholder="タイトル" class="input input-bordered input-sm w-full max-w-xs" />
                  <textarea v-model="commentForm.comment" class="textarea textarea-bordered" placeholder="コメント"></textarea>
                  <button type="submit" class="btn">投稿</button>
                </div>
              </form>
            </div>
            <div v-if="props.data.comment && props.data.comment.length">
              <template v-for="comment of props.data.comment" :key="comment.id">
                <div class="card w-96 bg-base-100 border">
                  <div class="card-body">
                    <h2 class="card-title">{{ comment.title }}</h2>
                    <p>{{ comment.content }}</p>
                    <div class="card-actions justify-end">
                      {{ comment.created_at }}
                    </div>
                  </div>
                </div>
              </template>
            </div>
            <div v-else>
              コメント投稿はありません
            </div>
          </div>
          <div v-else>
            <div v-if="props.data.stock_sum_quantity && props.data.stock_sum_quantity > 0">
              <Link class="btn" :href="route('login')">ログインして購入</Link>
            </div>
          </div>
        </div>
      </div>
      <h2>類似商品</h2>
      <div class="grid grid-cols-5 gap-5 justify-items-center">
        <template v-for="product of props.recommendedProducts" :key="product.id">
          <ProductListCard
            :image="product.image"
            :name="product.name"
            :id="product.id"
            :description="product.description"
            :price_excluding_tax="product.price_excluding_tax.toString()"
            :price_including_tax="product.price_including_tax.toString()"
            :category_name="product.category?.name"
            :created_at="product.created_at"
            :route_show="`product.show`"
            :mode="product.favorite?.some(item=> item.user_id === props.auth.user?.id) ? `favorite.disable` : `favorite.enable`"
            @addFavorite="addFavorite"
            :count="product.favorite?.length"
            :stock="product?.stock_sum_quantity"
          />
        </template>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>