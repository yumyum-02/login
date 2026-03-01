<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アカウント情報</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>

<body class="bg-light min-vh-100">
  <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <div class="container-fluid">
      <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="./dashboard.php">
        <i class="bi bi-house-door-fill me-2"></i>Dashboard
      </a>
      <div class="d-flex align-items-center">
        <span class="text-white me-3 d-none d-md-inline">
          <i class="bi bi-person-circle me-2"></i><?= escape($_SESSION['user']['name']) ?>
        </span>
      </div>
    </div>
  </nav>

  <div class="offcanvas offcanvas-start bg-white shadow-sm" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header border-bottom d-md-none">
      <h5 class="offcanvas-title">
        <i class="bi bi-person-circle me-2"></i><?= escape($_SESSION['user']['name']) ?>
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link" href="./dashboard.php">
            <i class="bi bi-house-door-fill"></i>
            ダッシュボード
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="./account.php">
            <i class="bi bi-person-fill"></i>
            アカウント情報
          </a>
        </li>
        <li class="nav-item mt-3">
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

  <main class="p-4">
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