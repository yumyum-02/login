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
  <?php
  // ナビゲーションバー
  require_once __DIR__ . '/components/navbar.php';

  // サイドバー
  $active_page = 'admin';
  $is_admin = true;
  require_once __DIR__ . '/components/sidebar.php';
  ?>

  <main class="p-4">
    <?php foreach ($messages as $message): ?>
      <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
        <?= escape($message['text']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="閉じる"></button>
      </div>
    <?php endforeach; ?>
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
                    <form action="./exec_delete_user.php" method="post" style="display:inline;" onsubmit="return confirm('<?= escape($user['name']) ?>を削除しますか？')">
                      <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                      <input type="hidden" name="id" value="<?= $user['id'] ?>">
                      <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i> 削除
                      </button>
                    </form>
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