<script setup lang="ts">
    import { Link, useForm, usePage } from '@inertiajs/vue3';
    
    defineProps<{
        mustVerifyEmail?: Boolean;
        status?: String;
    }>();
    
    const props = usePage().props;
    const user = usePage().props.auth.user;
    
    const form = useForm({
        subscribed: user.subscribed ?? false,
    });
    
    const submit = () => {
        console.log(form);
        form.put(route('newsletter.update'),{
            preserveState: false,
            onSuccess: () => {
                console.log('success');
            },
        })
    }
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">MailMagazine Subscribe</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                メールマガジン購読を選択して下さい
            </p>
        </header>

        <div class="flex flex-col gap-2">
            <form @submit.prevent="submit">
                <label for="magazine"><p class="text-sm">購読チェック</p>
                    <input type="checkbox" v-model="form.subscribed" id="magazine" class="toggle" />
                </label>
                <div>
                    <button type="submit" class="btn">送信</button>
                </div>
            </form>
        </div>
        <div v-show="props.errors">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ props.errors.error }}
            </p>
        </div>
        <div v-show="props.flash.success">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ props.flash.success }}
            </p>
        </div>
    </section>
</template>
