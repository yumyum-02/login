<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アカウント情報</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    .navbar-brand { font-weight: 600; letter-spacing: -0.5px; }
    .content-card { border-left: 4px solid #0d6efd; }
  </style>
</head>

<body class="bg-light min-vh-100">
  <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="./dashboard.php">
        <i class="bi bi-house-door-fill me-2"></i>Dashboard
      </a>
      <div class="d-flex align-items-center">
        <div class="dropdown">
          <button class="btn btn-link text-white text-decoration-none dropdown-toggle d-flex align-items-center py-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            <span><?= escape($_SESSION['user']['name']) ?></span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li class="px-3 py-2 border-bottom">
              <div class="small text-muted">ログイン中</div>
              <div class="fw-semibold"><?= escape($_SESSION['user']['name']) ?></div>
              <div class="small"><?= escape($_SESSION['user']['email']) ?></div>
            </li>
            <li>
              <form action="../logout.php" method="post" class="p-2">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <button type="submit" class="btn btn-outline-danger btn-sm w-100" name="logout">
                  <i class="bi bi-box-arrow-right me-1"></i>ログアウト
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="card-title">アカウント情報</h2>
      </div>
      <dl class="row mb-0">
        <dt class="col-sm-4 text-muted">ユーザー名</dt>
        <dd class="col-sm-8"><?= escape($_SESSION['user']['name']) ?></dd>
        <dt class="col-sm-4 text-muted">メールアドレス</dt>
        <dd class="col-sm-8"><?= escape($_SESSION['user']['email']) ?></dd>
        <dt class="col-sm-4 text-muted">パスワード</dt>
        <dd class="col-sm-8">********</dd>
      </dl>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>