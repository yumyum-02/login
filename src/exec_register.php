<?php
require './bootstrap.php';

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

  $name = getTrimmedPostValue('name');
  $email = getTrimmedPostValue('email');
  $password = $_POST['password'] ?? '';

  // バリデーション
  // バリデーションのためのエラー配列を用意
  $errors = [];
  // ユーザー名のバリデーション
  if (!isSafeInput($name)) {
    $errors[] = '使用できない文字が含まれています。';
  }
  if (!isWithinLength($name, 3, 16)) {
    $errors[] = 'ユーザー名は3文字以上16文字以内で入力してください。';
  }
  // メールアドレスのバリデーション（形式と長さを分けてエラーを特定しやすくする）
  if (!isEmailFormat($email)) {
    $errors[] = 'メールアドレスの形式が正しくありません。';
  }
  // メールアドレスはローカルパートが最大64文字 + ドメインが255文字 + ＠で 合計320文字 MAX
  if (!isWithinLength($email, null , 320)) {
    $errors[] = 'メールアドレスは320文字以内で入力してください。';
  }
  // パスワードのバリデーション
  if (!isPasswordFormat($password)) {
    $errors[] = 'パスワードは半角英数字と記号で入力してください。';
  }
  if (!isWithinLength($password, 16 , 64)) {
    $errors[] = 'パスワードは16文字以上64文字以内で入力してください。';
  }
  // エラーがあれば登録処理を中止してフォームに戻る
  if (!empty($errors)) {
    require './template/regist_template.php';
    exit();
  }

  $email = mb_strtolower($email, 'UTF-8');

  // パスワードのハッシュ化
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  try {
    // すでに登録されているIDかどうか確認 db.phpの関数を使用
    $user_info = getUserRegister($email);

    // すでに登録されているメールアドレスの場合はエラーに追加
    if (count($user_info)) {
      $errors[] = 'そのメールアドレスはすでに使用されています。';
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
} else {
  header('Location: ./regist.php');
  exit();
}
