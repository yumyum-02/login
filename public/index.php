<?php
require_once '../src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

// ページ固有の処理
// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// テンプレート読み込み
require_once '../src/template/index_template.php';
