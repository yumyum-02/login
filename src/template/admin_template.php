<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー一覧</title>
</head>

<body>
  <?php
  foreach ($user_info as $user) {
    echo 'ログインID: ' . htmlspecialchars($user['login_id'], ENT_QUOTES, 'UTF-8') . '<br>';
    echo '名前: ' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '<br><br>';
  }
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