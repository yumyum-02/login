<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light min-vh-100 d-flex align-items-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">ログイン</h2>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger" role="alert">
                <ul class="mb-0 ps-3">
                  <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <?php if (isset($success_logout_msg)): ?>
              <div class="alert alert-success" role="alert"><?= htmlspecialchars($success_logout_msg, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <?php if (isset($err_msg)): ?>
              <div class="alert alert-danger" role="alert"><?= htmlspecialchars($err_msg, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <?php if (isset($login_msg) && $login_msg !== ''): ?>
              <div class="alert alert-info" role="alert"><?= htmlspecialchars($login_msg, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form action="" method="post">
              <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" class="form-control" id="email" name="email" required maxlength="255" placeholder="example@email.com">
              </div>
              <div class="mb-4">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="パスワードを入力">
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg" name="login_btn">ログイン</button>
              </div>

              <?php
              if (!isset($_SESSION['login_token'])) {
                $token = bin2hex(random_bytes(32));
                $_SESSION['login_token'] = $token;
              }
              ?>
              <input type="hidden" name="login_token" value="<?= htmlspecialchars($_SESSION['login_token']) ?>">
            </form>

            <hr class="my-4">
            <p class="text-center mb-0">
              <a href="./regist.php" class="text-decoration-none">会員登録はこちら →</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>