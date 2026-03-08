<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  redirect('../login.php');
}

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
  $pdo = connectDb();
  $stmt = $pdo->prepare('UPDATE users SET name = :name WHERE id = :id');
  $stmt->execute([
      ':name' => $name,
      ':id' => $_SESSION['user']['id']
  ]);

  // セッション更新
  $_SESSION['user']['name'] = $name;
  $_SESSION['success_message'] = 'ユーザー名を更新しました';

  redirect('../admin/account.php');
} catch (PDOException $e) {
  $_SESSION['error_message'] = 'データベースエラーが発生しました';
  redirect('./edit-profile.php');
}