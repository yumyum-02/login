<?php
require_once '../src/bootstrap.php';
require_once '../src/functions/logout.php';

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

require '../src/template/admin_template.php';
