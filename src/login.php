<?php

session_start();
session_regenerate_id();

const DB_HOST = 'mysql:dbname=login_db;host=mysql;charset=utf8';
const DB_USER = 'root';
const DB_PASSWORD = 'secret';

if (isset($_GET['msg'])) $success_logout_msg = $_GET['msg']; //? GETとPOSTとSESSIONはどう使い分けている？（GETはメッセージなどの見られてもいいもの、POSTはIDなどの見られては困るもの。SESSIONはサーバーに保存されたデータという認識）

if (
  isset($_POST['login_btn']) &&
  (isset($_POST['login_id']) && $_POST['login_id'] != '') &&
  (isset($_POST['password']) && $_POST['password'] != '')
) {
  if (empty($_SESSION['login_token']) || ($_SESSION['login_token'] !== $_POST['login_token'])) exit('不正なリクエストです');
  if (isset($_SESSION['login_token'])) unset($_SESSION['login_token']);
  if (isset($_POST['login_token'])) unset($_POST['login_token']);

  $login_id = $_POST['login_id'];
  $password = $_POST['password'];

  try {
    //? PDOはDBに接続しているだけ？PDOについて網羅的に知れるサイトは？（そもそも実務でよく使うもの？）
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => false, //? 何をしている？調べてもよくわからなかった
    ]);
    $sql = ('
    SELECT login_id, password, name
    FROM users
    WHERE login_id = :LOGIN_ID
    '); //ログインIDに一致するレコードを取得
    $stmt = $pdo->prepare($sql); //? pdoでsqlに接続している？アロー演算子がよくわかっていない
    $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR); //ログインIDをセットしている
    $stmt->execute();
    //? 35行目〜37行目までの記述がよくわからない。PDOでデータベースに接続し、userテーブルからログインIDに一致するレコードを取得していることまではわかる。

    $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC); //? PDO::FETCH_ASSOCが具体的に何をしているのかよくわからない（fetchのオプションであることはわかる）

    if (count($user_info) && password_verify($password, $user_info[0]['password'])) { //?　なぜcountしている？なぜ　[0]で最初のデータを指定している？SQLからfetchAllで配列を全て取得しているのになぜ最初のデータで照らし合わせている？
      $_SESSION['user'] = array(
        'name'     => $user_info[0]['name'],
        'login_id' => $user_info[0]['login_id'],
      );
      header('Location: ./index.php');
      exit();
    } else {
      $err_msg = 'ログイン情報に誤りがあります。';
    }
  } catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage(); //? よくある書き方？
    exit();
  }
  $pdo = null;
  $stmt = null;
  //? nullで破棄しているのはなぜ？安全性？
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>
  <div>
    <h2>ログイン画面</h2>
    <?php if (isset($success_logout_msg)) echo '<p class="success_logout_msg">' . $sucsess_logout_msg . '</p>'; ?>

    <?php if (isset($err_msg)) echo '<p class="err-msg">' . $err_msg . '</p>'; ?>

    <form action="" method="post">
      <p><label for="login_id">ID</label><input type="text" name="login_id"></p>
      <p><label for="password">パスワード</label><input type="password" name="password"></p>
      <input type="submit" value="ログイン" name="login_btn">

      <?php
      $token = bin2hex(random_bytes(32));
      $_SESSION['login_token'] = $token;
      echo '<input type="hidden" name="login_token" value="' . $token . '" />';
      ?>
    </form>

    <a href="./regist.php">会員登録はこちら</a>
  </div>
</body>

</html>