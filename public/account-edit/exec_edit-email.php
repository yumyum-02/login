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
$email = getTrimmedPostValue('email');
// メールアドレスのバリデーション
$errors = getMailValidationErrors($email);
// エラーがあれば登録処理を中止してフォームに戻る
if (!empty($errors)) {
    redirectWithErrors($errors,['email' => $email],'./edit-email.php');
}

try {
  $user_info = getUserRegister($email);
  // すでに登録されているメールアドレスの場合はエラーに追加
  if (count($user_info)) {
    $errors[] = 'そのメールアドレスはすでに使用されています。';
    redirectWithErrors($errors, ['email' => $email],'./edit-email.php');
  }
} catch (PDOException $e) {
  redirectWithErrors(['データベースエラーが発生しました'], ['email' => $email], './edit-email.php');
}

try {
  updateUser(AuthUser::getUserId(), 'email', $email);

  // セッション更新
  AuthUser::setEmail($email);
  redirect('../admin/account.php');
} catch (InvalidArgumentException $e) {
  // 不正なフィールド名エラー（通常は発生しない）
  redirectWithErrors(['システムエラーが発生しました'], ['email' => $email], './edit-email.php');
} catch (PDOException $e) {
  // データベースエラー
  redirectWithErrors(['データベースエラーが発生しました'], ['email' => $email], './edit-email.php');
}