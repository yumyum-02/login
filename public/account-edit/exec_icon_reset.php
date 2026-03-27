<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// アイコン管理関数を読み込み
require_once dirname(__DIR__, 2) . '/src/functions/icon.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークン検証
requireValidCsrfToken();

try {
  $userId = $_SESSION['user']['id'];

  // プレビュー用ファイル（{id}_temp.jpg など）を削除
  deleteTempIconFile($userId);
  // アイコンをリセット（該当ユーザーの画像を全削除、DB NULL）
  resetIcon($userId);

  // セッションのユーザー情報を更新
  $_SESSION['user']['icon'] = null;
  unset($_SESSION['temp_icon']);

  // 成功時は account.php にリダイレクト
  redirect('../admin/account.php');
} catch (Exception $e) {
  // エラー時はアイコン編集画面にリダイレクト
  redirectWithErrors(['アイコンのリセットに失敗しました'], [], '../account-edit/edit-icon.php');
}
