<?php
// ログアウト処理
function executeLogout(): void
{
  // ログイン情報のみ削除（他のセッションデータは保持）
  unset($_SESSION['user']);

  // セッションIDを再生成（セキュリティ対策）
  session_regenerate_id(true);

  // ログアウトメッセージを設定
  $_SESSION['msg'] = 'ログアウトしました。';

  // クリーンなURLでリダイレクト
  header('Location: ./login.php');
  exit();
}