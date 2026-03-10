<?php
require '../src/bootstrap.php';

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}

$name = getTrimmedPostValue('name');
$email = getTrimmedPostValue('email');
$password = $_POST['password'] ?? '';

// バリデーション
// バリデーションのためのエラー配列を用意
$errors = [
  'name' => getUserNameValidationErrors($name),
  'email' => getMailValidationErrors($email),
  'password' => getPasswordValidationErrors($password)
];

// エラーチェック（いずれかのフィールドにエラーがあるか）
$hasErrors = !empty($errors['name']) || !empty($errors['email']) || !empty($errors['password']);
// エラーがあれば登録処理を中止してフォームに戻る
if ($hasErrors) {
  // エラー時はCSRFトークンを生成
  $csrf_token = generateCsrfToken();
  redirectWithErrors($errors, ['name' => $name, 'email' => $email],'./regist.php');
}

//メールアドレスを小文字に変換
//メールアドレスは大文字小文字を区別しないためtest@example.comとTest@Example.comは同じメアドだが、別のメアドとして重複判定から漏れる
$email = mb_strtolower($email, 'UTF-8');

// パスワードのハッシュ化
$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {
  // すでに登録されているIDかどうか確認 db.phpの関数を使用
  $user_info = getUserRegister($email);

  // すでに登録されているメールアドレスの場合はエラーに追加
  if (count($user_info)) {
    $errors['email'][] = 'そのメールアドレスはすでに使用されています。';
    redirectWithErrors($errors, ['name' => $name, 'email' => $email],'./regist.php');
  }

  // 登録されていないメールアドレスの場合は、usersテーブルに新規登録
  $user_id = registerUser($name, $email, $password_hash);

  // 登録成功時はCSRFトークンを破棄
  destroyCsrfToken();

  $_SESSION['msg'] = "会員登録が完了しました。ログインしてください。";
  redirect('./login.php');
} catch (PDOException $e) {
  echo '接続失敗' . $e->getMessage();
  exit();
}
