<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// ページ固有の処理
// CSRFトークンを生成
$csrf_token = generateCsrfToken();

// 投稿一覧取得（処理は public/chat/exec_chat.php）
require_once dirname(__DIR__) . '/chat/exec_chat.php';

// テンプレート読み込み
require_once dirname(__DIR__, 2) . '/src/template/chat_template.php';