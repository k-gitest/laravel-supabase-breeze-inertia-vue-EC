<script setup lang="ts">
  import { Head, Link, usePage, useForm } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { Product } from '@/types/product'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import EcImageGallery from "@/Components/EcImageGallery.vue"

  const { props } = usePage<PageProps & { data: Product }>()
  console.log(props)

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
  
</script>

<template>
  <Head title="ProductAllList" />
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
            <div v-show="form.errors">
              <p class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.quantity }}
                {{ form.errors.user_id }}
                {{ form.errors.product_id }}
              </p>
            </div>
            <div v-show="props.errors">
              {{ props.errors.quantity }}
              {{ props.errors.user_id }}
              {{ props.errors.product_id }}
            </div>
          </div>
          <div v-else>
            <Link class="btn" :href="route('login')">ログインして購入</Link>
          </div>
        </div>
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>