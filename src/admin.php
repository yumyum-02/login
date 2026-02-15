<?php
require_once './bootstrap.php';
require_once './logout.php';
require_once './exec_delete_user.php';

if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

// 削除リクエストの場合は削除処理を実行（リダイレクトで終了する）
if (isset($_POST['delete_user'])) {
  processUserDelete();
}

$users_info = getUsersInfo();

$pdo = null;
$stmt = null;

$token = bin2hex(random_bytes(32));
$_SESSION['logout_token'] = $token;

$_SESSION['delete_token'] = bin2hex(random_bytes(32));

require './template/admin_template.php';
