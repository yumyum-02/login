<?php
require_once './bootstrap.php';
require_once './logout.php';

if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

$users_info = getUsersInfo();

// セッションメッセージがあればGETパラメータに移す
if (isset($_SESSION['msg'])) {
  $_GET['msg'] = $_SESSION['msg'];
  unset($_SESSION['msg']);
}

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

require './template/admin_template.php';
