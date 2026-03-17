<?php

// 依存ファイルを読み込み
require_once __DIR__ . '/icon-file.php';
require_once __DIR__ . '/icon-db.php';

/**
 * 一時アイコンファイルを保存
 *
 * @param int $userId
 * @param array $file
 * @return string 保存した一時ファイル名
 */
function saveTempIcon(int $userId, array $file): string
{
  // MIMEタイプから拡張子を決定
  $mimeType = getImageMimeType($file['tmp_name']);
  $extension = getImageExtensionFromMime($mimeType);

  // ファイルパスを生成
  $tempPath = getIconFilePath($userId, $extension, true);

  // ディレクトリ確認
  ensureIconDirectoryExists(getIconDirectory());

  // 既存の一時ファイルを削除
  deleteTempIconFile($userId);

  // ファイルを移動
  if (!moveUploadedIconFile($file['tmp_name'], $tempPath)) {
    throw new RuntimeException('ファイルの保存に失敗しました');
  }

  // パーミッション設定
  setIconFilePermission($tempPath);

  // ファイル名を返す
  return basename($tempPath);
}

/**
 * 一時ファイルを本番ファイルに確定
 *
 * @param int $userId
 * @param string $tempFilename
 * @return string 本番ファイル名
 */
function confirmIcon(int $userId, string $tempFilename): string
{
  $tempPath = getIconDirectory() . $tempFilename;

  // 一時ファイルの存在確認
  if (!file_exists($tempPath)) {
    throw new RuntimeException('一時ファイルが見つかりません');
  }

  // 拡張子を取得
  $extension = pathinfo($tempFilename, PATHINFO_EXTENSION);

  // 既存の本番ファイルを削除
  deleteIconFile($userId);

  // 本番ファイルパスを生成
  $finalPath = getIconFilePath($userId, $extension, false);

  // リネーム
  if (!renameIconFile($tempPath, $finalPath)) {
    throw new RuntimeException('ファイルの確定に失敗しました');
  }

  // パーミッション設定
  setIconFilePermission($finalPath);

  return basename($finalPath);
}

/**
 * 一時アイコンファイルを削除
 *
 * @param int $userId
 * @return void
 */
function deleteTempIconFile(int $userId): void
{
  deleteAllIconFiles($userId, true);
}

/**
 * 本番アイコンファイルを削除
 *
 * @param int $userId
 * @return void
 */
function deleteIconFile(int $userId): void
{
  deleteAllIconFiles($userId, false);
}

/**
 * ユーザーのアイコンをDBに更新
 *
 * @param int $userId
 * @param string|null $filename
 * @return void
 */
function updateUserIcon(int $userId, ?string $filename): void
{
  updateUserIconInDb($userId, $filename);
}

/**
 * アイコンをデフォルトに戻す
 *
 * @param int $userId
 * @return void
 */
function resetIcon(int $userId): void
{
  deleteIconFile($userId);
  updateUserIcon($userId, null);
}
