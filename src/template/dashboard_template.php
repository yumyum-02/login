<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ダッシュボード</title>
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
        <span class="text-white me-3 d-none d-md-inline d-flex align-items-center">
          <?= displayIcon($_SESSION['user']['id'], 24, 'rounded-circle me-2', 'ユーザーアイコン') ?>
          <?= escape($_SESSION['user']['name']) ?>
        </span>
      </div>
    </div>
  </nav>

  <div class="offcanvas offcanvas-start bg-white shadow-sm" tabindex="-1" id="sidebarMenu">
    <div class="offcanvas-header border-bottom d-md-none">
      <h5 class="offcanvas-title d-flex align-items-center">
        <?= displayIcon($_SESSION['user']['id'], 24, 'rounded-circle me-2', 'ユーザーアイコン') ?>
        <?= escape($_SESSION['user']['name']) ?>
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="./dashboard.php">
            <i class="bi bi-house-door-fill"></i>
            ダッシュボード
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./account.php">
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
    <div class="mb-4">
      <h1 class="h3 fw-bold mb-1">ようこそ、<?= escape($_SESSION['user']['name']) ?>さん</h1>
      <p class="text-muted mb-0">ログインに成功しました</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">
              <i class="bi bi-lightning-charge text-warning me-2"></i>クイックアクセス
            </h5>
            <hr>
            <p class="text-muted small mb-0">
              このアプリケーションのメイン画面です。<br>
              今後、機能が追加されていく予定です。
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>