<?php

/**
 * ユーザーのアイコンパスを取得
 *
 * @param int $userId ユーザーID
 * @return string アイコンのパス
 */
function getIconPath(int $userId): string
{
  $pdo = connectDb();
  $stmt = $pdo->prepare('SELECT icon FROM users WHERE id = :id');
  $stmt->execute([':id' => $userId]);
  $user = $stmt->fetch();

  // iconがNULLまたは空ならデフォルト画像
  if (empty($user['icon'])) {
    return '/image/icon/default.png';
  }

  $iconPath = dirname(__DIR__, 2) . '/public/image/icon/' . $user['icon'];

  // ファイルが存在しなければデフォルト画像
  if (!file_exists($iconPath)) {
    return '/image/icon/default.png';
  }

  // ユーザーの画像パスを返す
  return '/image/icon/' . $user['icon'];
}

/**
 * アイコンをimgタグで表示
 *
 * @param int $userId ユーザーID
 * @param int $size サイズ（デフォルト: 100px）
 * @param string $class CSSクラス名
 * @param string $alt alt属性（デフォルト: 'アイコン'）
 * @return string imgタグのHTML
 * <?php echo displayIcon($_SESSION['user']['id']); ?> で呼び出し
 */
function displayIcon(int $userId, int $size = 100, string $class = '', string $alt = 'アイコン'): string
{
  $iconPath = getIconPath($userId);
  $escapedPath = htmlspecialchars($iconPath, ENT_QUOTES, 'UTF-8');
  $escapedClass = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');
  $escapedAlt = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');

  return "<img src=\"{$escapedPath}\" width=\"{$size}\" height=\"{$size}\" class=\"{$escapedClass}\" alt=\"{$escapedAlt}\">";
}
