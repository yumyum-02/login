<?php
/**
 * ナビゲーションバー共通パーツ
 *
 * @param string $base_path ベースパス（デフォルト: './'）
 */

// デフォルト値
$base_path = $base_path ?? './';
?>
<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
  <div class="container-fluid">
    <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="<?= $base_path ?>dashboard.php">
      <i class="bi bi-house-door-fill me-2"></i>Dashboard
    </a>
    <div class="d-flex align-items-center">
      <span class="text-white me-3 d-none d-md-inline d-flex align-items-center">
        <?= displayIcon(AuthUser::getUserId(), 24, 'rounded-circle me-2', 'ユーザーアイコン') ?>
        <?= escape(AuthUser::getName()) ?>
      </span>
    </div>
  </div>
</nav>
