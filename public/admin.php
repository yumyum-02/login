<?php
require_once '../src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

// ログアウト処理（ログイン済みユーザーのみ実行可能）
require_once '../src/handlers/logout_handler.php';

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
require '../src/template/admin_template.php';
