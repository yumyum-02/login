<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン後の画面</title>
</head>

<body>
  <?php
  echo 'ID：' . htmlspecialchars($_SESSION['user']['login_id'], ENT_QUOTES, 'UTF-8') . '<br>';
  echo 'ユーザー名：' . htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8');
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