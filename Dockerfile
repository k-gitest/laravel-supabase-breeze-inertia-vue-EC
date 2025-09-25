# 1. Node.js ビルドステージ: フロントエンドアセットをビルド
# 最終イメージを軽量化するための中間ステージ
FROM node:20-alpine as node-builder
WORKDIR /app

# package.json と package-lock.json のみを先にコピーして、依存関係インストールレイヤーのキャッシュを有効にする
COPY package*.json ./
RUN npm ci

# アセットビルドに必要なソースファイルをコピー
COPY resources/js ./resources/js
COPY resources/css ./resources/css  
COPY vite.config.* ./
COPY tsconfig.* ./
COPY tailwind.config.* ./

# 本番用のアセットとSSR用コードをビルド
RUN npm run build
RUN npm run build:ssr

---

# 2. PHP ビルドステージ: PHPの依存関係をインストール
# 本番環境で不要な開発用パッケージを含めないためのステージ
FROM composer:2 as php-builder
WORKDIR /app

# composer.json と composer.lock のみを先にコピーして、依存関係インストールレイヤーのキャッシュを有効にする
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts

---

# 3. 最終ステージ: アプリケーションの実行環境を構築
# 最終的にデプロイされる本番環境用イメージ
FROM php:8.2-fpm-alpine

# 必要なシステムパッケージとPHP拡張を一括でインストール
# レイヤー数を減らすために、一つのRUNコマンドにまとめている
RUN apk add --no-cache \
    nginx \
    supervisor \
    nodejs \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    postgresql-dev \
    oniguruma-dev \
    libxml2-dev \
    mecab \
    mecab-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache

WORKDIR /var/www

# php-builder ステージからインストール済みのPHP依存関係（vendorディレクトリ）をコピー
COPY --from=php-builder /app/vendor ./vendor

# アプリケーションの全ファイルを最終イメージにコピー
COPY . .

# node-builder ステージからビルド済みのアセットをコピー
COPY --from=node-builder /app/public/build ./public/build
COPY --from=node-builder /app/bootstrap/ssr ./bootstrap/ssr

# Composerのオートローダーを本番用に最適化
RUN composer dump-autoload --optimize

# nginxとsupervisorの設定ファイルをコピー
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisor/supervisord-ssr.conf /etc/supervisor/conf.d/supervisord.conf

# 実行ユーザーの権限を設定し、Laravelがファイルに書き込めるようにする
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 storage bootstrap/cache

# 外部に公開するポートを設定
EXPOSE 80

# コンテナ起動時にSupervisorを起動
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]