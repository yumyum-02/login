<?php

session_start(); //? 実務でセッションの有効期限（時間や画面を離れたらセッション切れにするとか）は設定する？
session_regenerate_id();

if (!isset($_SESSION['user'])) { //? !issetとemptyはどう使い分けている？
  header('Location: ./login.php');
  exit();
}

if (isset($_POST['logout'])) {

  //? sessionに保存されているlogout_tokenとPOSTで送信されたlogout_tokenが同じでなければ不正な投稿とみなすのはわかるが、unsetで破棄しているのはなぜ？こういう処理はよくするもの？
  if (empty($_SESSION['logout_token']) || ($_SESSION['logout_token'] !== $_POST['logout_token'])) exit('不正な投稿です');
  if (isset($_SESSION['logout_token'])) unset($_SESSION['logout_token']);
  if (isset($_POST['logout_token'])) unset($_POST['logout_token']);

  $_SESSION = array();
  //?　以下のクッキーの破棄もよくすること？セッションの破棄とセット？
  if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/'); //? なぜ時間指定している？
  session_destroy();

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
  echo 'ID：' . htmlspecialchars($_SESSION['user']['login_id'], ENT_QUOTES, 'UTF-8') . '<br>'; //? $_SESSION['login_id']ではダメ？
  //? ENT_QUOTES, 'UTF-8'を書いているのはなぜ？
  echo 'ユーザー名：' . htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8');
  ?>
  <form action="#" method="post">
    <input type="submit" name="logout" value="ログアウト">

    <?php
    $token = sha1(uniqid(mt_rand(), true)); //? よく使う構文？
    $_SESSION['logout_token'] = $token;
    echo '<input type="hidden" name="logout_token" value="' . $token . '" />'; //?　hiddenで見えないようにしているがlogout_tokenもPOSTされている？これ自体はsubmitで送信されていないのにどうPOSTしている？
    ?>
  </form>
</body>

</html>