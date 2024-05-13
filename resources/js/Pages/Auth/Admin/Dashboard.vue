<script setup lang="ts">
    import { Head } from "@inertiajs/vue3";
    import AdminEcLayout from "@/Layouts/AdminEcLayout.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AdminEcLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">
               Admin Dashboard
            </h2>
        </template>

        <div class="w-full bg-white p-6 text-gray-900">
                You're logged in!
        </div>
    </AdminEcLayout>
</template>
