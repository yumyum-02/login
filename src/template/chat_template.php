<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>掲示板</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>

<style>
  .card{
    position: relative;
    height: 70vh;
  }
  .card ul{
    overflow-y: scroll;
    height: 50vh;
  }
  .card ul li:not(:last-of-type){
    margin-bottom: 20px;
    border-bottom: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
  }
</style>

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
              <i class="bi bi-chat-text-fill me-2 text-primary"></i>掲示板
            </h1>
            <p class="text-muted mb-0">このページでは、ログインしているユーザーが投稿した内容を見ることができます。</p>
          </div>

          <section class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 text-end">
              <a class="btn btn-primary" href="../admin/chat-post.php">投稿</a>
            </div>
            <ul class="card-body p-4 mb-0">
              <?php $current_user_id = AuthUser::getUserId(); ?>
              <?php foreach ($posts as $post): ?>
              <li class="row align-items-center pb-3">
                <div class="d-flex mb-2">
                  <p class="mb-0 flex-grow-1 text-secondary"><?= escape($post['author_name']) ?></p>
                  <?php if ($current_user_id !== null && (int)$post['created_by'] === $current_user_id): ?>
                  <div class="col-sm-3 text-end">
                    <form action="../chat/exec_chat-delete.php" method="post" class="d-inline"
                      onsubmit="return confirm('本当に削除しますか？');">
                      <input type="hidden" name="csrf_token" value="<?= escape($csrf_token) ?>">
                      <input type="hidden" name="post_id" value="<?= (int)$post['id'] ?>">
                      <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i> 削除
                      </button>
                    </form>
                  </div>
                  <?php endif; ?>
                </div>
                <div>
                  <p class="mb-0"><?= escape($post['message']) ?></p>
                </div>
              </li>
              <?php endforeach; ?>
          </ul>
        </section>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>