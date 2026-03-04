<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}

// バリデーション
//　空白を削除
$name = getTrimmedPostValue('name');
// ユーザー名のバリデーション
$errors = validateUserName($name);
// エラーがあれば登録処理を中止してフォームに戻る
if (!empty($errors)) {
  redirectWithErrors(
      $errors,
      ['name' => $name],
      './profile-edit.php'
  );
}

try {
  $pdo = connectDb();
  $stmt = $pdo->prepare('UPDATE users SET name = :name WHERE id =
:id');
  $stmt->execute([
      ':name' => $name,
      ':id' => $_SESSION['user']['id']
  ]);

  // セッション更新
  $_SESSION['user']['name'] = $name;
  $_SESSION['success_message'] = 'ユーザー名を更新しました';

  header('Location: ../admin/account.php');
  exit;
} catch (PDOException $e) {
  $_SESSION['error_message'] = 'データベースエラーが発生しました';
  header('Location: ./profile-edit.php');
  exit;
}