<?php
require_once './bootstrap.php';

if (isset($_POST['logout'])) {
  // トークンの正当性チェック
  if (empty($_SESSION['logout_token']) ||
($_SESSION['logout_token'] !== $_POST['logout_token'])) {
    exit('不正な投稿です');
  }

  // トークンの破棄
  if (isset($_SESSION['logout_token']))
unset($_SESSION['logout_token']);
  if (isset($_POST['logout_token']))
unset($_POST['logout_token']);

  // ログアウト実行（この中でリダイレクト+exitされる）
  executeLogout();
}
