<?php
require_once '../src/bootstrap.php';

// ログアウト成功メッセージ
if (isset($_GET['msg'])) $success_logout_msg = $_GET['msg'];

// セッションメッセージ
$login_msg = '';
if (isset($_SESSION['msg'])) {
  $login_msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
}

// ログインエラーメッセージ
$err_msg = '';
if (isset($_SESSION['login_error_msg'])) {
  $err_msg = $_SESSION['login_error_msg'];
  unset($_SESSION['login_error_msg']);
}

// バリデーションエラー
$errors = [];
if (isset($_SESSION['login_errors'])) {
  $errors = $_SESSION['login_errors'];
  unset($_SESSION['login_errors']);
}

// 入力値の保持
$email = '';
if (isset($_SESSION['login_email'])) {
  $email = $_SESSION['login_email'];
  unset($_SESSION['login_email']);
}

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

require_once '../src/template/login_template.php';
