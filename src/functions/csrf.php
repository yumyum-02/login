<?php

// CSRFトークンを生成してセッションに保存（既に存在する場合は既存のトークンを返す）
function generateCsrfToken(): string
{
  if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

// CSRFトークンを検証（セッションのトークンと送信されたトークンを比較）
function verifyCsrfToken(string $token): bool
{
  return !empty($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

// CSRFトークンを破棄（ログイン成功時や登録成功時など、処理完了後に呼び出す）
function destroyCsrfToken(): void
{
  unset($_SESSION['csrf_token']);
}
