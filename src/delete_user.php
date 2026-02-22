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
  $sql = 'DELETE FROM users WHERE id = :id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
  $stmt->excute();

  $_SESSION['msg'] = 'ユーザーを削除しました';
} catch (PDOException $e){
  $_SESSION['msg'] = '削除に失敗しました';
}

// トークン破棄
unset($_SESSION['delete_token']);

header('Location: ./admin.php');
exit();