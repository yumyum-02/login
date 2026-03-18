<?php
// ログアウト処理（リダイレクトは行わない）
function executeLogout(): void
{
  // AuthUser クラスを使用してログアウト処理
  AuthUser::logout();

  // セッションIDを再生成（セキュリティ対策）
  session_regenerate_id(true);

  // ログアウトメッセージを設定
  $_SESSION['msg'] = 'ログアウトしました。';
}