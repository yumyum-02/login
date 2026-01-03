<?php
if (isset($_POST['logout'])) {

  if (empty($_SESSION['logout_token']) || ($_SESSION['logout_token'] !== $_POST['logout_token'])) exit('不正な投稿です');
  if (isset($_SESSION['logout_token'])) unset($_SESSION['logout_token']);
  if (isset($_POST['logout_token'])) unset($_POST['logout_token']);

  $_SESSION = [];
  if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/');
  session_destroy();

  $msg = urlencode("ログアウトしました。");
  header('Location: ./login.php?msg=' . $msg);
  exit();
}
