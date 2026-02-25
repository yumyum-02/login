<?php
require_once __DIR__ . '/../../src/bootstrap.php';

// POST送信でない場合はログイン画面へ
if (!isset($_POST['login_btn'])) {
  header('Location: ../login.php');
  exit();
}

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}

// バリデーション
// emailの前後の空白を削除
$email = getTrimmedPostValue('email');
// パスワードか空白
$password = $_POST['password'] ?? '';
// バリデーションのためのエラー配列を用意
$errors = [
  'email' => [],
  'password' => []
];
// メールアドレスのバリデーション
if (isEmpty($email)) {
  $errors['email'][] = 'メールアドレスを入力してください。';
} else {
  if (!isEmailFormat($email)) {
    $errors['email'][] = 'メールアドレスの形式が正しくありません。';
  }
  if (!isWithinLength($email, null, 320)) {
    $errors['email'][] = 'メールアドレスは320文字以内で入力してください。';
  }
}
// パスワードのバリデーション
if (isEmpty($password)) {
  $errors['password'][] = 'パスワードを入力してください。';
} else {
  if (!isPasswordFormat($password)) {
    $errors['password'][] = 'パスワードの形式が正しくありません。';
  }
  if (!isWithinLength($password, 8, 100)) {
    $errors['password'][] = 'パスワードは8文字以上100文字以内で入力してください。';
  }
}
// エラーチェック
$hasErrors = !empty($errors['email']) || !empty($errors['password']);
// エラーがあればログイン画面に戻る
if ($hasErrors) {
  $_SESSION['login_errors'] = $errors;
  $_SESSION['login_email'] = $email;
  header('Location: ../login.php');
  exit();
}

// DBのデータと照会
try {
  // ユーザー情報の取得 db.phpの関数を使用
  $user_info = getUserLogin($email);

  // 取得した情報と入力されたパスワードを照合
  // password_verify = ハッシュ化されたパスワードの照合
  // $user_info に要素が1件以上あるかパスワードが一致している時
  if (count($user_info) && password_verify($password, $user_info[0]['password'])) {
    $_SESSION['user'] = array(
      'id'    => $user_info[0]['id'],
      'name'  => $user_info[0]['name'],
      'email' => $user_info[0]['email'],
    ); // セッションにユーザー情報を保存

    // ログイン成功時はCSRFトークンを破棄
    destroyCsrfToken();

    // ログイン成功後、メイン画面へリダイレクト
    header('Location: ../index.php');
    exit();
  } else {
    $_SESSION['login_error_msg'] = 'ログイン情報に誤りがあります。';
    header('Location: ../login.php');
    exit();
  }
} catch (PDOException $e) {
  echo '接続失敗' . $e->getMessage();
  exit();
}
