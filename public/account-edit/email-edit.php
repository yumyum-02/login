<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// セッションからエラーと入力値を取得
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];

// セッションをクリア（一度表示したらクリア）
unset($_SESSION['errors']);
unset($_SESSION['old_input']);

// テンプレート読み込み
require_once dirname(__DIR__, 2) . '/src/template/email-edit_template.php';