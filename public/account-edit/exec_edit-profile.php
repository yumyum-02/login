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
  updateUser(AuthUser::getUserId(), 'name', $name);

  // セッション更新
  AuthUser::setName($name);
  redirect('../admin/account.php');
} catch (InvalidArgumentException $e) {
  // 不正なフィールド名エラー（通常は発生しない）
  redirectWithErrors(['システムエラーが発生しました'], ['name' => $name], './edit-profile.php');
} catch (PDOException $e) {
  // データベースエラー
  redirectWithErrors(['データベースエラーが発生しました'], ['name' => $name], './edit-profile.php');
}