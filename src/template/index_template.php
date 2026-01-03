<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン後の画面</title>
</head>

<body>
  <?php
  echo 'ID：' . escape($_SESSION['user']['login_id']) . '<br>';
  echo 'ユーザー名：' . escape($_SESSION['user']['name']);
  ?>
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