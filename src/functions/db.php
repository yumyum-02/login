<?php
function connectDb(): PDO
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

// emailに一致しているusersテーブルのレコードを取得して返す
function getUserLogin(string $login): array
{
  // PDOでデータベースに接続
  $pdo = connectDb();
  // userテーブルからemailに一致するレコードを取得
  $sql = '
    SELECT id, email, password, name
    FROM users
    WHERE email = :EMAIL
          LIMIT 1
    '; // WHEREでユーザーIDが入力された値と同じレコードだけを取り出す
  $stmt = $pdo->prepare($sql); // SQL文をデータベースに送る準備 prepare() を使うと、後で値を安全にbindValue()で入れられる
  $stmt->bindValue(':EMAIL', $login, PDO::PARAM_STR);
  $stmt->execute(); //データベースに送って結果を出す
  $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC); // 返ってきたデータを連想配列の形に変換して全部取得(FETCH_ASSOC=連想配列として取得)
  return $user_info; // user_infoを外でも使えるように返す
}

//　会員登録時のユーザー情報取得
function getUserRegister(string $email): array
{
  $pdo = connectDb();
  // usersテーブルからemailに一致するレコードを取得 一意のIDしか許可しないのでチェックしている
  $sql = ('
  SELECT email
  FROM users
  WHERE email = :EMAIL;
  ');
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR); // PARAM_STR=名前やアドレスなどのテキストデータをデータベースに挿入したり更新したりする際に利用
  $stmt->execute();
  $user_info = $stmt->fetchAll(PDO::FETCH_ASSOC); //FETCH_ASSOC=連想配列として取得
  return $user_info;
}

// 管理者画面用　全ユーザー情報取得
function getUsersInfo(): array
{
  $pdo = connectDb();
  $sql = ('
        SELECT id, email, name
        FROM users
    ');
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $users_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $users_info;
}

// ユーザー削除（ID指定）
function deleteUserById(int $id): int
{
  $pdo = connectDb();
  $sql = 'DELETE FROM users WHERE id = :ID';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->rowCount();
  // 削除された行を返す 1 →1行削除された（成功） 0 →削除されてない（失敗）
}

// ユーザー登録（新規ユーザーをusersテーブルに挿入）
function registerUser(string $name, string $email, string $password_hash): int
{
  $pdo = connectDb();
  $sql = 'INSERT INTO users (name, email, password) VALUES (:NAME, :EMAIL, :PASSWORD)';
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':NAME', $name, PDO::PARAM_STR);
  $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR);
  $stmt->bindValue(':PASSWORD', $password_hash, PDO::PARAM_STR);
  $stmt->execute();

  // 新しく登録されたユーザーのIDを返す
  return (int)$pdo->lastInsertId();
}

/**
 * ユーザー情報変更（汎用）
 * @throws InvalidArgumentException 不正なフィールド名の場合
 */
function updateUser(int $user_id, string $field, string $value): bool
{
  // 許可されたフィールドのホワイトリスト（SQLインジェクション対策）
  $allowed_fields = ['name', 'email', 'password'];

  // ホワイトリストにないフィールドは拒否
  if (!in_array($field, $allowed_fields, true)) {
    throw new InvalidArgumentException("不正なフィールド名: $field");
    // throw : 例外を投げる（現在の関数の実行を中断し、呼び出し元に例外を伝える）
    // try-catch があればキャッチされ処理は続く、なければプログラムが停止する
    // InvalidArgumentException : 引数が不正な場合に使う例外クラス
  }

  $pdo = connectDb();
  $sql = "UPDATE users SET $field = :value WHERE id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':value', $value, PDO::PARAM_STR);
  $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  return true;
}

// ユーザーのアイコンファイル名をDBに更新
//　アイコンリセット時に$filenameにnullを渡すため?でnullを許容
function updateUserIcon(int $userId, ?string $filename): void
{
  $pdo = connectDb();
  $stmt = $pdo->prepare('UPDATE users SET icon = :icon WHERE id = :id');
  $stmt->execute([
    ':icon' => $filename,
    ':id' => $userId
  ]);
}

// ユーザーのアイコンファイル名をDBから取得
function getUserIcon(int $userId): ?string
{
  $pdo = connectDb();
  $stmt = $pdo->prepare('SELECT icon FROM users WHERE id = :id');
  $stmt->execute([':id' => $userId]);

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['icon'] ?? null;
}
