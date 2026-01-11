<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン後の画面</title>
</head>

<body>
  <!-- ログインIDとユーザー名の表示 -->
  <?php
  echo 'ID：' . escape($_SESSION['user']['login_id']) . '<br>'; // escape=クロスサイトスクリプティング対策用に文字列で認識するように指定
  echo 'ユーザー名：' . escape($_SESSION['user']['name']);
  ?>
  <form action="#" method="post">
    <!-- ログアウトボタン logout.php -->
    <input type="submit" name="logout" value="ログアウト">
    <!-- 外部から勝手にログアウトではなくこの画面からログアウトしたことを照会している -->
    <?php
    $token = bin2hex(random_bytes(32)); //ランダムでユニークなIDを生成し　bin2hex=バイナリデータを16進数文字列に変換 random_bytes(32)=32バイトのランダムなバイナリデータを生成
    $_SESSION['logout_token'] = $token; //セッションにトークンを保存。後でフォームが送信されたときに、送信されてきたトークンとセッション内のトークンを照合して、正当なリクエストかどうか確認
    echo '<input type="hidden" name="logout_token" value="' . $token . '" />'; //サーバーは $_POST['logout_token'] と $_SESSION['logout_token'] を照合して正当性チェックする
    ?>
  </form>
</body>

</html>