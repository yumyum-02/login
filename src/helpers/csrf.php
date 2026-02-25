<?php

/**
 * CSRFトークンを生成してセッションに保存
 * すでにトークンが存在する場合は既存のトークンを返す
 *
 * @return string 生成されたCSRFトークン
 */
function generateCsrfToken(): string
{
  if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

/**
 * CSRFトークンを検証
 * セッションのトークンと送信されたトークンを比較
 *
 * @param string $token 検証するトークン（POSTやGETから取得）
 * @return bool トークンが有効な場合true、無効な場合false
 */
function verifyCsrfToken(string $token): bool
{
  return !empty($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

/**
 * CSRFトークンを破棄
 * ログイン成功時や登録成功時など、処理完了後に呼び出す
 *
 * @return void
 */
function destroyCsrfToken(): void
{
  unset($_SESSION['csrf_token']);
}
