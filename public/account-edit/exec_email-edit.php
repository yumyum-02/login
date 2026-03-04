<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}

// バリデーション
$email = getTrimmedPostValue('email');
// バリデーションのためのエラー配列を用意
$errors = [];
// メールアドレスのバリデーション
$errors = validateMail($email);

// エラーチェック
$hasErrors = !empty($errors);
// エラーがあれば登録処理を中止してフォームに戻る
if ($hasErrors) {
  $_SESSION['errors'] = $errors;  // エラー配列を保存
  $_SESSION['old_input'] = ['email' => $email];  // 入力値を保存（再表示用）
  header('Location: ./email-edit.php');
  exit;
}

try {
  $user_info = getUserRegister($email);
  // すでに登録されているメールアドレスの場合はエラーに追加
  if (count($user_info)) {
    $errors[] = 'そのメールアドレスはすでに使用されています。';
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = ['email' => $email];
    header('Location: ./email-edit.php');
    exit();
  }

  $pdo = connectDb();
  $stmt = $pdo->prepare('UPDATE users SET email = :email WHERE id =
:id');
  $result = $stmt->execute([
      ':email' => $email,
      ':id' => $_SESSION['user']['id']
  ]);

  // 実行結果をチェック
  if ($result === false) {
    $_SESSION['error_message'] ='更新に失敗しました。もう一度お試しください。';
    header('Location: ./email-edit.php');
    exit;
  }

  // 登録成功時はCSRFトークンを破棄
  destroyCsrfToken();

  // セッション更新
  $_SESSION['user']['email'] = $email;
  $_SESSION['success_message'] = 'メールアドレスを更新しました';

  header('Location: ../admin/account.php');
  exit;
} catch (PDOException $e) {
  $_SESSION['error_message'] = 'データベースエラーが発生しました';
  header('Location: ./email-edit.php');
  exit;
}