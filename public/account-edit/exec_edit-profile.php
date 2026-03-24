<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークン検証
requireValidCsrfToken();

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
  redirect('../admin/account.php');
} catch (InvalidArgumentException $e) {
  // 関数に渡された引数が間違っている(PHPに用意されている例外クラス)
  // 不正なフィールド名エラー（通常は発生しない）
  // なんらかのバグで値が途中で書き変わったりした時用
  redirectWithErrors(['システムエラーが発生しました'], ['name' => $name], './edit-profile.php');
} catch (PDOException $e) {
  // データベースエラー
  redirectWithErrors(['データベースエラーが発生しました'], ['name' => $name], './edit-profile.php');
}