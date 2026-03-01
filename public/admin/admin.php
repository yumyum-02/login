<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

$users_info = getUsersInfo();

// ページ固有の処理
// セッションメッセージがあればGETパラメータに移す
if (isset($_SESSION['msg'])) {
  $_GET['msg'] = $_SESSION['msg'];
  unset($_SESSION['msg']);
}

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// テンプレート読み込み
require_once dirname(__DIR__, 2) . '/src/template/admin_template.php';
