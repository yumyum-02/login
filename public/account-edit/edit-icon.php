<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// セッションからエラーを取得
$errors = $_SESSION['errors'] ?? [];

// セッションをクリア（一度表示したらクリア）
unset($_SESSION['errors']);

// テンプレート読み込み
require_once dirname(__DIR__, 2) . '/src/template/edit-icon_template.php';
