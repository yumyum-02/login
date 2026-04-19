<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// ページ固有の処理
// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// セッションからエラーと入力値を取得
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);

// テンプレート読み込み
require_once dirname(__DIR__, 2) . '/src/template/chat-post_template.php';