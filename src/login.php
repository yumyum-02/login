<?php
require_once './bootstrap.php';

if (isset($_GET['msg'])) $success_logout_msg = $_GET['msg'];

$login_msg = '';
if (isset($_SESSION['msg'])) {
  $login_msg = $_SESSION['msg'];
  unset($_SESSION['msg']); // 一度表示したら消す
}

if (
  isset($_POST['login_btn']) &&
  (isset($_POST['login_id']) && $_POST['login_id'] != '') &&
  (isset($_POST['password']) && $_POST['password'] != '')
) {
  if (empty($_SESSION['login_token']) || ($_SESSION['login_token'] !== $_POST['login_token'])) exit('不正なリクエストです');
  if (isset($_SESSION['login_token'])) unset($_SESSION['login_token']);
  if (isset($_POST['login_token'])) unset($_POST['login_token']);

  $login_id = $_POST['login_id'];
  $password = $_POST['password'];

  try {
    $user_info = getUserLogin($login_id);

    if (count($user_info) && password_verify($password, $user_info[0]['password'])) {
      $_SESSION['user'] = array(
        'name'     => $user_info[0]['name'],
        'login_id' => $user_info[0]['login_id'],
      );
      header('Location: ./index.php');
      exit();
    } else {
      $err_msg = 'ログイン情報に誤りがあります。';
    }
  } catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage();
    exit();
  }
  $pdo = null;
  $stmt = null;
}

require_once './template/login_template.php';
