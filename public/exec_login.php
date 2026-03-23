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

// バリデーションチェック（統一メッセージを返す）
$email_errors = getSimpleEmailErrors($email);
$password_errors = getSimplePasswordErrors($password);

// エラーがあればログイン処理を中止してフォームに戻る
// セキュリティ: バリデーション関数が統一メッセージを返す（ユーザー列挙攻撃対策）
if (!empty($email_errors) || !empty($password_errors)) {
  // どちらかにエラーがあれば、最初のエラーメッセージを表示
  $_SESSION['error_message'] = $email_errors[0] ?? $password_errors[0];
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
    // AuthUser クラスを使用してログイン処理
    AuthUser::login([
      'id'    => $user_info[0]['id'],
      'name'  => $user_info[0]['name'],
      'email' => $user_info[0]['email'],
    ]);

    // ログイン成功後、メイン画面へリダイレクト
    header('Location: ./admin/dashboard.php');
    exit();
  } else {
    // 認証失敗時も同じメッセージ（セキュリティ対策）
    $_SESSION['error_message'] = 'ログイン情報が正しくありません。';
    redirect('./login.php');
  }
} catch (PDOException $e) {
  $_SESSION['error_message'] = 'データベースエラーが発生しました';
  redirect('./login.php');
}
$pdo = null;
$stmt = null;