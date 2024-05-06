## 概要

laravel11で構築されたwebアプリケーション

- laravelのバージョン11がリリースされていたのでTODOとコンタクトフォーム、ECを開発する
- 認証はbreezeセットでinertia/vue/tsによるSSRでインストール
- DBはsupabase databaseのpostgres、画像の保存先はsupabase storage、メール送信先はmailtrap

## 開発環境

- php 8.2
- inertiajs 1.0
- laravel 11.0
- ziggy 2.0
- breeze 2.0
- larastan 2.9
- vue 3.4.0
- vite 5.0
- typescript 5.4.5
- prettier 3.2.5
- eslint 8.57.0
- daisyui 4.10.1
- tailwindcss 3.2.1
- supabase database/storage
- mailtrap

```text
/
├── app
│    ├── Events
│    ├── Facades
│    ├── Http
│    │    ├── Controllers
│    │    │      └── Auth
│    │    ├── Middleware
│    │    └── Requests
│    │           └── Auth
│    ├── Listeners
│    ├── Models
│    ├── Policies
│    ├── Providers
│    └── Services
├── bootstrap
│    ├── app.php
│    └── providers.php
├── config
├── database
├── public
│    └── build
├── resources
│    └── js
│         ├── Pages
│         │    ├── Auth
│         │    ├── Contact
│         │    ├── Profile
│         │    ├── Todo
│         │    ├── Ec
│         │    ├── Dashboard
│         │    └── Welcome
│         ├── app.ts
│         └── ssr.ts
├── routes
│      ├── auth.php
│      ├── ec.php
│      └── web.php
├── storage
├── tests
├── composer.json
├── eslint.config.js
├── package.json
├── phpstan.neon
├── tailwind.config.js
├── tsconfig.json
└── vite.config.js

supabase <-> laravel/breeze <-> inertia/ziggy <-> vue

```

## 11の主な変更点
- デフォルトのプロバイダーやミドルウェアに関連するディレクトリとファイルがappから削除されbootstrapに集約
- プロバイダー登録はbootstrap/providers、ミドルウェア登録はbootstrap/appとrouteで行う
- イベント・リスナー・プロバイダーは自動的にbootstrap追加される

## 用意
- todoのCRUD画面、コンタクトフォーム、画像アップローダー
- todo_listsテーブル、contactsテーブル、imageバケット
- ECは商品CRUD画面、画像アップローダー・プレビュー、カート
- categories, products, images, carts, usersの各テーブル
- productsを中心としたリレーション
- コードや型チェックでlarastanとeslint、eslintはflat config
- tailwindとdaisyUIでスタイル
- supabaseとmailtrapのenv設定

## まとめ

- laravelは以前に比べてディレクトリもファイルも少なく分かりやすくなった印象
- イベントやリスナー、ポリシーなど作成するとディレクトリは結局増える
- スリム化されたのでエイリアス設定が分かり難くなった印象
- breezeはjetstreamやfortifyと同じくインストール時点でほぼ完成されている
- DB接続しなくても認証できるのはデフォルトでSQLiteが設定されている
- 今回は使用していないがdiskのs3ドライバでsupabaseもs3互換が使えそう
- eslintはparserでエラー、flat configに絞った方が上手くいった
- laravelからsupabase strorageへの接続はSDKを入れるとlaravelの意味がないのでREST APIを使用した
- mixからviteになっていたが、設定しないと普通に遅い
- laravelだけでも覚える事が多くinertia/ziggyなどもあるので、フロントエンドの開発者はapi onlyで使った方が分かりやすいかも知れない
- hotwire/livewireは次の機会に使ってみようと思う

