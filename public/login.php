<?php
require_once '../src/bootstrap.php';

// メッセージ取得
$msg = getMessage();

// ページ固有の処理
// エラーメッセージの表示
$errors = $_SESSION['errors'] ?? [];
$err_msg = $_SESSION['error_message'] ?? null;
// 一度表示したらクリア
unset($_SESSION['errors'], $_SESSION['error_message']);

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

require_once '../src/template/login_template.php';
