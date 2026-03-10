<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログインチェック
requireLogin('./login.php');

// CSRFトークン検証
if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
  exit('不正なリクエストです');
}

// CSRFトークンを破棄
destroyCsrfToken();

// ユーザーID取得
$user_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$user_id){
  exit('無効なIDです');
}

//　削除処理
try {
  // ログイン中のユーザーは削除不可
  if (isset($_SESSION['user']['id']) && $user_id === (int)$_SESSION['user']['id']) {
    $_SESSION['msg'] = 'ログイン中のユーザー情報は削除できません';
    redirect('./admin.php');
  }

  // 削除実行
  $deleted_count = deleteUserById($user_id);

  if ($deleted_count > 0) {
    $_SESSION['msg'] = 'ユーザーを削除しました';
  } else {
    $_SESSION['msg'] = 'ユーザーが見つかりませんでした';
  }
} catch (PDOException $e){
  $_SESSION['msg'] = '削除に失敗しました';
}

redirect('./admin.php');