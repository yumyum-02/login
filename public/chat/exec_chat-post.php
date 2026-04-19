<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークン検証
requireValidCsrfToken();

// バリデーション
$chatpost = getTrimmedPostValue('chatpost');
$errors = getChatPostValidationErrors($chatpost);
if (!empty($errors)) {
  redirectWithErrors($errors, ['chatpost' => $chatpost], '../admin/chat-post.php');
}

$userId = AuthUser::getUserId();
if ($userId === null) {
  redirectWithErrors(['ログイン情報が無効です。'], ['chatpost' => $chatpost], '../admin/chat-post.php');
}

try {
  insertPost($chatpost, $userId);
  redirect('../admin/chat.php');
} catch (PDOException $e) {
  redirectWithErrors(['データベースエラーが発生しました'], ['chatpost' => $chatpost], '../admin/chat-post.php');
}
