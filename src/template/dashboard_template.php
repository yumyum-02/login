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
  <?php
  // ナビゲーションバー
  require_once __DIR__ . '/components/navbar.php';

  // サイドバー
  $active_page = 'dashboard';
  $is_admin = false;
  require_once __DIR__ . '/components/sidebar.php';
  ?>

  <main class="p-4">
    <div class="mb-4">
      <h1 class="h3 fw-bold mb-1">ようこそ、<?= escape(AuthUser::getName()) ?>さん</h1>
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