# 開発環境セットアップガイド

## 目次
1. [GitHub Codespaces（推奨）](#github-codespaces)
2. [ローカル環境](#ローカル環境)
3. [トラブルシューティング](#トラブルシューティング)

---

## GitHub Codespaces

### なぜCodespacesを推奨するのか
- 環境構築が自動化
- チーム全員が同じ環境

### セットアップ手順
1. GitHubリポジトリで「Code」→「Create codespace」
2. 自動で環境構築（`.devcontainer/` の設定が適用）
3. `composer install && npm install` が自動実行
4. `.env` を設定
5. `php artisan serve` でアプリ起動

### devcontainer の構成
```
.devcontainer/
├── devcontainer.json  # VSCode設定、拡張機能
└── Dockerfile         # PHP 8.2, Node.js 20.x, PostgreSQL Client
```

---

## ローカル環境

### 必要な環境
- PHP 8.2以上
- Composer 2.x
- Node.js 20.x
- PostgreSQL（Supabase使用の場合は不要）

---

## トラブルシューティング

### OpenSSL バージョン不一致エラー

**エラー**:
```
php: /lib/x86_64-linux-gnu/libcrypto.so.1.1: version `OPENSSL_1_1_1' not found
```

**原因**: PHPとシステムのOpenSSLバージョン不一致

**解決策**:
1. **Codespaces使用（推奨）**: devcontainerで自動解決
2. **ローカル環境**: PHP再インストールまたはDockerコンテナ使用

詳細な手順:
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