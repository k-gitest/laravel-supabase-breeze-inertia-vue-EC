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

## セットアップ
依存関係のインストール (composer install, npm install)
アプリ起動 (php artisan serve, npm run dev)
開発用ストレージリンク (php artisan storage:link)
データベースマイグレーション (php artisan migrate)

## アーキテクチャ

### レイヤー構成
```
Route → Controller → Service → Model
                    ↓
                 Repository（未使用）
```

**サービス層の方針**:
- ビジネスロジックとEloquentクエリを集約
- リポジトリパターンは未採用（理由：現状のクエリ複雑度では過剰な抽象化）
- 今後の方針：同じクエリが3箇所以上で重複したらリポジトリ化を検討

### 認可の設計
- **ロール認可**: Gate（admin/userの判定）
- **所有者認可**: Policy（user_idチェック）
- **ガード認可**: Middleware（web/adminガード切り替え）
- **DB認可**: Supabase RLS

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
- sentry-laravel 4.19
- supabase database/storage
- mailtrap
- stripe
- mecab
- ateliee/mecab
- php-ml
- pgvector

```text
/
├── .devcontainer
│    ├── devcontainer.json # 開発環境用の設定
│    └── Dockerfile # 開発環境用dockerfile
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
├── vite.config.js
└── Dockerfile # 本番環境用dockerfile

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

## 開発上の注意点

### inertiaでputやdeleteでfile送信する場合
inertiaのフォームを使用してputやdeleteで画像などfileをアップロードするために送信するとエラーになる
正確には画像などfileを含む送信はエラーにならずなにも送信されなくなる。fileを含めなければputやdeleteも使用できる

原因としては、inertiaはファイルを含むフォームデータがJSON形式ではなく、マルチパート形式で送信されるため。

対処法としては、
- postで送信する、routeもpostに変更する
- メソッドはpost、headerでputに変更し、routeはputのままで良い
- useFormデータ内に、_method: "PUT",を含める

headerでputにする場合、
```php
headers: {
        'X-HTTP-Method-Override': 'PUT'
      },
```

- _method: "PUT",とする場合、当然だがこの_method: "PUT",もキーと値として送信される
- 結論として、Inertiaのフォーム内で **_method: "PUT"** を使用するか、もしくは headers: {'X-HTTP-Method-Override': 'PUT'} を設定する方法が推奨されます

### larastanの設定
larastanはLaravelに特化した静的解析ツール、PHPStanをベースにしている

インストール
```bash
$ composer require nunomaduro/larastan --dev
```

設定ファイル
```bash
$ touch phpstan.neon
```

実行
```bash
$ ./vendor/bin/phpstan analyse
```

### Stripe WebhookのCSRF保護除外
Stripeなどの外部サービスから送信されるWebhook（サーバーへの通知）は、セキュリティ上の理由からCSRFトークンを持ちません  
LaravelのCSRF保護機能がこれらのリクエストをブロックしないよう、該当のエンドポイントを認証から明示的に除外しています  
エンドポイントをstripe/webhookとして、stripe/*以下をcsrf認証から外しておく  
bootstrapのappで以下を記載する

```php
$middleware->validateCsrfTokens(except: [
        'stripe/*',
    ]);
```

## レコメンデーション実装
1. レコメンデーションのアルゴリズム
商品名や説明文をベクトルに変換し、その近似値 (類似度) によって類似製品のレコメンデーションを表示します。

**日本語形態素解析**: MeCab (ateliee/mecab) を使用し、テキストを単語(トークン)に分割。

**特徴量抽出**: php-ml を使用し、以下の手法でベクトル化

- TokenCountVectorizer (トークンカウント)

- TfIdfTransformer (TF-IDF変換)

**ベクトルの次元**: 500次元に固定長として設定。足りない場合は0埋めを行います。

2. MeCab (ateliee/mecab) の使用
日本語解析にはMeCabが必要です。OSへのインストール時、文字化け防止のためUTF-8で言語設定を行います。Laravel8以降で互換性のある ateliee/mecabを使用します。
**注意点**: php-mlのストップワード機能は主に英語用であるため、MeCabで解析したトークンに対し、別途定義した日本語ストップワードの除外処理を行っています。

3. pgvector によるベクトルデータの永続化
ベクトルデータをPostgreSQLに効率的に保存し、類似性検索を可能にするため、pgvector拡張機能とpgvector-phpライブラリを使用しています。

**Supabase設定**: ダッシュボードのExtensionsでpgvectorを有効化する際、マイグレーションエラーを避けるためpublicスキーマにインストールしています。

**Laravelでの保存**: pgvector-phpの制約により、ベクトル型のカラムはEloquentのsaveメソッドでのみ保存可能です（insert / upsert は使用不可）。

**インデックス**: HNSWやIVFFlatなどのインデックスを作成することで、類似検索のパフォーマンスを向上させています。

4. 非同期処理 (Job Queue)
ベクトル変換処理は時間がかかるため、商品登録とは非同期で処理を行うためジョブキューを使用しています。

**Job**: VectorizeProductジョブを作成し、新規登録サービスからディスパッチしています。

5. ベクトル検索 (pgvector-php)
保存されたベクトルは、L2 DistanceやCosine類似度などの距離関数を使用して類似検索を行います。
```php
use Pgvector\Laravel\Distance;

// L2距離で最も近い5件のアイテムを取得
$neighbors = Item::query()->nearestNeighbors('embedding', $queryVector, Distance::L2)->take(5)->get();
```

## まとめ

### Laravel 11について
- laravelは以前に比べてディレクトリもファイルも少なく分かりやすくなった印象
- イベントやリスナー、ポリシーなど作成するとディレクトリは結局増える
- スリム化されたのでエイリアス設定が分かり難くなった印象
- breezeはjetstreamやfortifyと同じくインストール時点でほぼ完成されている
- DB接続しなくても認証できるのはデフォルトでSQLiteが設定されている

### インフラ・外部サービス連携
- 今回は使用していないがdiskのs3ドライバでsupabaseもs3互換が使えそう
- laravelからsupabase strorageへの接続はSDKを入れるとlaravelの意味がないのでREST APIを使用した
- sentryを導入してエラートラッキングを実装、本番環境での監視に有効

### エラーハンドリング
- Sentryを導入し、本番環境でのエラートラッキングとパフォーマンス監視を実装しています
- laravel側では、bootstrap/app.phpのwithExceptionsでSentry統合
- 既存のErrorLogServiceと併用（Sentryは本番用、storage/logs/は開発用）
- before_sendでStripe決済情報などの機密データをフィルタリング
- tagをつけてエラーを分類  

| フィルタ | 説明 |
|---------|------|
| `platform:laravel` | Laravelバックエンドのエラー |
| `platform:vue AND ssr:true` | Vue SSR（サーバー）のエラー |
| `platform:vue AND ssr:false` | Vueクライアントサイド（ブラウザ）のエラー |

### フロントエンド開発
- eslintはparserでエラー、flat configに絞った方が上手くいった
- mixからviteになっていたが、設定しないと普通に遅い
- laravelだけでも覚える事が多くinertia/ziggyなどもあるので、フロントエンドの開発者はapi onlyで使った方が分かりやすいかも知れない
- inertiaのPartial reloadsは検索結果表示やページネーションとの相性が悪いと感じる
- DB処理後の更新としてrouter.reloadの使いどころは多いと感じる
- defineModelでオプショナルがあるとbuild時に型は通ってもInvalid assignment targetのエラーが出るので初期値を設定する必要がある
- コンボボックスはアクセシビリティ設定をした方が良い
- sentryをresource/app.tsとssr.tsに設置しています

### データベース・クエリ
- whereHasは言われているほど遅くも重くもない
- withException内でModelNotFoundExceptionが取得できなかったのでNotFoundHttpExceptionで代用しrenderで処理する

### 機械学習・自然言語処理
- 日本語形態素解析やベクトル化は外部apiが幾つかあるが、今回はmecab、php-mlで行っている
- 形態素解析のラッパーmecab-phpはlaravel8以降でエラーが出るのでateliee/mecabを使用する
- php-mlのストップワードは英語なので、日本語用として幾つか設定する
- laravelでベクトル型を保存する場合、DBはsupabaseのpostgresなのでpgvectorを有効化しlaravelでpgvector-phpを使用してeloquentに対応させる
- 商品登録時に再計算するので商品データ保存とは非同期で別にジョブキューでベクトル変換処理を行う
- ベクトルの次元は500次元の固定長としている
- pgvector-phpではinsert/upsertが使用できないのでsaveで行っている
- pgvectorのnearestNeighborsでL2近傍やコサイン類似などのクエリを発行できる
- pythonの方が機械学習は前処理含めてライブラリや関数も豊富で良いとは思うが、php/laravelでも一応同じような事はできる

### 今後試したいこと
- hotwire/livewireは次の機会に使ってみようと思う

## CodespacesでのLaravel環境構築

### よくあるエラー: OpenSSL バージョン不一致

LaravelプロジェクトをCodespacesで開発しようとした際に、以下のエラーが出る場合があります:
```
php: /lib/x86_64-linux-gnu/libcrypto.so.1.1: version `OPENSSL_1_1_1' not found
```

**原因**: PHPがリンクしているOpenSSLライブラリのバージョンが合っていない

このエラーが出ると`php -v`も動作しません。

---

### 解決方法

#### 方法1: devcontainer を使用（推奨）

`.devcontainer/`ディレクトリに開発環境専用の設定を作成することで、自動的に正しい環境が構築されます。

**ディレクトリ構成:**
```text
your-project/
├── .devcontainer/
│   ├── devcontainer.json
│   └── Dockerfile # 開発環境用
├── Dockerfile     # 本番環境用
└── ...
```

**`.devcontainer/Dockerfile`:**
```dockerfile
FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye

# システムライブラリをインストール
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y \
    libpq-dev libzip-dev libpng-dev libjpeg-dev \
    libfreetype6-dev libicu-dev curl \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# PHP拡張をビルド
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) bcmath pdo_pgsql pgsql zip gd intl

# 拡張を明示的に有効化（重要）
RUN echo "extension=bcmath.so" > /usr/local/etc/php/conf.d/docker-php-ext-bcmath.ini \
    && echo "extension=pdo_pgsql.so" > /usr/local/etc/php/conf.d/docker-php-ext-pdo_pgsql.ini

# Node.js 20.x をインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs && npm install -g npm@latest

# Xdebugを無効化
RUN mv /usr/local/etc/php/conf.d/xdebug.ini \
       /usr/local/etc/php/conf.d/xdebug.ini.disabled 2>/dev/null || true
```

**`.devcontainer/devcontainer.json`:**
```jsonc
{
  "name": "Laravel 11 Development",
  "build": {
    "dockerfile": "Dockerfile"
  },
  "postCreateCommand": "composer install && npm install",
  "forwardPorts": [8000, 5173],
  "customizations": {
    "vscode": {
      "extensions": [
        "bmewburn.vscode-intelephense-client",
        "Vue.volar",
        "bradlc.vscode-tailwindcss"
      ]
    }
  },
  "remoteUser": "vscode"
}
```

コンテナ作成後、VSCodeで`Dev Containers: Rebuild Container`を実行してください。

---

#### 方法2: 手動インストール（一時的な対処）

devcontainerを使わない場合、以下の手順で手動インストールできます。ただし、**コンテナを再作成するたびに再設定が必要**です。

1. **PHP拡張をインストール**
```bash
   sudo docker-php-ext-install bcmath
```
   この時点では`php -m | grep bcmath`はヒットしません。

2. **拡張を手動で有効化**
```bash
   echo "extension=bcmath" | sudo tee /usr/local/etc/php/conf.d/docker-php-ext-bcmath.ini
```
   この後`php -m | grep bcmath`でヒットするようになります。

3. **動作確認**
```bash
   php -v          # バージョンが表示されればOK
   php -m | grep bcmath  # bcmathが表示されればOK
```

4. **Composer依存関係をインストール**
```bash
   composer install
```

---

### Node.jsのインストール

Inertia/Vue開発には Node.js が必要です:
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs
npm install
```

---

### 注意事項

- **本番環境用Dockerfile（ルート）**: 既存のDockerfileは本番環境用です。変更しないでください。
- **開発環境用Dockerfile（.devcontainer/）**: 開発環境専用の設定です。本番には影響しません。
- **devcontainerの利点**: 
  - チーム全員が同じ環境で開発できる
  - コンテナ再作成時も自動セットアップ
  - 手動インストールの手間が不要

