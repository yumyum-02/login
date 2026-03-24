<?php
require_once dirname(__DIR__, 2) . '/src/bootstrap.php';

// ログインチェック
requireLogin('../login.php');

// CSRFトークン検証
requireValidCsrfToken();

// ユーザーID取得
$user_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
// filter_input PHPの組み込み関数 外部からの入力データを安全に取得・検証
// filter_input(タイプ, 変数名, フィルタ, オプション)
// POSTデータから id パラメータを取得
// FILTER_VALIDATE_INT で整数としてバリデーション（悪意のあるSQL文を注入するのを防ぐため整数だけ受け付ける）
// 有効な整数なら その値、無効なら false を返す

if (!$user_id){
  $_SESSION['error'] = '無効なIDです';
  redirect('./admin.php');
}

//　削除処理
try {
  // ログイン中のユーザーは削除不可
  if (isset($_SESSION['user']['id']) && $user_id === (int)$_SESSION['user']['id']) {
    $_SESSION['error'] = 'ログイン中のユーザー情報は削除できません';
    redirect('./admin.php');
  }

  // 削除実行
  $deleted_count = deleteUserById($user_id);

  if ($deleted_count > 0) {
    $_SESSION['success'] = 'ユーザーを削除しました';
  } else {
    $_SESSION['error'] = 'ユーザーが見つかりませんでした';
  }

  redirect('./admin.php');
} catch (PDOException $e){
  $_SESSION['error'] = 'データベースエラーが発生しました';
  redirect('./admin.php');
}