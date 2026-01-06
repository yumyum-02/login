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

    <form action="./exec_register.php" method="post">
      <p><label for="name">ニックネーム</label><input type="text" name="name"></p>
      <p><label for="login_id">ID</label><input type="text" name="login_id"></p>
      <p><label for="password">パスワード</label><input type="password" name="password"></p>
      <input type="submit" value="登録" name="regist_btn">

      <?php
      // 不正リクエストチェック用のトークン生成
      $token = bin2hex(random_bytes(32)); //ランダムでユニークなIDを生成し　bin2hex=バイナリデータを16進数文字列に変換 random_bytes(32)=32バイトのランダムなバイナリデータを生成
      $_SESSION['regist_token'] = $token; //セッションにトークンを保存。後でフォームが送信されたときに、送信されてきたトークンとセッション内のトークンを照合して、正当なリクエストかどうか確認
      echo '<input type="hidden" name="regist_token" value="' . $token . '" />';
      ?>
    </form>

    <a href="./login.php">ログイン画面へ戻る</a>
  </div>
</body>

</html>