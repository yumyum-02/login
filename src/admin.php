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
      $deleted_count = deleteUserById($delete_id);
      if ($deleted_count > 0) {
        $deleted_email = $_SESSION['user']['email'] ?? '';
        $is_self = ($deleted_email !== '' && $deleted_user = array_filter(getUsersInfo(), fn($u) => (int)$u['id'] === $delete_id) ? false : true);
        // 削除対象が自分かどうか（削除後はgetUsersInfoに残っていないので、削除前にチェック）
        $current_user_id = null;
        foreach (getUsersInfo() as $u) {
          if ($u['email'] === $_SESSION['user']['email']) {
            $current_user_id = (int)$u['id'];
            break;
          }
        }
        $is_self = ($current_user_id === $delete_id);

        if ($is_self) {
          unset($_SESSION['delete_token']);
          unset($_SESSION['logout_token']);
          $_SESSION = [];
          if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 1800, '/');
          }
          session_destroy();
          header('Location: ./regist.php?msg=' . urlencode('ユーザーを削除しました。同じ情報で再度登録できます。'));
          exit();
        }
        unset($_SESSION['delete_token']);
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

$delete_token = bin2hex(random_bytes(32));
$_SESSION['delete_token'] = $delete_token;

require './template/admin_template.php';
