<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>掲示板｜投稿</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>

<body class="bg-light min-vh-100">
  <?php
  // ナビゲーションバー
  require_once __DIR__ . '/components/navbar.php';

  // サイドバー
  $active_page = 'chat';
  $is_admin = false;
  require_once __DIR__ . '/components/sidebar.php';
  ?>

  <main class="p-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <!-- ページヘッダー -->
          <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">
              <i class="bi bi-chat-text-fill me-2 text-primary"></i>掲示板｜投稿
            </h1>
            <p class="text-muted mb-0">このページでは、ログインしているユーザーが投稿できます</p>
          </div>

          <section class="card shadow-sm border-0">
            <div class="card-body p-4">
              <p class="mb-0 flex-grow-1 text-secondary"><?= escape(AuthUser::getName()) ?></p>
              <form action="../chat/exec_chat-post.php" method="post">
                <input type="text"
                  class="form-control <?= !empty($errors) ? 'is-invalid' : '' ?>"
                  name="chatpost"
                  value="<?= escape($old_input['chatpost'] ?? '') ?>">
                <?php if (!empty($errors)): ?>
                <div class="invalid-feedback d-block">
                  <?php foreach ($errors as $error): ?>
                    <div><?= escape($error) ?></div>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <button type="submit" class="btn btn-primary" name="chat_post_submit">
                  <i class="bi bi-check-lg me-2"></i>投稿
                </button>
                <a href="../admin/chat.php" class="btn btn-outline-secondary">
                  <i class="bi bi-x-lg me-2"></i>キャンセル
                </a>
              </form>
            </div>
        </section>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>