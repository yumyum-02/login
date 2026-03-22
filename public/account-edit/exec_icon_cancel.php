<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// アイコン管理関数を読み込み
require_once dirname(__DIR__, 2) . '/src/functions/icon.php';

// ログイン認証チェック（未ログインはリダイレクト）
requireLogin('../login.php');

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}
// CSRFトークンを破棄
destroyCsrfToken();

// セッションに一時ファイル名があれば削除
if (!empty($_SESSION['temp_icon'])) {
  // 一時ファイルを削除
  deleteTempIconFile(AuthUser::getUserId());

  // セッションの一時ファイル名を削除
  unset($_SESSION['temp_icon']);
}

// account.phpにリダイレクト
redirect('../admin/account.php');
