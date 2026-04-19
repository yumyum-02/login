<?php

/**
 * サイドバー共通パーツ
 *
 * @param string $active_page アクティブなページ（'dashboard', 'account', 'admin'）
 * @param string $csrf_token CSRFトークン
 * @param bool $is_admin 管理者メニューを表示するか
 * @param string $base_path ベースパス（デフォルト: './'）
 * @param string $logout_path ログアウトパス（デフォルト: '../logout.php'）
 */

// デフォルト値
$active_page = $active_page ?? 'dashboard';
$is_admin = $is_admin ?? false;
$base_path = $base_path ?? './';
$logout_path = $logout_path ?? '../logout.php';
?>
<div class="offcanvas offcanvas-start bg-white shadow-sm" tabindex="-1" id="sidebarMenu">
  <div class="offcanvas-header border-bottom d-md-none">
    <h5 class="offcanvas-title d-flex align-items-center">
      <?= displayIcon(AuthUser::getUserId(), 24, 'rounded-circle me-2', 'ユーザーアイコン') ?>
      <?= escape(AuthUser::getName()) ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?= $active_page === 'dashboard' ? 'active' : '' ?>" href="<?= $base_path ?>dashboard.php">
          <i class="bi bi-house-door-fill"></i>
          ダッシュボード
        </a>
      </li>
      <?php if ($is_admin): ?>
        <li class="nav-item">
          <a class="nav-link <?= $active_page === 'admin' ? 'active' : '' ?>" href="<?= $base_path ?>admin.php">
            <i class="bi bi-people-fill"></i>
            ユーザー一覧
          </a>
        </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link <?= $active_page === 'account' ? 'active' : '' ?>" href="<?= $base_path ?>account.php">
          <i class="bi bi-person-fill"></i>
          アカウント情報
        </a>
      </li>
      <li class="nav-item mt-3">
        <form action="<?= $logout_path ?>" method="post" class="p-2">
          <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
          <button type="submit" class="btn btn-outline-danger btn-sm w-100" name="logout">
            <i class="bi bi-box-arrow-right me-1"></i>ログアウト
          </button>
        </form>
      </li>
    </ul>
  </div>
</div>
