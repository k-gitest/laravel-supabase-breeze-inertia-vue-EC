## 概要

laravel11で構築されたwebアプリケーション

- laravelのバージョン11がリリースされていたのでTODOとコンタクトフォーム、ECを開発する
- 認証はbreezeセットでinertia/vue/tsによるSSRでインストール
- DBはsupabase databaseのpostgres、画像の保存先はsupabase storage、メール送信はmailtrap、決済はstripe

### 主な機能:

- TODO管理: CRUD操作
- コンタクトフォーム
- ECサイト:

  - 商品管理 (CRUD)
  - カート機能
  - Stripe決済
  - 注文履歴


- 管理者機能:

  - マルチログイン
  - 在庫管理
  - 倉庫管理


- 検索機能:

  - テキスト検索（サジェスト機能）
  - カテゴリ検索
  - 価格帯検索
  - ソート機能

- レコメンデーション機能 (ベクトル検索)

## 開発環境

- php 8.2
- inertiajs 1.0
- laravel 11.0
- cashier 15.3
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
- stripe
- mecab
- ateliee/mecab
- php-ml
- pgvector

```text
/
├── app
│    ├── Enums
│    ├── Events
│    ├── Facades
│    ├── Http
│    │    ├── Controllers
│    │    │      ├── Auth
│    │    │      └── Admin
│    │    ├── Middleware
│    │    └── Requests
│    │           ├── Admin
│    │           └── Auth
│    ├── Jobs
│    ├── Listeners
│    ├── Mail
│    ├── Models
│    ├── Policies
│    ├── Providers
│    └── Services
│          └── Admin
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
│         │    ├── EC
│         │    │    └── Admin
│         │    ├── Dashboard
│         │    └── Welcome
│         ├── types
│         ├── app.ts
│         └── ssr.ts
├── routes
│      ├── auth.php
│      ├── admin.php
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
route -> controller -> service -> (controller)

```

## 11の主な変更点
- デフォルトのプロバイダーやミドルウェアなどに関連するディレクトリとファイルがappから削除されbootstrapに集約
- プロバイダー登録はbootstrap/providers、ミドルウェア登録はbootstrap/appとrouteで行う
- イベント・リスナー・プロバイダーは自動的にbootstrap追加される
- スケジューラーのkernelが削除されroutes/consoleとbootsrap/appで行う

## 用意
- todoのCRUD画面、コンタクトフォーム、画像アップローダー
- todo_listsテーブル、contactsテーブル、imageバケット
- EC追加、商品CRUD画面、画像アップローダー・プレビュ
- categories, products, images, carts, usersの各テーブル
- stripeによる決済、laravel cashierを追加する
- PaymentIntentとCard Elementを使用、webhookで結果を受け取る
- Orders,OrderItemsテーブルを追加する、決済／履歴画面追加する
- productsを中心としたリレーション
- マルチログイン追加、adminsテーブル追加
- comments, favoritesテーブル追加、コメント投稿とお気に入り画面追加
- stocks, warehousesテーブル追加、在庫と倉庫画面追加
- メールマガジン登録、ジョブキューとスケジュール処理追加
- テキスト、カテゴリ、店舗在庫、価格帯の検索追加、ページネーション追加
- 上記の検索結果をソートする機能を追加
- roleはGateで認可、user_idはpolicyで認可、ガードはミドルウェアで認可、DBはRLS
- Enumで定数バリデーション、ビュー側への定数はconfig/constantsで設定
- ビジネスロジックとクエリはサービスへ、リポジトリは現状使用していない
- コードや型チェックでlarastanとeslint、eslintはflat config
- tailwindとdaisyUIでスタイル設定
- supabaseとmailtrapとstripeのenv設定
- 検索サジェストの実装、商品名の予測表示、コンボボックスの実装
- ベクトルで商品のレコメンデーション表示を実装

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
- inertiaのPartial reloadsは検索結果表示やページネーションとの相性が悪いと感じる
- DB処理後の更新としてrouter.reloadの使いどころは多いと感じる
- whereHasは言われているほど遅くも重くもない
- defineModelでオプショナルがあるとbuild時に型は通ってもInvalid assignment targetのエラーが出るので初期値を設定する必要がある
- withException内でModelNotFoundExceptionが取得できなかったのでNotFoundHttpExceptionで代用しrenderで処理する
- hotwire/livewireは次の機会に使ってみようと思う
- コンボボックスはアクセシビリティ設定をした方が良い
- 日本語形態素解析やベクトル化は外部apiが幾つかあるが、今回はmecab、php-mlで行っている
- 形態素解析のラッパーmecab-phpはlaravel8以降でエラーが出るのでateliee/mecabを使用する
- php-mlのストップワードは英語なので、日本語用として幾つか設定する
- laravelでベクトル型を保存する場合、DBはsupabaseのpostgresなのでpgvectorを有効化しlaravelでpgvector-phpを使用してeloquentに対応させる
- 商品登録時に再計算するので商品データ保存とは非同期で別にジョブキューでベクトル変換処理を行う
- ベクトルの次元は500次元の固定長としている
- pgvector-phpではinsert/upsertが使用できないのでsaveで行っている
- pgvectorのnearestNeighborsでL2近傍やコサイン類似などのクエリを発行できる
- pythonの方が機械学習は前処理含めてライブラリや関数も豊富で良いとは思うが、php/laravelでも一応同じような事はできる

