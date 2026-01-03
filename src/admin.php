<?php
require_once './bootstrap.php';
require_once './logout.php';

if (!isset($_SESSION['user'])) {
  header('Location: ./noset.php');
  exit();
}

$users_info = getUsersInfo();

$pdo = null;
$stmt = null;

$token = sha1(uniqid(mt_rand(), true));
$_SESSION['logout_token'] = $token;

require './template/admin_template.php';
