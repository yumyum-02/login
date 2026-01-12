<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン後の画面</title>
</head>

<body>
  <!-- ログインIDとユーザー名の表示 -->
  <?php
  echo 'ID：' . escape($_SESSION['user']['login_id']) . '<br>'; // escape=クロスサイトスクリプティング対策用に文字列で認識するように指定
  echo 'ユーザー名：' . escape($_SESSION['user']['name']);
  ?>
  <form action="#" method="post">
    <!-- ログアウトボタン logout.php -->
    <input type="submit" name="logout" value="ログアウト">
    <!-- 外部から勝手にログアウトではなくこの画面からログアウトしたことを照会している -->
    <?php
    if (!isset($_SESSION['logout_token'])) {
      $_SESSION['logout_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <input type="hidden" name="logout_token" value="<?= htmlspecialchars($_SESSION['logout_token']) ?>">

  </form>
</body>

</html>