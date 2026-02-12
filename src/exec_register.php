<?php
require './bootstrap.php';
require_once __DIR__ . '/validation.php'; //バリデーションの呼び出し

if (
  // 各フォームが入力されている場合
  (isset($_POST['name']) && $_POST['name'] != '') &&
  (isset($_POST['email']) && $_POST['email'] != '') &&
  (isset($_POST['password']) && $_POST['password'] != '')
) {
  // 不正リクエストチェック トークンの照合
  // トークンがセッションに存在しない、または一致しない場合は処理を中止
  if (empty($_SESSION['regist_token']) || ($_SESSION['regist_token'] !== $_POST['regist_token'])) exit('不正なリクエストです');
  // トークンの破棄（1回限り有効にするため）
  if (isset($_SESSION['regist_token'])) unset($_SESSION['regist_token']);
  if (isset($_POST['regist_token'])) unset($_POST['regist_token']);

  $name = $_POST['name'];
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'];
  // パスワードのハッシュ化
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  // バリデーションのためのエラー配列を用意
  $errors = [];

  try {
    // すでに登録されているIDかどうか確認 db.phpの関数を使用
    $user_info = getUserRegister($email);

    // メールアドレスのバリデーション
    if (!validationLoginMail($email)) {
      $errors[] = 'メールアドレスの形式が正しくありません。';
    }
    // エラーがあれば登録処理を中止してフォームに戻る
    if (!empty($errors)) {
      require './template/regist_template.php';
      exit();
    }

    // すでに登録されているメールアドレスの場合はエラーメッセージを表示
    if (count($user_info)) {
      $err_msg = 'そのメールアドレスはすでに使用されています。';
      require './template/regist_template.php';
      exit();
    } else {
      // 登録されていないメールアドレスの場合は、usersテーブルに新規登録
      // PDOでデータベースに接続
      $pdo = connectDb();
      // INSERT文を実行してユーザー情報を登録
      $sql = ('
      INSERT INTO users (name, email, password)
      VALUES (:NAME, :EMAIL, :PASSWORD);
      ');
      $stmt = $pdo->prepare($sql); // SQL文をデータベースに送る準備
      $stmt->bindValue(':NAME', $name, PDO::PARAM_STR); // NAMEにnameを入れる(PDO::PARAM_STR=文字列として扱う)
      $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR);
      $stmt->bindValue(':PASSWORD', $password_hash, PDO::PARAM_STR);
      $stmt->execute();
      $_SESSION['msg'] = "会員登録が完了しました。ログインしてください。";
      header('Location: ./login.php');
      exit();
    }
  } catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage();
    exit();
  }

  $pdo = null;
  $stmt = null;
}
