<?php
require_once './bootstrap.php';

// ログインチェック
if(!isset($_SESSION['user'])){
  header('Location: ./login.php');
  exit();
}

// トークン検証
if (empty($_GET['token']) || $_GET['token'] !== $_SESSION['delete_token']){
  exit('不正なリクエストです');
}

// ユーザーID取得
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$user_id){
  exit('無効なIDです');
}

//　削除処理
try {
  $pdo = connectDb();

  // 削除対象ユーザーの情報を取得
  $sql = 'SELECT email FROM users WHERE id = :id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $target_user = $stmt->fetch(PDO::FETCH_ASSOC);

  // ログイン中のユーザーは削除不可
  if ($target_user && $target_user['email'] === $_SESSION['user']['email']) {
    $_SESSION['msg'] = 'ログイン中のユーザー情報は削除できません';
    header('Location: ./admin.php');
    exit();
  }

  // 削除実行
  $sql = 'DELETE FROM users WHERE id = :id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  $_SESSION['msg'] = 'ユーザーを削除しました';
} catch (PDOException $e){
  $_SESSION['msg'] = '削除に失敗しました';
}

// トークン破棄
unset($_SESSION['delete_token']);

header('Location: ./admin.php');
exit();