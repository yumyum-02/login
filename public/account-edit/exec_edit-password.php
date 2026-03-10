<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '' )){
  exit('不正なリクエストです');
}
// CSRFトークンを破棄
destroyCsrfToken();

// バリデーション
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$new_password_confirm = $_POST['new_password_confirm'] ?? '';
// パスワードのバリデーション
$errors = [
  'current_password' => getCurrentPasswordErrors($current_password, $_SESSION['user']['id']),
  'new_password' => getPasswordValidationErrors($new_password),
  'new_password_confirm' => getPasswordCheck($new_password, $new_password_confirm)
];

$hasErrors = !empty($errors['current_password']) || !empty($errors['new_password']) || !empty($errors['new_password_confirm']);

// エラーがあれば登録処理を中止してフォームに戻る
if ($hasErrors){
  $_SESSION['errors'] = $errors;
  redirect('./edit-password.php');
}

// パスワードのハッシュ化
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

try {
  updateUserPassword($_SESSION['user']['id'], $password_hash);

  // セッションIDを再生成（セキュリティ対策）
  session_regenerate_id(true);
  // セッション更新
  $_SESSION['success_message'] = 'パスワードを更新しました';
  redirect('../admin/account.php');
} catch (PDOException $e) {
  $_SESSION['error_message'] = 'データベースエラーが発生しました';
  redirect('./edit-password.php');
}