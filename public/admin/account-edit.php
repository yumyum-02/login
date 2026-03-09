<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('./login.php');

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
require_once dirname(__DIR__, 2) . '/src/template/account-edit_template.php';