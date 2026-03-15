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
  $_SESSION['error_message'] = '画像がアップロードされていません';
  redirect('./edit-icon.php');
}

try {
  // 一時ファイルを本番ファイルに変換
  $filename = confirmIcon($_SESSION['user']['id'], $_SESSION['temp_icon']);

  // DBを更新
  updateUserIcon($_SESSION['user']['id'], $filename);

  // セッションのユーザー情報を更新
  $_SESSION['user']['icon'] = $filename;

  // セッションの一時ファイル名を削除
  unset($_SESSION['temp_icon']);

  // 成功メッセージをセッションに保存
  $_SESSION['success_message'] = 'アイコンを更新しました';

  // account.phpにリダイレクト
  redirect('../admin/account.php');
} catch (RuntimeException $e) {
  // エラー処理
  $_SESSION['error_message'] = 'アイコンの更新に失敗しました';
  redirect('./edit-icon.php');
}
