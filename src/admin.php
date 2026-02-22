<?php
require_once './bootstrap.php';
require_once './logout.php';

if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

$users_info = getUsersInfo();

$token = bin2hex(random_bytes(32));
$_SESSION['logout_token'] = $token;
$_SESSION['delete_token'] = bin2hex(random_bytes(32));

// セッションメッセージがあればGETパラメータに移す
if (isset($_SESSION['msg'])) {
  $_GET['msg'] = $_SESSION['msg'];
  unset($_SESSION['msg']);
}

require './template/admin_template.php';
