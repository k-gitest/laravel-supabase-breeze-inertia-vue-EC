<script setup lang="ts">
  import { Head, usePage, router, Link } from "@inertiajs/vue3";
  import type { PageData } from '@/types/page'
  import type { Contact } from '@/types/contact'
  import type { PageProps } from '@/types'
  import AdminEcLayout from "@/Layouts/AdminEcLayout.vue"
  import Pagination from "@/Components/Pagination.vue"
  import { supabaseURL } from "@/lib/supabase"

  const page = usePage<PageProps & {data: Contact}>()
  const props = page.props

  const getAttachmentKey = (path: string) => {
    const segments = path.split('/');
    return segments[segments.length - 1];
  }

  const downloadFile = async (filename: string) => {
    try {
      const url = supabaseURL + filename
      const name = getAttachmentKey(filename)
      const response = await fetch(url);
      if (!response.ok) throw new Error('Network response was not ok');

      const blob = await response.blob();
      const link = document.createElement('a');
      link.href = window.URL.createObjectURL(blob);
      link.download = name;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    } catch (error) {
      console.error('There has been a problem with your fetch operation:', error);
    }
  };
</script>

<template>
  <Head title="Contact Show" />
  <AdminEcLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Contact Show</h2>
    </template>
    <div class="flex justify-center items-start w-full">
      <div class="flex flex-col gap-5 w-full px-16">
        <div class="flex flex-col gap-2 w-full rounded border p-2">
          <p>差出人：{{ props.data.name }}</p>
          <p>差出人メールアドレス：{{ props.data.email }}</p>
        </div>
        <div v-if="props.data.attachments?.length" class="rounded border p-2">
          <p>添付ファイル：{{ props.data.attachments?.length }}件</p>
          <div v-for="attachment in props.data.attachments" :key="attachment.id">
            <button @click="downloadFile(attachment.key)" class="btn btn-sm">{{ getAttachmentKey(attachment.key) }}</button>
          </div>
        </div>
        <div class="divider divider-start">本文</div>
        <div class="w-full rounded border">
        {{ props.data.message }}
        </div>
      </div>
    </div>
  </AdminEcLayout>
</template>