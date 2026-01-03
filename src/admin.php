<?php
require './db.php';

session_start();
session_regenerate_id();

if (!isset($_SESSION['user'])) {
  header('Location: ./login.php');
  exit();
}

if (isset($_POST['logout'])) {

  if (empty($_SESSION['logout_token']) || ($_SESSION['logout_token'] !== $_POST['logout_token'])) exit('不正な投稿です');
  if (isset($_SESSION['logout_token'])) unset($_SESSION['logout_token']);
  if (isset($_POST['logout_token'])) unset($_POST['logout_token']);

  $_SESSION = array();
  if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/');
  session_destroy();

  $msg = urlencode("ログアウトしました。");
  header('Location: ./login.php?msg=' . $msg);
  exit();
}

$pdo = connectDb();
$sql = ('
    SELECT login_id, name
    FROM users
    ');
$stmt = $pdo->prepare($sql);
$stmt->execute();

$user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdo = null;
$stmt = null;
require './template/admin_template.php';
