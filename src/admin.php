<?php
require './db.php';

session_start();
session_regenerate_id();

if (!isset($_SESSION['user'])) {
  header('Location: ./login.php');
  exit();
}

if (isset($_POST['logout'])) {

  if (empty($_SESSION['logout_token']) || ($_SESSION['logout_token'] !== $_POST['logout_token'])) exit('不正な投稿です');
  if (isset($_SESSION['logout_token'])) unset($_SESSION['logout_token']);
  if (isset($_POST['logout_token'])) unset($_POST['logout_token']);

  $_SESSION = array();
  if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/');
  session_destroy();

  $msg = urlencode("ログアウトしました。");
  header('Location: ./login.php?msg=' . $msg);
  exit();
}

$pdo = connectDb();
$sql = ('
    SELECT login_id, name
    FROM users
    ');
$stmt = $pdo->prepare($sql);
$stmt->execute();

$user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($user_info as $user) {
  echo 'ログインID: ' . htmlspecialchars($user['login_id'], ENT_QUOTES, 'UTF-8') . '<br>';
  echo '名前: ' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '<br><br>';
}

$pdo = null;
$stmt = null;

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー一覧</title>
</head>

<body>
  <form action="#" method="post">
    <input type="submit" name="logout" value="ログアウト">

    <?php
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['logout_token'] = $token;
    echo '<input type="hidden" name="logout_token" value="' . $token . '" />';
    ?>
  </form>
</body>

</html>