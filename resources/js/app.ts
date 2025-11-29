import './bootstrap';
import '../css/app.css';

import { createApp, createSSRApp, h, DefineComponent } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import * as Sentry from "@sentry/vue";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createSSRApp({ render: () => h(App, props) })
        // spaの場合は以下のcreateAppを使用する
        //createApp({ render: () => h(App, props) })

        // クライアントサイドのSentry（本番環境のみ）
        if (import.meta.env.PROD && import.meta.env.VITE_SENTRY_DSN_PUBLIC) {
            Sentry.init({
                app,
                dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
                integrations: [
                    Sentry.browserTracingIntegration(),
                    Sentry.replayIntegration({
                        maskAllText: true,
                        blockAllMedia: true,
                    }),
                ],
                tracesSampleRate: 1.0,
                replaysSessionSampleRate: 0.1,
                replaysOnErrorSampleRate: 1.0,
                environment: import.meta.env.VITE_APP_ENV || 'production',
                beforeSend(event) {
                    // タグを追加
                    event.tags = event.tags || {};
                    event.tags.platform = 'vue';
                    event.tags.ssr = false;
                    return event;
                },
            });

            // 初期ユーザー情報の設定
            const user = props.initialPage.props.auth?.user;
            if (user) {
                Sentry.setUser({
                    id: user.id,
                    email: user.email,
                    username: user.name,
                });
            }

            // Inertiaナビゲーション時のユーザー情報更新
            router.on('navigate', (event) => {
                const user = event.detail.page.props.auth?.user;
                if (user) {
                    Sentry.setUser({
                        id: user.id,
                        email: user.email,
                        username: user.name,
                    });
                } else {
                    Sentry.setUser(null);
                }
            });
        }

        app.use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
