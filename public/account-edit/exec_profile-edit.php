<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}

// バリデーション
$name = getTrimmedPostValue('name');
// バリデーションのためのエラー配列を用意
$errors = [];
// ユーザー名のバリデーション
if (isEmpty($name)) {
  $errors[] = 'ユーザー名を入力してください。';
} else {
  if (!isUserNameFormat($name)) {
    $errors[] = '使用できない文字が含まれています。';
  }
  if (!isWithinLength($name, 3, 16)) {
    $errors[] = 'ユーザー名は3文字以上16文字以内で入力してください。';
  }
}
// エラーチェック（いずれかのフィールドにエラーがあるか）
$hasErrors = !empty($errors);
// エラーがあれば登録処理を中止してフォームに戻る
if ($hasErrors) {
  $_SESSION['errors'] = $errors;  // エラー配列を保存
  $_SESSION['old_input'] = ['name' => $name];  // 入力値を保存（再表示用）
  header('Location: ./profile-edit.php');
  exit;
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