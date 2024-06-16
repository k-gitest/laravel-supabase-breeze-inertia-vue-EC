<script setup lang="ts">
  import { Head, useForm, usePage } from '@inertiajs/vue3'
  import type { Contact } from '@/types/contact'
  import { ref } from 'vue';
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';

  const page = usePage()
  const props = page.props
  const contacts = page.props?.data as Contact[]
  const fileImageElement = ref<HTMLInputElement | null>(null);

  const form = useForm({
    name: '',
    email: '',
    message: '',
    image: [] as File[],
    error: '',
  })

  const submit = () => {
    form.post(route('contact.store'), {
      preserveScroll: true,
      preserveState: (page) => {
        return Object.keys(page.props.errors).length > 0
      },
      onSuccess: (response) => {
        form.reset("name", "email", "message", "image");
        if (fileImageElement.value) {
            fileImageElement.value.value = "";
        }
      },
      onBefore: post => {form.clearErrors()},
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
          <div class="w-full">
            <InputLabel for="name" value="Name" />
            <input type="text" id="name" v-model="form.name" class="input input-sm w-full input-bordered" />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div class="w-full">
            <InputLabel for="email" value="e-mail" />
            <input type="email" id="email" v-model="form.email" class="input input-sm w-full input-bordered" />
            <InputError class="mt-2" :message="form.errors.email" />
          </div>
          <div>
            <InputLabel for="message" value="message" />
            <textarea type="text" id="message" v-model="form.message" class="textarea textarea-bordered w-full" />
            <InputError class="mt-2" :message="form.errors.message" />
          </div>
          <div>
            <input type="file" id="image" ref="fileImageElement" class="hidden" accept="image/*" @input="handleFileChange" />
            <button @click="clickHandleFile" class="btn btn-sm">ファイルを選択</button>
            <div v-if="form.image.length > 0">
              <template v-for="file in form.image" :key="file.name">
                <p :id="`${file.name}`" @click="handleFileDelete" class="cursor-pointer">{{ file.name }}</p>
              </template>
            </div>
          </div>
          <div v-if="form.errors.error">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ form.errors.error }}
            </p>
          </div>
          <div v-if="props.flash.success">
            <p class="text-sm text-red-600 dark:text-red-400">
              {{ props.flash.success }}
            </p>
          </div>
          <div>
            <button type="submit" class="btn" :disabled="form.processing">送信</button>
          </div>
        </div>
      </form>
    </main>
  </AuthenticatedLayout>
</template>