<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('./login.php');

$users_info = getUsersInfo();

// メッセージ取得
$msg = getMessage();

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// テンプレート読み込み
require_once dirname(__DIR__, 2) . '/src/template/admin_template.php';
