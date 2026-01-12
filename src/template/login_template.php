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
    <?php if (isset($success_logout_msg)) echo '<p class="success_logout_msg">' . $success_logout_msg . '</p>'; ?>
    <?php if (isset($err_msg)) echo '<p class="err-msg">' . $err_msg . '</p>'; ?>
    <?php if ($login_msg !== ''): ?><p class="login-msg"><?= htmlspecialchars($login_msg) ?></p><?php endif; ?>
    <form action="" method="post">
      <p><label for="login_id">ID</label><input type="text" name="login_id"></p>
      <p><label for="password">パスワード</label><input type="password" name="password"></p>
      <input type="submit" value="ログイン" name="login_btn">

      <?php
      if (!isset($_SESSION['login_token'])) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['login_token'] = $token;
      }
      ?>
      <input type="hidden" name="login_token" value="<?= htmlspecialchars($_SESSION['login_token']) ?>">

    </form>

    <a href="./regist.php">会員登録はこちら</a>
  </div>
</body>

</html>