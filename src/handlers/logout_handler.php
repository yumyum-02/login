<?php
if (isset($_POST['logout'])) {
  // CSRFトークン検証
  if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    exit('不正な投稿です');
  }

  // ログアウト実行（この中でリダイレクト+exitされる）
  executeLogout();
}