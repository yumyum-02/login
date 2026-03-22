<?php

// アイコンディレクトリのパスを取得
function getIconDirectory(): string
{
  return dirname(__DIR__, 2) . '/public/image/icon/';
}

// アイコンファイルのパスを生成
// extension: 拡張子（jpg, png等） / isTemp: 一時ファイルならtrue
function getIconFilePath(int $userId, string $extension, bool $isTemp = false): string
{
  // 一時ファイルなら "{userId}_temp.{拡張子}"、本番なら "{userId}.{拡張子}"
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
    // default: png でも jpeg でもない場合
    // throw new InvalidArgumentException(): エラーを投げて処理を止める
    default => throw new InvalidArgumentException('サポートされていない画像形式です')
  };
}

// ファイルのMIMEタイプを取得
// getimagesizeで情報を取得できずnullなこともあるので ? でnullを許容
function getImageMimeType(string $filePath): ?string
{
  $imageInfo = getimagesize($filePath);
  return $imageInfo['mime'] ?? null;
}

// アップロード一時ファイルを指定パスに移動
function moveUploadedIconFile(string $tmpPath, string $destPath): bool
{
  return move_uploaded_file($tmpPath, $destPath);
  // move_uploaded_file() はPHPの組み込み関数
  // - ファイルが本当にアップロードされたものかチェック（セキュリティ対策）
  // - アップロードファイルしか移動できない（勝手に他のファイルを移動させない）
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
  // file_exists はPHPの組み込み関数(ファイルが存在するかチェックする)
  // unlinkはファイルを削除する
}

// アイコンファイルのパーミッションを設定
function setIconFilePermission(string $path): void
{
  chmod($path, 0644);
  // chmodはPHPの組み込み関数(ファイルの権限（パーミッション）を変更)
  // 0644 所有者:読み書き、他:読み取り
}

// 特定ユーザーのアイコンファイルを全て削除
// 前回のアップロードでtempファイルが残っているかもしれないのでそれがあるか確認して削
// またはアイコンをデフォルトに戻す時に使用
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
  // file_exists はPHPの組み込み関数(ファイルが存在するかチェックする)
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
