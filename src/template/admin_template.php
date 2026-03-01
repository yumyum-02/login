<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー一覧</title>
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
          <a class="nav-link" href="admin.php">
            <i class="bi bi-person-fill"></i>
            ユーザー一覧
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
    <?php if (isset($_GET['msg'])): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= escape($_GET['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
      </div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-start mb-4">
      <div>
        <h1 class="h3 fw-bold mb-1">ユーザー一覧</h1>
        <p class="text-muted mb-0">登録されているユーザー一覧です</p>
      </div>
    </div>

    <div class="card shadow-sm content-card">
      <div class="card-body">
        <h5 class="card-title mb-4">
          <i class="bi bi-people text-primary me-2"></i>ユーザー一覧
        </h5>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>メールアドレス</th>
                <th>ユーザー名</th>
                <th>ユーザーID</th>
                <th class="text-end" style="width: 100px;">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users_info as $user): ?>
                <tr>
                  <td><?= escape($user['email']) ?></td>
                  <td><?= escape($user['name']) ?></td>
                  <td><?= escape($user['id']) ?></td>
                  <td class="text-end">
                    <a href="exec_delete_user.php?id=<?= $user['id'] ?>&token=<?= $csrf_token ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('<?= escape($user['name']) ?>を削除しますか？')"><i class="bi bi-trash"></i> 削除</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>