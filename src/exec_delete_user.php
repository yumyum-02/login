<?php
/**
 * ユーザー削除処理を行う。
 * 削除リクエストでない・検証失敗時は admin.php へリダイレクト。
 * 削除成功時は自分なら regist.php、他人なら admin.php へリダイレクトして exit する。
 */
function processUserDelete(): void
{
  if (!isset($_POST['delete_user']) || !isset($_POST['delete_user_id']) || !isset($_POST['delete_token'])) {
    header('Location: ./admin.php');
    exit();
  }

  if (empty($_SESSION['delete_token']) || $_SESSION['delete_token'] !== $_POST['delete_token']) {
    header('Location: ./admin.php');
    exit();
  }

  $delete_id = filter_var($_POST['delete_user_id'], FILTER_VALIDATE_INT);
  if ($delete_id === false) {
    header('Location: ./admin.php');
    exit();
  }

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

  header('Location: ./admin.php');
  exit();
}
