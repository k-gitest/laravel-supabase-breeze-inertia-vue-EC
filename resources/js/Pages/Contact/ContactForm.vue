<script setup lang="ts">
  import { Head, useForm, usePage } from '@inertiajs/vue3'
  import type { Contact } from '@/types/contact'
  import { ref } from 'vue';
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

  const page = usePage()
  const contacts = page.props?.data as Contact[]
  const supabaseURL = import.meta.env.VITE_SUPABASE_URL + "/storage/v1/object/public/"
  const fileImageElement = ref<HTMLInputElement | null>(null);

  const form = useForm({
    name: '',
    email: '',
    message: '',
    image: [] as File[],
    path: '',
  })

  const submit = () => {
    form.post(route('contact.store'), {
      preserveScroll: true,
      preserveState: false,
      onSuccess: (response) => {
        form.reset("name", "email", "message", "image", "path");
        if (fileImageElement.value) {
            fileImageElement.value.value = "";
        }
      },
      onFinish: () => {
        console.log("hoge")
      },
    })
  }

  const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = (target.files as FileList)[0]
    if(file){
      form.image.push(file)
    }
  }

  const clickHandleFile = (event: Event) => {
    event.preventDefault();
    fileImageElement.value?.click()
  }

  const  handleFileDelete = (event: Event) => {
    const target = event.target as HTMLInputElement
    form.image = form.image.filter(file => file.name !== target.id);
    if (fileImageElement.value) {
        fileImageElement.value.value = "";
    }

  }

</script>

<template>
  <Head title="Contact" />
  <AuthenticatedLayout>
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Contact</h2>
    </template>
    <main class="flex flex-col items-center justify-center w-full">
      <h1 class="text-4xl font-bold p-2">Contact</h1>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <div class="flex flex-col items-center justify-center w-full max-w-md gap-2">
          <div>
            <input type="text" id="name" v-model="form.name" class="border" />
          </div>
          <div>
            <input type="email" id="email" v-model="form.email" class="border" />
          </div>
          <div>
            <textarea type="text" id="message" v-model="form.message" class="border" />
          </div>
          <div>
            <input type="text" id="path" v-model="form.path" class="border" />
          </div>
          <div>
            <input type="file" id="image" ref="fileImageElement" class="hidden" accept="image/*" @input="handleFileChange" />
            <button @click="clickHandleFile">ファイルを選択</button>
            <div v-if="form.image.length > 0">
              <template v-for="file in form.image" :key="file.name">
                <p :id="`${file.name}`" @click="handleFileDelete" class="cursor-pointer">{{ file.name }}</p>
              </template>
            </div>
          </div>
          <div v-show="form.errors">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.name }}
            </p>
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.email }}
            </p>
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.message }}
            </p>
          </div>
          <div v-show="page.props.flash">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ page.props.flash.success }}
            </p>
          </div>
          <div>
            <button type="submit" class="btn" :disabled="form.processing">送信</button>
          </div>
        </div>
      </form>
  
      <div v-if="contacts">
        <template v-for="contact in contacts" :key="contact.id">
          <div>
            <p>{{ contact.id }}</p>
            <p>{{ contact.name }}</p>
            <p>{{ contact.email }}</p>
            <p>{{ contact.message }}</p>
            <p>{{ contact.created_at }}</p>
            <p>{{ contact.updated_at }}</p>
            <div v-if="contact.attachments">
              <template v-for="file in contact.attachments" :key="file.id">
                <img :src="supabaseURL + file.key" />
              </template>
            </div>
          </div>
        </template>
      </div>
      <div v-else>
        <p>お問い合わせ履歴はありません</p>
      </div>
    </main>
  </AuthenticatedLayout>
</template>