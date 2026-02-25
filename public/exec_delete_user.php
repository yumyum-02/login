<?php
require_once '../src/bootstrap.php';

// CSRFトークン検証
if (!verifyCsrfToken($_GET['token'] ?? '')) {
  exit('不正なリクエストです');
}

// ログインチェック
if (!isset($_SESSION['user'])) {
  header('Location: ./login.php');
  exit();
}

// ユーザーID取得
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$user_id){
  exit('無効なIDです');
}

//　削除処理
try {
  // ログイン中のユーザーは削除不可
  if (isset($_SESSION['user']['id']) && $user_id === (int)$_SESSION['user']['id']) {
    $_SESSION['msg'] = 'ログイン中のユーザー情報は削除できません';
    header('Location: ./admin.php');
    exit();
  }

  // 削除実行
  $pdo = connectDb();
  $sql = 'DELETE FROM users WHERE id = :id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  $_SESSION['msg'] = 'ユーザーを削除しました';
} catch (PDOException $e){
  $_SESSION['msg'] = '削除に失敗しました';
}

// CSRFトークンを破棄
destroyCsrfToken();

header('Location: ./admin.php');
exit();