<?php
require './bootstrap.php';

if (
  // 会員登録ボタンが押され、かつ各フォームが入力されている場合
  isset($_POST['regist_btn']) &&
  (isset($_POST['name']) && $_POST['name'] != '') &&
  (isset($_POST['login_id']) && $_POST['login_id'] != '') &&
  (isset($_POST['password']) && $_POST['password'] != '')
) {
  // 不正リクエストチェック
  // トークンがセッションに存在しない、または一致しない場合は処理を中止
  if (empty($_SESSION['regist_token']) || ($_SESSION['regist_token'] !== $_POST['regist_token'])) exit('不正なリクエストです');
  // トークンの破棄（1回限り有効にするため）
  if (isset($_SESSION['regist_token'])) unset($_SESSION['regist_token']);
  if (isset($_POST['regist_token'])) unset($_POST['regist_token']);

  $name = $_POST['name'];
  $login_id = $_POST['login_id'];
  $password = $_POST['password'];
  // パスワードのハッシュ化
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  try {
    // すでに登録されているIDかどうか確認 db.phpの関数を使用
    $user_info = getUserRegister($login_id);

    // すでに登録されているIDの場合はエラーメッセージを表示
    if (count($user_info)) {
      $err_msg = 'そのIDはすでに使用されています。';
    } else {
      // 登録されていないIDの場合は、usersテーブルに新規登録
      // PDOでデータベースに接続
      $pdo = connectDb();
      // INSERT文を実行してユーザー情報を登録
      $sql = ('
      INSERT INTO users (name, login_id, password)
      VALUES (:NAME, :LOGIN_ID, :PASSWORD);
      ');
      $stmt = $pdo->prepare($sql); // SQL文をデータベースに送る準備
      $stmt->bindValue(':NAME', $name, PDO::PARAM_STR); // NAMEにnameを入れる(PDO::PARAM_STR=文字列として扱う)
      $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR);
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
