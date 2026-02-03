<?php
function connectDb()
{
  $db_host = 'mysql:dbname=login_db;host=mysql;charset=utf8';
  $db_user = 'root';
  $db_password = 'secret';

  try {
    $pdo = new PDO($db_host, $db_user, $db_password, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //エラーモードを例外に設定（デフォルトでは PDO は エラーが発生しても警告を出すだけで処理は続くためエラーが出たらcatchでエラーメッセージを出すようにしている）
      PDO::ATTR_EMULATE_PREPARES => false, //後から値を差し込むモードを無効化(安全性を高めるため)
    ]);
    return $pdo;
  } catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage(); // $e->getMessage()はデバッグやログに正確な原因を表示する
    exit();
  }
}

// login_idに一致しているusersテーブルのレコードを取得して返す
function getUserLogin(string $login): array
{
  // PDOでデータベースに接続
  $pdo = connectDb();
  // 、userテーブルからログインIDに一致するレコードを取得
  $sql = '
    SELECT login_id, password, name, email
    FROM users
    WHERE login_id = :LOGIN_ID
          OR email = :EMAIL
          LIMIT 1
    '; // WHEREでユーザーIDが入力された値と同じレコードだけを取り出す
  $stmt = $pdo->prepare($sql); // SQL文をデータベースに送る準備 prepare() を使うと、後で値を安全にbindValue()で入れられる
  $stmt->bindValue(':LOGIN_ID', $login, PDO::PARAM_STR); // 準備したSQLの中の:login_id という穴に、変数 $login_id の値を入れて、安全に実行できるようにする( PDO::PARAM_STR=文字列として扱う)
  $stmt->bindValue(':EMAIL', $login, PDO::PARAM_STR);
  $stmt->execute(); //データベースに送って結果を出す
  $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC); // 返ってきたデータを連想配列の形に変換して全部取得(FETCH_ASSOC=連想配列として取得)
  return $user_info; // user_infoを外でも使えるように返す
}

//　会員登録時のユーザー情報取得
function getUserRegister($login_id): array
{
  $pdo = connectDb();
  // usersテーブルからログインIDに一致するレコードを取得 一意のIDしか許可しないのでチェックしている
  $sql = ('
  SELECT login_id
  FROM users
  WHERE login_id = :LOGIN_ID;
  ');
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':LOGIN_ID', $login_id, PDO::PARAM_STR); // PARAM_STR=名前やアドレスなどのテキストデータをデータベースに挿入したり更新したりする際に利用
  $stmt->execute();
  $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC); //FETCH_ASSOC=連想配列として取得
  return $user_info;
}

// 管理者画面用　全ユーザー情報取得
function getUsersInfo(): array
{
  $pdo = connectDb();
  $sql = ('
        SELECT login_id, name
        FROM users
    ');
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $users_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $users_info;
}

// クロスサイトスクリプティング(XSS)対策用エスケープ関数
// 今回はユーザー情報を出しているページで使用
function escape($value)
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
