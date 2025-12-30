<?php

/**
 * セッションスタート
 */
ini_set('session.gc_maxlifetime', 1800);
ini_set('session.gc_divisor', 1);
session_start();
session_regenerate_id(); // セッションIDを新しいものに置き換える（★セッションハイジャック）

/**
 * DB接続情報
 */
const DB_HOST     = 'mysql:dbname=login_db;host=mysql;charset=utf8';
const DB_USER     = 'root'; //直接書いていいもの？
const DB_PASSWORD = 'secret'; //直接書いていいもの？

/**
 * 会員登録
 */
if (
  isset($_POST['regist_btn']) &&
  (isset($_POST['name'])       && $_POST['name']     != '') &&
  (isset($_POST['login_id'])   && $_POST['login_id'] != '') &&
  (isset($_POST['password'])   && $_POST['password'] != '')
) {
  /**
   * トークンチェック（★CSRF）
   */
  if (empty($_SESSION['regist_token']) || ($_SESSION['regist_token'] !== $_POST['regist_token'])) exit('不正なリクエストです');
  if (isset($_SESSION['regist_token'])) unset($_SESSION['regist_token']); //トークン破棄
  if (isset($_POST['regist_token']))    unset($_POST['regist_token']); //トークン破棄

  // POSTデータの取得
  $name     = $_POST['name'];
  $login_id = $_POST['login_id'];
  $password = $_POST['password'];

  // パスワードをハッシュ化する（★SQLインジェクション）
  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  try {
    /**
     * DB接続処理
     */
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION, // 例外が発生した際にスローする
      PDO::ATTR_EMULATE_PREPARES => false,                  // （★SQLインジェクション）
    ]);

    /**
     * 会員情報重複チェック
     * 入力されたIDがすでに登録済みかどうかをチェックする
     */
    $sql = ('
            SELECT login_id
            FROM users
            WHERE login_id = :LOGIN_ID;
        ');
    $stmt = $pdo->prepare($sql);
    // プレースホルダーに値をセット
    $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR);
    // SQL実行
    $stmt->execute();
    // ユーザ情報の取得
    $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ユーザ情報が取得できている＝件数が「1」の場合はエラーメッセージを返す
    if (count($user_info)) {
      $err_msg = 'そのIDはすでに使用されています。';
    } else {
      /**
       * 会員情報登録処理
       */
      $sql = ('
                INSERT INTO
                    users (name, login_id, password)
                VALUES
                    (:NAME, :LOGIN_ID, :PASSWORD)
            ');
      $stmt = $pdo->prepare($sql);
      // プレースホルダーに値をセット
      $stmt->bindValue(':NAME',     $name,          PDO::PARAM_STR);
      $stmt->bindValue(':LOGIN_ID', $login_id,      PDO::PARAM_STR);
      $stmt->bindValue(':PASSWORD', $password_hash, PDO::PARAM_STR);
      // SQL実行
      $stmt->execute();

      // ログイン画面へ遷移
      $msg = urlencode("会員登録が完了しました。");
      header('Location: ./login.php?msg=' . $msg);
      exit();
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
  <title>会員登録画面</title>
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>
  <div>
    <h2>会員登録画面</h2>

    <!-- 登録エラーメッセージ -->
    <?php if (isset($err_msg)) echo '<p class="err-msg">' . $err_msg . '</p>'; ?>

    <form action="#" method="post">
      <p><label for="name">ニックネーム</label><input type="text" name="name"></p>
      <p><label for="login_id">ID</label><input type="text" name="login_id"></p>
      <p><label for="password">パスワード</label><input type="password" name="password"></p>
      <input type="submit" value="登録" name="regist_btn">

      <?php
      // 不正リクエストチェック用のトークン生成（★CSRF）
      $token = bin2hex(random_bytes(32));
      $_SESSION['regist_token'] = $token;
      echo '<input type="hidden" name="regist_token" value="' . $token . '" />';
      ?>
    </form>

    <a href="./login.php">ログイン画面へ戻る</a>
  </div>
</body>

</html>