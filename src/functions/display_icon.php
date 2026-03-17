<?php

// 依存ファイルを読み込み
require_once __DIR__ . '/icon-file.php';
require_once __DIR__ . '/icon-db.php';

/**
 * ユーザーのアイコンパスを取得
 *
 * @param int $userId
 * @return string アイコンのWebパス
 */
function getIconPath(int $userId): string
{
  // DBからアイコンファイル名を取得
  $filename = getUserIconFromDb($userId);

  // iconがNULLまたは空ならデフォルト画像
  if (empty($filename)) {
    return getDefaultIconPath();
  }

  // ファイルが存在しなければデフォルト画像
  if (!iconFileExists($filename)) {
    return getDefaultIconPath();
  }

  // ユーザーの画像パスを返す
  return getUserIconWebPath($filename);
}

/**
 * アイコンをimgタグで表示
 *
 * @param int $userId
 * @param int $size
 * @param string $class
 * @param string $alt
 * @return string imgタグのHTML
 */
function displayIcon(int $userId, int $size = 100, string $class = '', string $alt = 'アイコン'): string
{
  $iconPath = getIconPath($userId);
  $escapedPath = htmlspecialchars($iconPath, ENT_QUOTES, 'UTF-8');
  $escapedClass = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');
  $escapedAlt = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');

  return "<img src=\"{$escapedPath}\" width=\"{$size}\" height=\"{$size}\" class=\"{$escapedClass}\" alt=\"{$escapedAlt}\">";
}
