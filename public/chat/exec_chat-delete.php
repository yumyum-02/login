<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークン検証
requireValidCsrfToken();

$postId = filter_var($_POST['post_id'] ?? '', FILTER_VALIDATE_INT);
$userId = AuthUser::getUserId();

if ($postId === false || $postId < 1 || $userId === null) {
  redirect('../admin/chat.php');
}

try {
  softDeletePostForOwner($postId, $userId);
} catch (PDOException $e) {
  // 一覧へ戻す（詳細はログで確認）
}

redirect('../admin/chat.php');
