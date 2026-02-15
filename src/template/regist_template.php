<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>会員登録画面</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light min-vh-100 d-flex align-items-center py-4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">会員登録</h2>

            <?php if (isset($_GET['msg'])): ?>
              <div class="alert alert-success" role="alert"><?= htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <!-- 登録エラーメッセージ（形式エラー・重複エラーを $errors で一元表示） -->
            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger" role="alert">
                <ul class="mb-0 ps-3">
                  <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <form action="./exec_register.php" method="post">
              <div class="mb-3">
                <label for="name" class="form-label">ユーザー名</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="半角英数字で入力" value="<?= isset($name) ? htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : '' ?>">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" value="<?= isset($email) ? htmlspecialchars($email, ENT_QUOTES, 'UTF-8') : '' ?>">
              </div>
              <div class="mb-4">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="半角英数字と記号を含む8文字以上">
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg" name="regist_btn">登録する</button>
              </div>

              <?php
              // 不正リクエストチェック用のトークン生成
              $token = bin2hex(random_bytes(32));
              $_SESSION['regist_token'] = $token;
              echo '<input type="hidden" name="regist_token" value="' . $token . '" />';
              ?>
            </form>

            <hr class="my-4">
            <p class="text-center mb-0">
              <a href="./login.php" class="text-decoration-none">← ログイン画面へ戻る</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>