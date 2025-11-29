import { createSSRApp, h, DefineComponent } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import * as Sentry from "@sentry/vue";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// 環境変数: サーバー専用DSNを優先、なければ公開DSNを使用
const SENTRY_DSN = import.meta.env.VITE_SENTRY_DSN_SERVER || import.meta.env.VITE_SENTRY_DSN_PUBLIC;

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) })

            // SSR環境でのSentry初期化
            if (SENTRY_DSN) {
                Sentry.init({
                    app, // Vueアプリインスタンスを渡す
                    dsn: SENTRY_DSN,

                    // SSR環境では最小限のインテグレーション
                    integrations: [
                        // ブラウザ専用機能は使わない
                    ],

                    environment: import.meta.env.VITE_APP_ENV || 'production',

                    // SSRではトレーシングを低めに設定
                    tracesSampleRate: 0.1,

                    // SSR側のエラーであることを識別
                    beforeSend(event) {
                        event.tags = event.tags || {};
                        event.tags.platform = 'vue';
                        event.tags.ssr = true;
                        return event;
                    },
                });

                // ユーザー情報の設定
                const user = props.initialPage.props.auth?.user;
                if (user) {
                    Sentry.setUser({
                        id: user.id,
                        email: user.email,
                        username: user.name,
                    });
                }
            }

            return app
                .use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                });
        },
    })
);
