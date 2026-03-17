<?php

/**
 * ユーザーのアイコンファイル名をDBに更新
 *
 * @param int $userId
 * @param string|null $filename
 * @return void
 */
function updateUserIconInDb(int $userId, ?string $filename): void
{
  $pdo = connectDb();
  $stmt = $pdo->prepare('UPDATE users SET icon = :icon WHERE id = :id');
  $stmt->execute([
    ':icon' => $filename,
    ':id' => $userId
  ]);
}

/**
 * ユーザーのアイコンファイル名をDBから取得
 *
 * @param int $userId
 * @return string|null
 */
function getUserIconFromDb(int $userId): ?string
{
  $pdo = connectDb();
  $stmt = $pdo->prepare('SELECT icon FROM users WHERE id = :id');
  $stmt->execute([':id' => $userId]);

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['icon'] ?? null;
}
