<?php

/**
 * セッションスタート
 */
ini_set('session.gc_maxlifetime', 1800);
ini_set('session.gc_divisor', 1);
session_start();
session_regenerate_id(); // セッションIDを新しいものに置き換える（★セッションハイジャック）

/**
 * ログインしていなければログイン画面へ強制リダイレクト
 */
if (! isset($_SESSION['user'])) {
  header('Location: ./login.php');
  exit();
}

/**
 * ログアウト
 */
if (isset($_POST['logout'])) {

  // トークンチェック（★CSRF）
  if (empty($_SESSION['logout_token']) || ($_SESSION['logout_token'] !== $_POST['logout_token'])) exit('不正な投稿です');
  if (isset($_SESSION['logout_token'])) unset($_SESSION['logout_token']); //トークン破棄
  if (isset($_POST['logout_token'])) unset($_POST['logout_token']);//トークン破棄

  /**
   * セッションを破棄する（★セッションハイジャック）
   */
  // セッション変数の中身をすべて破棄
  $_SESSION = array();
  // クッキーに保存されているセッションIDを破棄
  if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/');
  // セッションを破棄
  session_destroy();

  // ログインページに戻る
  $msg = urlencode("ログアウトしました。");
  header('Location: ./login.php?msg=' . $msg);
  exit();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン後の画面</title>
</head>

<body>
  <?php
  // ログイン中のユーザー情報を表示（★クロスサイトスクリプティング）
  echo 'ID：' . htmlspecialchars($_SESSION['user']['login_id'], ENT_QUOTES, 'UTF-8') . '<br>';
  echo 'ユーザー名：' . htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8');
  ?>

  <form action="#" method="post">
    <input type="submit" name="logout" value="ログアウト">

    <?php
    // 不正リクエストチェック用のトークン生成（★CSRF）
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['logout_token'] = $token;
    echo '<input type="hidden" name="logout_token" value="' . $token . '" />';
    ?>
  </form>
</body>

</html>