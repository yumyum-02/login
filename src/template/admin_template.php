<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー一覧</title>
</head>

<body>
  <?php
  foreach ($users_info as $user) {
    echo 'ログインID: ' . escape($user['login_id']) . '<br>';
    echo '名前: ' . escape($user['name']) . '<br><br>';
  }
  ?>
  <form action="#" method="post">
    <input type="submit" name="logout" value="ログアウト">

    <?php
    echo '<input type="hidden" name="logout_token" value="' . $token . '" />';
    ?>
  </form>
</body>

</html>