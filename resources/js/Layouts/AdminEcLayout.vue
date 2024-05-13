<script setup lang="ts">
  import AdminEcNav from "@/Components/AdminEcNav.vue"
  import GlobalNav from "@/Components/GlobalNav.vue"
  
  defineProps<{
      canLogin?: boolean;
      canRegister?: boolean;
  }>();

    const historyBack = () => {
      window.history.back();
    }
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <GlobalNav />
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white dark:bg-gray-800 shadow"
                v-if="$slots.header"
            >
                <div class="flex items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <button type="button" @click="historyBack">
                        <svg class="h-6 w-6 fill-current md:h-8 md:w-8" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"></path></svg>
                    </button>
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex flex-col items-center justify-center w-full p-2">
              <AdminEcNav />
              <slot />
            </main>
        </div>
    </div>
</template>
