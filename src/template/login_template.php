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

            <?php if (isset($success_logout_msg)): ?>
              <div class="alert alert-success" role="alert"><?= escape($success_logout_msg) ?></div>
            <?php endif; ?>
            <?php if (isset($err_msg) && $err_msg !== ''): ?>
              <div class="alert alert-danger" role="alert"><?= escape($err_msg) ?></div>
            <?php endif; ?>
            <?php if (isset($login_msg) && $login_msg !== ''): ?>
              <div class="alert alert-info" role="alert"><?= escape($login_msg) ?></div>
            <?php endif; ?>

            <form action="./handlers/login_handler.php" method="post">
              <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email"
                       class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                       id="email"
                       name="email"
                       placeholder=""
                       value="<?= isset($email) ? escape($email) : '' ?>">

                <?php if (!empty($errors['email'])): ?>
                  <div class="invalid-feedback d-block">
                    <?php foreach ($errors['email'] as $error): ?>
                      <div><?= escape($error) ?></div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="mb-4">
                <label for="password" class="form-label">パスワード</label>
                <input type="password"
                       class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>"
                       id="password"
                       name="password"
                       placeholder="">

                <?php if (!empty($errors['password'])): ?>
                  <div class="invalid-feedback d-block">
                    <?php foreach ($errors['password'] as $error): ?>
                      <div><?= escape($error) ?></div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg" name="login_btn">ログイン</button>
              </div>

              <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
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