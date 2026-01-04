<?php
function connectDb()
{
  $db_host = 'mysql:dbname=login_db;host=mysql;charset=utf8';
  $db_user = 'root';
  $db_password = 'secret';

  try {
    $pdo = new PDO($db_host, $db_user, $db_password, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
  } catch (PDOException $e) {
    echo '接続失敗' . $e->getMessage();
    exit();
  }
}

// ログイン時のユーザー情報取得
function getUserLogin($login_id): array
{
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

  $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC); //FETCH_ASSOC=連想配列として取得
  return $user_info;
}

//　会員登録時のユーザー情報取得
function getUserRegister($login_id): array
{
  $pdo = connectDb();
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
  $sql = '
        SELECT login_id, name
        FROM users
    ';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $users_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $users_info;
}

function escape($value)
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
