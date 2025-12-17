# Laravel チーム開発プロジェクト

Laravel Sail を使用したDocker開発環境です。

## 技術スタック

| カテゴリ | 技術 |
|---------|------|
| Framework | Laravel 12 + Sail |
| Frontend | Livewire 3 |
| UI | Bootstrap 5 (CDN) |
| Database | MySQL 8.0 |
| DB管理 | phpMyAdmin |
| 認証 | Laravel Socialite (Google) |
| API | Gemini 2.5 Flash |

---

## 必要な環境

- **Windows**: WSL2 + Docker Desktop
- **Mac/Linux**: Docker

---

## セットアップ手順

### 1. リポジトリをクローン

```bash
git clone <repository-url>
cd Laravel-setup
```

### 2. 環境変数を設定

```bash
cp .env.example .env
```

`.env` ファイルを編集して以下を設定：

```env
# Google OAuth (必須)
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret

# Gemini API (必須)
GEMINI_API_KEY=your-api-key
```

### 3. Composerパッケージをインストール

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Sailを起動

```bash
./vendor/bin/sail up -d
```

### 5. アプリケーションキーを生成

```bash
./vendor/bin/sail artisan key:generate
```

### 6. データベースをマイグレーション

```bash
./vendor/bin/sail artisan migrate
```

### 7. フロントエンドをビルド

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

---

## アクセスURL

| サービス | URL |
|---------|-----|
| アプリケーション | http://localhost |
| phpMyAdmin | http://localhost:8080 |
| Vite (HMR) | http://localhost:5173 |

---

## よく使うコマンド

```bash
# Sail起動
./vendor/bin/sail up -d

# Sail停止
./vendor/bin/sail down

# Artisanコマンド
./vendor/bin/sail artisan <command>

# Composerコマンド
./vendor/bin/sail composer <command>

# NPMコマンド
./vendor/bin/sail npm <command>

# PHPUnit テスト
./vendor/bin/sail test
```

---

## Google OAuth 設定方法

1. [Google Cloud Console](https://console.cloud.google.com/apis/credentials) にアクセス
2. 「OAuth 2.0 クライアントID」を作成
3. 承認済みリダイレクトURIに `http://localhost/auth/google/callback` を追加
4. クライアントIDとシークレットを `.env` に設定

---

## Gemini API 設定方法

1. [Google AI Studio](https://aistudio.google.com/app/apikey) にアクセス
2. APIキーを作成
3. キーを `.env` の `GEMINI_API_KEY` に設定

---

## ディレクトリ構成

```
app/
├── Http/Controllers/Auth/
│   └── SocialiteController.php  # Google OAuth
├── Models/
│   └── User.php                 # Socialiteフィールド追加済み
└── Services/
    └── GeminiService.php        # Gemini API呼び出し

config/
└── services.php                 # Google/Gemini設定

routes/
└── web.php                      # OAuth ルート
```
