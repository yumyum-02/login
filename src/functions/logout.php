<?php
// ログアウト処理
function executeLogout(): void
{
  // 古いセッションを破棄
  $_SESSION = [];
  if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }
  session_destroy();

  // 新しいセッションを開始してメッセージを保存
  session_start();
  $_SESSION['msg'] = 'ログアウトしました。';

  // クリーンなURLでリダイレクト
  header('Location: ./login.php');
  exit();
}