<?php
// ログアウト処理
function executeLogout(): void
{
  $_SESSION = [];
  if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }
  session_destroy();

  $msg = urlencode("ログアウトしました。");
  header('Location: ./login.php?msg=' . $msg);
  exit();
}