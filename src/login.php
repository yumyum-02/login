<?php
require_once './bootstrap.php';

if (isset($_GET['msg'])) $success_logout_msg = $_GET['msg'];

$login_msg = '';
if (isset($_SESSION['msg'])) {
  $login_msg = $_SESSION['msg'];
  unset($_SESSION['msg']); // 一度表示したら消す
}

if (
  // ログインボタンが押され、かつ各フォームが入力されている場合
  isset($_POST['login_btn']) &&
  (isset($_POST['login_id']) && $_POST['login_id'] != '') &&
  (isset($_POST['password']) && $_POST['password'] != '')
) {
  // 不正リクエストチェック
  // トークンがセッションに存在しない、または一致しない場合は処理を中止
  if (empty($_SESSION['login_token']) || ($_SESSION['login_token'] !== $_POST['login_token'])) exit('不正なリクエストです');
  // トークンの破棄（1回限り有効にするため）
  if (isset($_SESSION['login_token'])) unset($_SESSION['login_token']);
  if (isset($_POST['login_token'])) unset($_POST['login_token']);

  $login_id = $_POST['login_id'];
  $password = $_POST['password'];

  try {
    // ユーザー情報の取得 db.phpの関数を使用
    $user_info = getUserLogin($login_id);

    // 取得した情報と入力されたパスワードを照合
    //password_verify = ハッシュ化されたパスワードの照合
    // $user_info に要素が1件以上あるかパスワードが一致している時
    if (count($user_info) && password_verify($password, $user_info[0]['password'])) {
      $_SESSION['user'] = array(
        'name'     => $user_info[0]['name'],
        'login_id' => $user_info[0]['login_id'],
      ); // セッションにユーザー情報を保存
      // ログイン成功後、メイン画面へリダイレクト
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
