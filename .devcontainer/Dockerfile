FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye

# 必要なシステムライブラリをインストール
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    curl \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# PHP拡張をdocker-php-ext-installでビルド
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    pdo_pgsql \
    pgsql \
    zip \
    gd \
    intl

# あなたが発見した方法: bcmath拡張を明示的に有効化
RUN echo "extension=bcmath.so" > /usr/local/etc/php/conf.d/docker-php-ext-bcmath.ini \
    && echo "extension=pdo_pgsql.so" > /usr/local/etc/php/conf.d/docker-php-ext-pdo_pgsql.ini \
    && echo "extension=pgsql.so" > /usr/local/etc/php/conf.d/docker-php-ext-pgsql.ini

# Node.js 20.x をインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# Xdebugを無効化
RUN mv /usr/local/etc/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini.disabled 2>/dev/null || true

# 確認
RUN php -m | grep -E 'bcmath|pdo_pgsql|pgsql|gd|zip|intl'