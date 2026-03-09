<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークンを生成してaccount_template.phpのinputに埋め込む
$csrf_token = generateCsrfToken();

// セッションからエラーと入力値を取得
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];
// セッションをクリア
unset($_SESSION['errors'] ,$_SESSION['old_input']);

require_once dirname(__DIR__, 2) . '/src/template/edit-password_template.php';