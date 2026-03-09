<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// セッションからエラーと入力値を取得
$errors = $_SESSION['errors'] ?? [];
$old_input = $_SESSION['old_input'] ?? [];

// セッションをクリア（一度表示したらクリア）
unset($_SESSION['errors'] ,$_SESSION['old_input']);

// テンプレート読み込み
require_once dirname(__DIR__, 2) . '/src/template/edit-profile_template.php';