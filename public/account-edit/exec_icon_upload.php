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

// ファイルを取得
$file = $_FILES['icon'] ?? null;

// バリデーション
$errors = getIconValidationErrors($file);

// エラーがあればedit-icon.phpに戻る
if (!empty($errors)) {
  redirectWithErrors($errors, [], './edit-icon.php');
}

try {
  // 一時ファイルとして保存
  $tempFilename = saveTempIcon($_SESSION['user']['id'], $file);

  // セッションに一時ファイル名を保存
  $_SESSION['temp_icon'] = $tempFilename;

  // edit-icon.phpにリダイレクト（プレビュー表示）
  redirect('./edit-icon.php');
} catch (RuntimeException $e) {
  // ファイル保存エラー
  redirectWithErrors(['ファイルの保存に失敗しました'], [], './edit-icon.php');
}
