<?php

// アイコンディレクトリのパスを取得
function getIconDirectory(): string
{
  return dirname(__DIR__, 2) . '/public/image/icon/';
}

// アイコンファイルのパスを生成
function getIconFilePath(int $userId, string $extension, bool $isTemp = false): string
{
  $filename = $isTemp ? "{$userId}_temp.{$extension}" : "{$userId}.{$extension}";
  return getIconDirectory() . $filename;
}

/**
 * MIMEタイプから拡張子を取得
 * @throws InvalidArgumentException サポートされていない画像形式の場合
 */
function getImageExtensionFromMime(string $mimeType): string
{
  return match($mimeType) {
    'image/png' => 'png',
    'image/jpeg' => 'jpg',
    default => throw new InvalidArgumentException('サポートされていない画像形式です')
  };
}

// ファイルのMIMEタイプを取得
function getImageMimeType(string $filePath): ?string
{
  $imageInfo = getimagesize($filePath);
  return $imageInfo['mime'] ?? null;
}

// アップロードファイルを指定パスに移動
function moveUploadedIconFile(string $tmpPath, string $destPath): bool
{
  return move_uploaded_file($tmpPath, $destPath);
}

// ファイルをリネーム
function renameIconFile(string $oldPath, string $newPath): bool
{
  return rename($oldPath, $newPath);
}

// ファイルが存在すれば削除
function deleteIconFileIfExists(string $path): void
{
  if (file_exists($path)) {
    unlink($path);
  }
}

// アイコンファイルのパーミッションを設定
function setIconFilePermission(string $path): void
{
  chmod($path, 0644);
}

// 特定ユーザーのアイコンファイルを全て削除
function deleteAllIconFiles(int $userId, bool $isTemp = false): void
{
  $extensions = ['jpg', 'jpeg', 'png'];

  foreach ($extensions as $ext) {
    $path = getIconFilePath($userId, $ext, $isTemp);
    deleteIconFileIfExists($path);
  }
}

// ユーザーのアイコンファイルが存在するかチェック
function iconFileExists(string $filename): bool
{
  $path = getIconDirectory() . $filename;
  return file_exists($path);
}

// デフォルトアイコンのパスを取得
function getDefaultIconPath(): string
{
  return '/image/icon/default.png';
}

// ユーザーアイコンのWebパスを取得
function getUserIconWebPath(string $filename): string
{
  return '/image/icon/' . $filename;
}
