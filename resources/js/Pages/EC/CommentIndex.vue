<script setup lang="ts">
  import { Head, usePage, Link, router } from "@inertiajs/vue3";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import type { PageData } from '@/types/page'
  import type { Comment } from '@/types/comment'
  import type { PageProps } from '@/types'
  import EcLayout from "@/Layouts/EcLayout.vue"
  import ProductListCard from "@/Components/ProductListCard.vue"
  import Pagination from "@/Components/Pagination.vue"
  import { ref } from "vue"

  const { props } = usePage<PageProps & { pagedata: PageData<Comment> }>()
  const isProcessing = ref(false)

  const deleteComment = (id: number) => {
    isProcessing.value = true
    router.delete(`/comment/destroy/${id}`, {
      preserveState: false,
      onSuccess: (res) => {
        console.log("success", res)
      },
      onFinish: () => {
        isProcessing.value = false
      }
    })
  }
</script>

<template>
  <Head title="CommentIndex" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Comment Index</h2>
    </template>
    <EcLayout>
      <div v-if="props.pagedata.data.length">
        <div class="text-center mb-5">
          <p class="font-semibold text-xl text-gray-800">コメント投稿一覧</p>
        </div>
        <div class="grid grid-cols-3 gap-5">
          <template v-for="comment of props.pagedata.data" :key="comment.id">
            <div class="card w-96 bg-base-100 border">
              <div class="card-body">
                <div v-if="comment.product">
                  {{ comment.product.name }}へのコメント投稿
                </div>
                <h2 class="card-title">{{ comment.title }}</h2>
                <p>{{ comment.content }}</p>
                <div class="card-actions justify-end">
                  {{ comment.created_at }}
                  <Link v-if="comment.product" :href="route('product.show',{id: comment.product.id})" class="btn btn-sm">詳細</Link>
                  <button @click="deleteComment(comment.id)" :disabled="isProcessing" class="btn btn-sm">削除</button>
                </div>
              </div>
            </div>
          </template>
        </div>
        <Pagination :links="props.pagedata.links" />
      </div>
      <div v-else>
        コメント投稿はありません
      </div>
    </EcLayout>
  </AuthenticatedLayout>
</template>