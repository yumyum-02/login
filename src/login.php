<?php
require './db.php';

session_start();
session_regenerate_id();

const DB_HOST = 'mysql:dbname=login_db;host=mysql;charset=utf8';
const DB_USER = 'root';
const DB_PASSWORD = 'secret';

if (isset($_GET['msg'])) $success_logout_msg = $_GET['msg'];

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
    $pdo = connectDb();
    $sql = ('
    SELECT login_id, password, name
    FROM users
    WHERE login_id = :LOGIN_ID
    '); //ログインIDに一致するレコードを取得
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR); //ログインIDをセットしている
    $stmt->execute();
    // PDOでデータベースに接続し、userテーブルからログインIDに一致するレコードを取得

    $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

require './template/login_template.php';
