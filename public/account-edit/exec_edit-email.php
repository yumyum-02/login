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
    redirect('./edit-email.php');
  }

  $pdo = connectDb();
  $stmt = $pdo->prepare('UPDATE users SET email = :email WHERE id = :id');
  $result = $stmt->execute([
      ':email' => $email,
      ':id' => $_SESSION['user']['id']
  ]);

  // 実行結果をチェック
  if ($result === false) {
    $_SESSION['error_message'] ='更新に失敗しました。もう一度お試しください。';
    redirect('./edit-email.php');
  }

  // セッション更新
  $_SESSION['user']['email'] = $email;
  $_SESSION['success_message'] = 'メールアドレスを更新しました';

  redirect('../admin/account.php');
} catch (PDOException $e) {
  $_SESSION['error_message'] = 'データベースエラーが発生しました';
  redirect('./edit-email.php');
}