<?php

/**
 * アイコンディレクトリのパスを取得
 *
 * @return string
 */
function getIconDirectory(): string
{
  return dirname(__DIR__, 2) . '/public/image/icon/';
}

/**
 * アイコンファイルのパスを生成
 *
 * @param int $userId
 * @param string $extension
 * @param bool $isTemp
 * @return string
 */
function getIconFilePath(int $userId, string $extension, bool $isTemp = false): string
{
  $filename = $isTemp ? "{$userId}_temp.{$extension}" : "{$userId}.{$extension}";
  return getIconDirectory() . $filename;
}

/**
 * MIMEタイプから拡張子を取得
 *
 * @param string $mimeType
 * @return string
 * @throws InvalidArgumentException
 */
function getImageExtensionFromMime(string $mimeType): string
{
  return match($mimeType) {
    'image/png' => 'png',
    'image/jpeg' => 'jpg',
    default => throw new InvalidArgumentException('サポートされていない画像形式です')
  };
}

/**
 * ファイルのMIMEタイプを取得
 *
 * @param string $filePath
 * @return string|null
 */
function getImageMimeType(string $filePath): ?string
{
  $imageInfo = getimagesize($filePath);
  return $imageInfo['mime'] ?? null;
}

/**
 * アイコンディレクトリが存在することを保証
 *
 * @param string $dir
 * @return void
 */
function ensureIconDirectoryExists(string $dir): void
{
  if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
  }
}

/**
 * アップロードファイルを指定パスに移動
 *
 * @param string $tmpPath
 * @param string $destPath
 * @return bool
 */
function moveUploadedIconFile(string $tmpPath, string $destPath): bool
{
  return move_uploaded_file($tmpPath, $destPath);
}

/**
 * ファイルをリネーム
 *
 * @param string $oldPath
 * @param string $newPath
 * @return bool
 */
function renameIconFile(string $oldPath, string $newPath): bool
{
  return rename($oldPath, $newPath);
}

/**
 * ファイルが存在すれば削除
 *
 * @param string $path
 * @return void
 */
function deleteIconFileIfExists(string $path): void
{
  if (file_exists($path)) {
    unlink($path);
  }
}

/**
 * アイコンファイルのパーミッションを設定
 *
 * @param string $path
 * @return void
 */
function setIconFilePermission(string $path): void
{
  chmod($path, 0644);
}

/**
 * 特定ユーザーのアイコンファイルを全て削除
 *
 * @param int $userId
 * @param bool $isTemp
 * @return void
 */
function deleteAllIconFiles(int $userId, bool $isTemp = false): void
{
  $extensions = ['jpg', 'jpeg', 'png'];

  foreach ($extensions as $ext) {
    $path = getIconFilePath($userId, $ext, $isTemp);
    deleteIconFileIfExists($path);
  }
}

/**
 * ユーザーのアイコンファイルが存在するかチェック
 *
 * @param string $filename
 * @return bool
 */
function iconFileExists(string $filename): bool
{
  $path = getIconDirectory() . $filename;
  return file_exists($path);
}

/**
 * デフォルトアイコンのパスを取得
 *
 * @return string
 */
function getDefaultIconPath(): string
{
  return '/image/icon/default.png';
}

/**
 * ユーザーアイコンのWebパスを取得
 *
 * @param string $filename
 * @return string
 */
function getUserIconWebPath(string $filename): string
{
  return '/image/icon/' . $filename;
}
