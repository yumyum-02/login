<?php
if (isset($_POST['logout'])) {
  // CSRFトークン検証
  if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    exit('不正な投稿です');
  }

  // ログアウト実行（この中でリダイレクト+exitされる）
  executeLogout();
}

// ログアウト処理を実行
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