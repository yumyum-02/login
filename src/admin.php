<?php
require_once './bootstrap.php';
require_once './logout.php';

if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

// ユーザー削除処理
if (isset($_POST['delete_user']) && isset($_POST['delete_user_id']) && isset($_POST['delete_token'])) {
  if (!empty($_SESSION['delete_token']) && $_SESSION['delete_token'] === $_POST['delete_token']) {
    $delete_id = filter_var($_POST['delete_user_id'], FILTER_VALIDATE_INT);
    if ($delete_id !== false) {
      // 削除前に自分かどうかチェック（セッションのemailから現在ユーザーのIDを取得）
      $current_email = $_SESSION['user']['email'] ?? '';
      $users_for_check = array_filter(getUsersInfo(), fn($u) => $u['email'] === $current_email);
      $current_user_id = !empty($users_for_check) ? (int)reset($users_for_check)['id'] : null;
      $is_self = ($current_user_id !== null && $current_user_id === $delete_id);

      $deleted_count = deleteUserById($delete_id);
      if ($deleted_count > 0) {
        unset($_SESSION['delete_token']);
        if ($is_self) {
          unset($_SESSION['logout_token']);
          $_SESSION = [];
          if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 1800, '/');
          }
          session_destroy();
          header('Location: ./regist.php?msg=' . urlencode('ユーザーを削除しました。'));
          exit();
        }
        header('Location: ./admin.php?msg=' . urlencode('ユーザーを削除しました。'));
        exit();
      }
    }
  }
}

$users_info = getUsersInfo();

$pdo = null;
$stmt = null;

$token = bin2hex(random_bytes(32));
$_SESSION['logout_token'] = $token;

$_SESSION['delete_token'] = bin2hex(random_bytes(32));

require './template/admin_template.php';
