<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}
// CSRFトークンを破棄
destroyCsrfToken();

// バリデーション
//　空白を削除
$name = getTrimmedPostValue('name');
// ユーザー名のバリデーション
$errors = getUserNameValidationErrors($name);
// エラーがあれば登録処理を中止してフォームに戻る
if (!empty($errors)) {
  redirectWithErrors($errors,['name' => $name],'./edit-profile.php');
}

try {
  updateUser($_SESSION['user']['id'], 'name', $name);

  // セッション更新
  $_SESSION['user']['name'] = $name;
  $_SESSION['success_message'] = 'ユーザー名を更新しました';
  redirect('../admin/account.php');
} catch (InvalidArgumentException $e) {
  // 不正なフィールド名エラー（通常は発生しない）
  $_SESSION['error_message'] = 'システムエラーが発生しました';
  redirect('./edit-profile.php');
} catch (PDOException $e) {
  // データベースエラー
  $_SESSION['error_message'] = 'データベースエラーが発生しました';
  redirect('./edit-profile.php');
}