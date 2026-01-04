<?php
if (isset($_POST['logout'])) { //POSTのキー＝name 属性がlogoutのとき

  //　トークンの正当性チェック
  // logout_tokenがセッションに保存されていない、またはPOSTで送信されたlogout_tokenとセッション内のlogout_tokenが一致しない場合、不正な投稿として処理を中断
  if (empty($_SESSION['logout_token']) || ($_SESSION['logout_token'] !== $_POST['logout_token'])) exit('不正な投稿です');

  // トークンの破棄
  if (isset($_SESSION['logout_token'])) unset($_SESSION['logout_token']);
  if (isset($_POST['logout_token'])) unset($_POST['logout_token']);

  // 現在のセッションに保存されているすべてのキーと値を削除
  $_SESSION = [];

  // セッションIDをクッキーから削除することで、セッションの乗っ取りを防止
  if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/'); // PHPSESSID=PHPが自動で作るセッションID用のクッキー　有効期限を過去にすることでクッキーを削除（空）できるテクニック

  // セッションを破壊
  session_destroy();
  // destroyまでしなくてもunset($_SESSION['user']);とクッキー削除でも十分
  //? 普通はdestroyまでしない？

  $msg = urlencode("ログアウトしました。"); // destroyするのでURLにメッセージをつけるしかない
  header('Location: ./login.php?msg=' . $msg);
  exit();
}
