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
  'name' => [],
  'email' => [],
  'password' => []
];

// ユーザー名のバリデーション
if (isEmpty($name)) {
  $errors['name'][] = 'ユーザー名を入力してください。';
} else {
  if (!isUserNameFormat($name)) {
    $errors['name'][] = '使用できない文字が含まれています。';
  }
  if (!isWithinLength($name, 3, 16)) {
    $errors['name'][] = 'ユーザー名は3文字以上16文字以内で入力してください。';
  }
}
// メールアドレスのバリデーション（形式と長さを分けてエラーを特定しやすくする）
if (isEmpty($email)) {
  $errors['email'][] = 'メールアドレスを入力してください。';
} else {
  if (!isEmailFormat($email)) {
    $errors['email'][] = 'メールアドレスの形式が正しくありません。';
  }
  // メールアドレスはローカルパートが最大64文字 + ドメインが255文字 + ＠で 合計320文字 MAX
  if (!isWithinLength($email, null, 320)) {
    $errors['email'][] = 'メールアドレスは320文字以内で入力してください。';
  }
}
// パスワードのバリデーション
if (isEmpty($password)) {
  $errors['password'][] = 'パスワードを入力してください。';
} else {
  if (!isPasswordFormat($password)) {
    $errors['password'][] = 'パスワードは半角英数字と記号で入力してください。';
  }
  if (!isWithinLength($password, 16, 64)) {
    $errors['password'][] = 'パスワードは16文字以上64文字以内で入力してください。';
  }
}
// エラーチェック（いずれかのフィールドにエラーがあるか）
$hasErrors = !empty($errors['name']) || !empty($errors['email']) || !empty($errors['password']);
// エラーがあれば登録処理を中止してフォームに戻る
if ($hasErrors) {
  // エラー時はCSRFトークンを再生成
  $csrf_token = regenerateCsrfToken();
  require '../src/template/regist_template.php';
  exit();
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
    $errors[] = 'そのメールアドレスはすでに使用されています。';
    // エラー時はCSRFトークンを再生成
    $csrf_token = regenerateCsrfToken();
    require '../src/template/regist_template.php';
    exit();
  }

  // 登録されていないメールアドレスの場合は、usersテーブルに新規登録
  $user_id = registerUser($name, $email, $password_hash);

  if ($user_id === false) {
    // 登録失敗
    echo '登録に失敗しました。もう一度お試しください。';
    exit();
  }

  // 登録成功時はCSRFトークンを破棄
  destroyCsrfToken();

  $_SESSION['msg'] = "会員登録が完了しました。ログインしてください。";
  header('Location: ./login.php');
  exit();
} catch (PDOException $e) {
  echo '接続失敗' . $e->getMessage();
  exit();
}
