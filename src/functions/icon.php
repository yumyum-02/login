<?php

// 依存ファイルを読み込み
require_once __DIR__ . '/db.php';

/**
 * 一時アイコンファイルを保存
 * @throws RuntimeException ファイルの保存に失敗した場合
 */
function saveTempIcon(int $userId, array $file): string
{
  // MIMEタイプから拡張子を決定
  $mimeType = getImageMimeType($file['tmp_name']);
  $extension = getImageExtensionFromMime($mimeType);

  // ファイルパスを生成
  $tempPath = getIconFilePath($userId, $extension, true);

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
 * @throws RuntimeException 一時ファイルが見つからない、または確定に失敗した場合
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

// 一時アイコンファイルを削除
function deleteTempIconFile(int $userId): void
{
  deleteAllIconFiles($userId, true);
}

// 本番アイコンファイルを削除
function deleteIconFile(int $userId): void
{
  deleteAllIconFiles($userId, false);
}

// アイコンをデフォルトに戻す
function resetIcon(int $userId): void
{
  deleteIconFile($userId);
  updateUserIcon($userId, null);
}
