<?php
require '../src/bootstrap.php';

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}
// CSRFトークンを破棄
destroyCsrfToken();

// バリデーション
// 空白の削除
$email = getTrimmedPostValue('email');
$password = $_POST['password'] ?? '';

// バリデーションチェック
$errors = [
  'email' => getSimpleEmailErrors($email),
  'password' => getSimplePasswordErrors($password)
];

$hasErrors = !empty($errors['email']) || !empty($errors['password']);

// エラーがあればログイン処理を中止してフォームに戻る
if ($hasErrors) {
  $_SESSION['errors'] = $errors;
  redirect('./login.php');
}

//メールアドレスを小文字に変換
//メールアドレスは大文字小文字を区別しないためtest@example.comとTest@Example.comは同じメアドだが、別のメアドとして重複判定から漏れる
$email = mb_strtolower($email, 'UTF-8');

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

    // ログイン成功後、メイン画面へリダイレクト
    header('Location: ./admin/dashboard.php');
    exit();
  } else {
    $_SESSION['error_message'] = 'メールアドレスまたはパスワードが正しくありません。';
    redirect('./login.php');
  }
} catch (PDOException $e) {
  $_SESSION['error_message'] = 'データベースエラーが発生しました';
  redirect('./login.php');
}
$pdo = null;
$stmt = null;