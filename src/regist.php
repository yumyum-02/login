<?php
session_start();
session_regenerate_id();

const DB_HOST = 'mysql:dbname=login_db;host=mysql;charset=utf8';
const DB_USER = 'root';
const DB_PASSWORD = 'secret';

if (
  isset($_POST['regist_btn']) &&
  (isset($_POST['name']) && $_POST['name'] != '') &&
  (isset($_POST['login_id']) && $_POST['login_id'] != '') &&
  (isset($_POST['password']) && $_POST['password'] != '')
) {
  if (empty($_SESSION['regist_token']) || ($_SESSION['regist_token'] !== $_POST['regist_token'])) exit('不正なリクエストです');
  if (isset($_SESSION['regist_token'])) unset($_SESSION['regist_token']);
  if (isset($_POST['regist_token'])) unset($_POST['regist_token']);

  $name = $_POST['name'];
  $ligin_id = $_POST['login_id'];
  $password = $_POST['password'];

  $password_hash = password_hash($password, PASSWORD_DEFAULT);

  try {
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $sql = ('
    SELECT login_id
    FROM users
    WHERE login_id = :LOGIN_ID;
    ');
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR); // PARAM_STR=名前やアドレスなどのテキストデータをデータベースに挿入したり更新したりする際に利用
    $stmt->execute();
    $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC); //FETCH_ASSOC=連想配列として取得

    if (count($user_info)) {
      $err_msg = 'そのIDはすでに使用されています。';
    } else {
      $sql = ('
      INSERT INTO users (name, login_id, password)
      VALUES (:NAME, :LOGIN_ID, :PASSWORD);
      ');
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':NAME', $name, PDO::PARAM_STR);
      $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR);
      $stmt->bindValue(':PASSWORD', $password_hash, PDO::PARAM_STR);
      $stmt->execute();

      $msg = urlencode('会員登録が完了しました。ログインしてください。');
      header('Location: ./login.php?msg=' . $msg);
      exit();
    }
  } catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage();
    exit();
  }

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
      // 不正リクエストチェック用のトークン生成
      $token = bin2hex(random_bytes(32));
      $_SESSION['regist_token'] = $token;
      echo '<input type="hidden" name="regist_token" value="' . $token . '" />';
      ?>
    </form>

    <a href="./login.php">ログイン画面へ戻る</a>
  </div>
</body>

</html>