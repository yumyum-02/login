<?php

class User
{
  /**
   * メールアドレスでユーザーを取得（ログイン用）
   */
  public static function findByEmailForLogin($email)
  {
    $pdo = connectDb();
    $sql = 'SELECT id, email, password, name FROM users WHERE email = :EMAIL LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * メールアドレスでユーザーを取得（登録確認用）
   */
  public static function findByEmailForRegister($email)
  {
    $pdo = connectDb();
    $sql = 'SELECT * FROM users WHERE email = :EMAIL';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 全ユーザーを取得
   */
  public static function all()
  {
    $pdo = connectDb();
    $sql = 'SELECT id, name, email FROM users';
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * ユーザーを削除
   */
  public static function delete($id)
  {
    $pdo = connectDb();
    $sql = 'DELETE FROM users WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
  }

  /**
   * 新規ユーザーを登録
   */
  public static function create($name, $email, $password_hash)
  {
    try {
      $pdo = connectDb();
      $sql = 'INSERT INTO users (name, email, password) VALUES (:NAME, :EMAIL, :PASSWORD)';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':NAME', $name, PDO::PARAM_STR);
      $stmt->bindValue(':EMAIL', $email, PDO::PARAM_STR);
      $stmt->bindValue(':PASSWORD', $password_hash, PDO::PARAM_STR);
      $stmt->execute();
      return (int)$pdo->lastInsertId();
    } catch (PDOException $e) {
      return false;
    }
  }
}
