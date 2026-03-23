<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// ページ固有の処理
// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// テンプレート読み込み
require_once dirname(__DIR__, 2) . '/src/template/account_template.php';