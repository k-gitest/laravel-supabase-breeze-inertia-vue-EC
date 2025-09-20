# 1. Node.js build stage
FROM node:20-alpine as node-builder
WORKDIR /app

# package.json をコピーして依存関係をインストール
COPY package*.json ./
RUN npm ci

# フロントエンド関連ファイルをコピー
COPY resources/js ./resources/js
COPY resources/css ./resources/css  
COPY vite.config.* ./
COPY tsconfig.* ./
COPY tailwind.config.* ./

# 通常のビルド + SSRビルド
RUN npm run build
RUN npm run build:ssr

# 2. PHP build stage  
FROM composer:2 as php-builder
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-scripts

# 3. Final stage
FROM php:8.2-fpm-alpine

# 必要なパッケージとPHP拡張をインストール
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

# PHP依存関係をコピー
COPY --from=php-builder /app/vendor ./vendor

# アプリケーションファイルをコピー  
COPY . .

# ビルド済みアセットをコピー
COPY --from=node-builder /app/public/build ./public/build
COPY --from=node-builder /app/bootstrap/ssr ./bootstrap/ssr

# Composer autoload最適化
RUN composer dump-autoload --optimize

# 設定ファイル
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisor/supervisord-ssr.conf /etc/supervisor/conf.d/supervisord.conf

# 権限設定
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 storage bootstrap/cache

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]