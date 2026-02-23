<?php
require_once './bootstrap.php';

if (isset($_POST['logout'])) {
  // トークンの正当性チェック
  if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    exit('不正な投稿です');
  }

  // ログアウト実行（この中でリダイレクト+exitされる）
  executeLogout();
}
