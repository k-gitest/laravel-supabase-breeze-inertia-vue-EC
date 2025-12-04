## 目次
1. [概要](#概要)
2. [主な機能](#主な機能)
3. [セットアップ](#セットアップ)
4. [アーキテクチャ](#アーキテクチャ)
5. [データベース設計](#データベース設計)
6. [開発上の注意点](#開発上の注意点)
7. [レコメンデーション実装](#レコメンデーション実装)
8. [まとめ](#まとめ)

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
```bash
# 依存関係のインストール
composer install
npm install

# アプリ起動
php artisan serve  # http://localhost:8000
npm run dev        # http://localhost:5173

# データベース
php artisan migrate
php artisan storage:link
```
詳細は [SETUP.md](./SETUP.md) を参照してください。

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

## データベース設計

### テーブル構成（15テーブル）

**ユーザー関連**:
- users, admins

**EC機能**:
- products, categories, images
- carts, orders, order_items

**在庫管理**:
- stocks, warehouses

**ユーザー機能**:
- comments, favorites

**その他**:
- todo_lists, contacts

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

### なぜこの実装を選んだか

**通常のアプローチ**:
- Pythonライブラリなど
- 外部API（OpenAI Embeddings、Cohere等）

**このプロジェクトでの選択**:
- Laravel（PHP）環境で完結させたい
- 外部APIコストを避けたい
- Supabase PostgreSQL（pgvector）を活用したい
- 学習目的でPHP環境での機械学習を試したい

→ **PHP + MeCab + php-ml + pgvectorという構成**

### 実装の概要

商品名や説明文をベクトルに変換し、類似度によって類似製品のレコメンデーションを表示します。

1. レコメンデーションのアルゴリズム
**日本語形態素解析**: MeCab (ateliee/mecab) を使用し、テキストを単語(トークン)に分割。

**特徴量抽出**: php-ml を使用し、以下の手法でベクトル化

- TokenCountVectorizer (トークンカウント)

- TfIdfTransformer (TF-IDF変換)

**ベクトルの次元**: 500次元に固定長として設定。足りない場合は0埋めを行います。

2. MeCab (ateliee/mecab) の使用
日本語解析にはMeCabが必要です。OSへのインストール時、文字化け防止のためUTF-8で言語設定を行います。Laravel8以降で互換性のある ateliee/mecabを使用します。
**注意点**: php-mlのストップワード機能は主に英語用であるため、MeCabで解析したトークンに対し、別途定義した日本語ストップワードの除外処理を行っています。

#### 日本語トークンのクレンジング処理

**課題**: MeCabの出力には不要な文字・記号が含まれる

**実装した正規表現**:
```php
preg_match('/^[\p{L}\p{N}ー々〆〤]+$/u', $token)
```

**この正規表現の意味**:
- `\p{L}`: すべての文字（Unicode対応）
- `\p{N}`: すべての数字
- `ー`: 長音記号（カタカナ用）
- `々`: 踊り字（「人々」「時々」等）
- `〆`: 締め字（「〆切」等）
- `〤`: 日本語記号

**なぜこれが必要か**:
- MeCabは「人々」を「人」「々」に分割する
- 「々」を除外すると「人」だけになり意味が変わる
- 日本語特有の文字を残す必要がある

**試行錯誤**:
1. 最初: `preg_match('/^[ぁ-ん]+$/u')` → カタカナ・漢字が除外される
2. 次: `preg_match('/^[\p{Hiragana}\p{Katakana}\p{Han}]+$/u')` → 「ー」が除外される
3. 最終: 上記の形（日本語特有文字を個別に追加）

---

#### ストップワードの自前実装

**課題**: php-mlのStopWords\Englishは日本語に対応していない

**実装したストップワード**:
```php
$this->stopwords = [
    'これ', 'は', 'です', 'の', 'を', 'が', 'に', 'と', 'も', 'し', 
    'ます', 'た', 'り', 'な', 'で', 'ない', 'か', 'いう', 'こと',
    '。', '、', '「', '」', '（', '）'  // 句読点・括弧も除外
];
```

**選定基準**:
- 助詞: 「は」「が」「を」「に」等（文法的機能のみ、意味なし）
- 補助動詞: 「ます」「です」「た」等（丁寧表現、意味に寄与しない）
- 代名詞: 「これ」「それ」等（文脈依存、商品説明では無意味）
- 記号: 句読点、括弧（TF-IDFの計算を歪める）

**検証方法**:
```php
// 実際の商品説明で検証
$text = "この商品は高品質な素材を使用しています。";
$tokens = $this->tokenizeText($text);
// 期待: ['商品', '高品質', '素材', '使用']
// 除外: ['この', 'は', 'を', 'います', '。']
```

**妥協点**:
- 実際の商品データで頻出する無意味語を手動追加
- 完全なストップワードリストは1000語以上
- 現状30語程度（パフォーマンスと精度のバランス）

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
- **効果検証が不十分**: 次元数500はphp-mlのサンプルを参考にした程度
- **精度の定量評価なし**: 手動で目視確認のみ
- **ベンチマーク未実施**: 大規模データでの性能は未検証
- **英語商品未対応**: MeCabは日本語専用

### 未実装・今後の課題

#### テスト
- 決済フローのFeature Test（Stripe Mock使用）
- ベクトル変換のUnit Test
- E2Eテスト（Playwright導入検討）

#### パフォーマンス
- Redis導入（キャッシュ戦略）
- laravel/telescope導入（N+1監視）
- ベクトル検索のベンチマーク測定
- **常駐型サーバーによる高速化の検討**
    - **Laravel Octane / RoadRunner (または Swoole)** の導入検討
    - **状態残留リスクの調査とリセット戦略**の確立（`RequestTerminated`イベントの利用検討を含む）

#### ドキュメント
- API仕様書
- デプロイ手順書

