<?php
require_once '../src/bootstrap.php';

// ログアウトメッセージ用
if (isset($_GET['msg'])) $success_logout_msg = $_GET['msg'];

// ページ固有の処理
// メッセージの表示
$errors = $_SESSION['errors'] ?? [];
$err_msg = $_SESSION['error_message'] ?? null;
$login_msg = $_SESSION['msg'] ?? '';
// 一度表示したらクリア
unset($_SESSION['errors'], $_SESSION['error_message'], $_SESSION['msg']);

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

require_once '../src/template/login_template.php';
