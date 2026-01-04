<?php
require_once './bootstrap.php';
require_once './logout.php';

if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

$users_info = getUsersInfo();

$pdo = null;
$stmt = null;

$token = bin2hex(random_bytes(32));
$_SESSION['logout_token'] = $token;

require './template/admin_template.php';
