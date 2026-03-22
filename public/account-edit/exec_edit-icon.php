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

// セッションに一時ファイル名があるかチェック
if (empty($_SESSION['temp_icon'])) {
  redirectWithErrors(['画像がアップロードされていません'], [], './edit-icon.php');
}

try {
  // 一時ファイルを本番ファイルに変換
  $filename = confirmIcon(AuthUser::getUserId(), $_SESSION['temp_icon']);

  // DBを更新
  updateUserIcon(AuthUser::getUserId(), $filename);

  // セッションのユーザー情報を更新
  AuthUser::setIcon($filename);

  // セッションの一時ファイル名を削除
  unset($_SESSION['temp_icon']);

  // account.phpにリダイレクト
  redirect('../admin/account.php');
} catch (RuntimeException $e) {
  // エラー時はアイコン編集画面にリダイレクト
  redirectWithErrors(['アイコンの更新に失敗しました'], [], './edit-icon.php');
}
