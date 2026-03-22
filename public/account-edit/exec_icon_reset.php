<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// アイコン管理関数を読み込み
require_once dirname(__DIR__, 2) . '/src/functions/icon.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}
// CSRFトークンを破棄
destroyCsrfToken();

try {
  // アイコンをリセット（本番ファイル削除、DB NULL）
  resetIcon($_SESSION['user']['id']);

  // セッションのユーザー情報を更新
  $_SESSION['user']['icon'] = null;

  // 成功時は account.php にリダイレクト
  redirect('../admin/account.php');
} catch (Exception $e) {
  // エラー時はアイコン編集画面にリダイレクト
  redirectWithErrors(['アイコンのリセットに失敗しました'], [], '../account-edit/edit-icon.php');
}
