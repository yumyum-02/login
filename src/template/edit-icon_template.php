<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アイコン変更</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
</head>

<body class="bg-light min-vh-100">
  <?php
  // ナビゲーションバー
  $base_path = '../admin/';
  require_once __DIR__ . '/components/navbar.php';

  // サイドバー
  $active_page = 'account';
  $is_admin = false;
  $logout_path = '../logout.php';
  require_once __DIR__ . '/components/sidebar.php';
  ?>

  <main class="p-4">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <!-- ページヘッダー -->
          <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">
              <i class="bi bi-image me-2 text-primary"></i>アイコン変更
            </h1>
            <p class="text-muted mb-0">プロフィールアイコンを変更できます</p>
          </div>

          <!-- アイコン変更カード -->
          <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
              <h5 class="card-title mb-0">
                <i class="bi bi-image-fill me-2"></i>アイコンプレビュー
              </h5>
            </div>
            <div class="card-body p-4">
              <!-- アイコンプレビュー -->
              <div class="icon-preview-container" id="iconPreviewContainer">
                <?php
                // セッションにプレビュー用ファイル名（*_temp.*）があればそれを表示
                if (!empty($_SESSION['temp_icon'])) {
                  $previewPath = '/image/icon/' . $_SESSION['temp_icon'];
                } else {
                  // なければ現在の画像を表示
                  require_once dirname(__DIR__) . '/functions/display_icon.php';
                  $previewPath = getIconPath($_SESSION['user']['id']);
                }
                ?>
                <img src="<?= escape($previewPath) ?>" alt="アイコンプレビュー" class="icon-preview" id="iconPreview">
                <div class="icon-overlay">
                  <div class="icon-overlay-text">
                    <i class="bi bi-pencil-fill me-2"></i>画像を変更
                  </div>
                </div>
              </div>

              <!-- エラーメッセージ -->
              <?php if(!empty($errors)): ?>
                <div class="invalid-feedback d-block text-center">
                  <?php foreach ($errors as $error): ?>
                    <p><?= escape($error) ?></p>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <!-- 注意書き -->
              <div class="mt-4 text-center">
                <p class="text-muted mb-0">
                  <i class="bi bi-info-circle me-2"></i>
                  1MB以下、400px×400px以下、PNG/JPEG形式の画像をアップロードしてください
                </p>
              </div>

              <!-- 非表示のアップロードフォーム -->
              <form action="./exec_icon_upload.php" method="post" enctype="multipart/form-data" id="uploadForm">
                <!-- id="iconPreviewContainer"をクリックすると以下type="file"によりファイル選択ダイアログを開く -->
                <input type="file" id="iconFile" name="icon" accept="image/png,image/jpeg" style="display:none;">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
              </form>
            </div>

            <!-- カードフッター（ボタンエリア） -->
            <div class="card-footer bg-white border-top py-3">
              <div class="d-flex justify-content-between gap-2">
                <div>
                  <!-- デフォルトに戻すボタン -->
                  <form action="./exec_icon_reset.php" method="post" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <button type="submit" class="btn btn-outline-warning">
                      <i class="bi bi-arrow-counterclockwise me-2"></i>デフォルトに戻す
                    </button>
                  </form>
                </div>
                <div class="d-flex gap-2">
                  <!-- キャンセルボタン -->
                  <form action="./exec_icon_cancel.php" method="post" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <button type="submit" class="btn btn-outline-secondary">
                      <i class="bi bi-x-lg me-2"></i>キャンセル
                    </button>
                  </form>

                  <!-- 変更を保存ボタン -->
                  <form action="./exec_edit-icon.php" method="post" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <button type="submit" class="btn btn-primary btn-save" <?= empty($_SESSION['temp_icon']) ? 'disabled' : '' ?>>
                      <i class="bi bi-check-lg me-2"></i>変更を保存
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // アイコンをクリックでファイル選択ダイアログを開く
    document.getElementById('iconPreviewContainer').addEventListener('click', function() {
      document.getElementById('iconFile').click();
    });

    // ファイル選択後に自動的にフォーム送信
    document.getElementById('iconFile').addEventListener('change', function() {
      if (this.files.length > 0) {
        document.getElementById('uploadForm').submit();
      }
    });
  </script>
</body>

</html>
