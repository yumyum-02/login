<?php

// セッションスタート
ini_set('session.gc_maxlifetime', 1800); //これは必要？ セッションの長さを指定
ini_set('session.gc_divisor', 1); //これは必要？ セッション初期化確率を制御 100%に設定
session_start();
session_regenerate_id(); //セッションIDを新しく生成 セキュリティ攻撃を防ぐ

// DB接続情報
const DB_HOST     = 'mysql:dbname=login_db;host=mysql;charset=utf8';
const DB_USER     = 'root'; //直接書いていいもの？
const DB_PASSWORD = 'secret'; //直接書いていいもの？

// 会員登録・ログアウト完了メッセージの取得
if (isset($_GET['msg'])) $success_logout_msg = $_GET['msg']; //msgはどこからきている？

/**
 * ログイン
 */
if (
  isset($_POST['login_btn']) &&
  (isset($_POST['login_id'])  && $_POST['login_id'] != '') &&
  (isset($_POST['password'])  && $_POST['password'] != '')
) {
  /**
   * トークンチェック（★CSRF）
   */
  if (empty($_SESSION['login_token']) || ($_SESSION['login_token'] !== $_POST['login_token'])) exit('不正なリクエストです');
  if (isset($_SESSION['login_token'])) unset($_SESSION['login_token']); //トークン破棄
  if (isset($_POST['login_token']))    unset($_POST['login_token']); //トークン破棄

  // POSTデータの取得
  $login_id = $_POST['login_id'];
  $password = $_POST['password'];

  try {
    /**
     * DB接続処理
     */
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION, // 例外が発生した際にスローする
      PDO::ATTR_EMULATE_PREPARES => false,                  // （★SQLインジェクション対策）
    ]);

    /**
     * ログイン処理
     */
    $sql = ('
            SELECT login_id, password, name
            FROM users
            WHERE login_id = :LOGIN_ID
        ');
    $stmt = $pdo->prepare($sql);
    // プレースホルダーに値をセット
    $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR);
    // SQL実行
    $stmt->execute();

    /**
     * ログイン情報が正しいかをチェック
     */
    $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($user_info) && password_verify($password, $user_info[0]['password'])) {

      // ログイン状態確認用にセッションにデータ保存（★ログイン機能の実現）
      $_SESSION['user'] = array(
        'name'     => $user_info[0]['name'],
        'login_id' => $user_info[0]['login_id'],
      );

      // ログイン後はトップページへ遷移する
      header('Location: ./index.php');
      exit();
    } else {
      $err_msg = 'ログイン情報に誤りがあります。';
    }
  } catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage();
    exit();
  }
  // DBとの接続を切る
  $pdo = null;
  $stmt = null;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>
  <div>
    <h2>ログイン画面</h2>

    <!-- ログアウト成功メッセージ -->
    <?php if (isset($success_logout_msg)) echo '<p class="success_logout_msg">' . $success_logout_msg . '</p>'; ?>

    <!-- ログイン失敗メッセージ -->
    <?php if (isset($err_msg)) echo '<p class="err-msg">' . $err_msg . '</p>'; ?>

    <form action="" method="post">
      <p><label for="login_id">ID</label><input type="text" name="login_id"></p>
      <p><label for="password">パスワード</label><input type="password" name="password"></p>
      <input type="submit" value="ログイン" name="login_btn">

      <?php
      // 不正リクエストチェック用のトークン生成（★CSRF）
      $token = bin2hex(random_bytes(32));
      $_SESSION['login_token'] = $token;
      echo '<input type="hidden" name="login_token" value="' . $token . '" />';
      ?>
    </form>

    <a href="./regist.php">会員登録はこちら</a>
  </div>
</body>

</html>