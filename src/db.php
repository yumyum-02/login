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
