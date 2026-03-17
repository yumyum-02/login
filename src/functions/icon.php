<?php

/**
 * 一時アイコンファイルを保存
 *
 * @param int $userId ユーザーID
 * @param array $file アップロードされたファイル（$_FILES['icon']）
 * @return string 保存した一時ファイル名
 */
function saveTempIcon(int $userId, array $file): string
{
  // MIMEタイプから拡張子を決定
  $imageInfo = getimagesize($file['tmp_name']);
  $mimeType = $imageInfo['mime'];

  $extension = match($mimeType) {
    'image/png' => 'png',
    'image/jpeg' => 'jpg',
    default => throw new RuntimeException('サポートされていない画像形式です')
  };

  // 一時ファイル名を生成
  $tempFilename = "{$userId}_temp.{$extension}";
  $iconDir = dirname(__DIR__, 2) . '/public/image/icon/';
  $tempPath = $iconDir . $tempFilename;

  // 保存先ディレクトリが存在しなければ作成
  if (!is_dir($iconDir)) {
    mkdir($iconDir, 0755, true);
  }

  // 既存の一時ファイルがあれば削除（拡張子が異なる可能性があるため）
  deleteTempIconFile($userId);

  // 一時ファイルとして保存
  if (!move_uploaded_file($file['tmp_name'], $tempPath)) {
    throw new RuntimeException('ファイルの保存に失敗しました');
  }

  // パーミッション設定（実行不可にする）
  chmod($tempPath, 0644);

  return $tempFilename;
}

/**
 * 一時ファイルを本番ファイルに確定
 *
 * @param int $userId ユーザーID
 * @param string $tempFilename 一時ファイル名
 * @return string 本番ファイル名
 */
function confirmIcon(int $userId, string $tempFilename): string
{
  $iconDir = dirname(__DIR__, 2) . '/public/image/icon/';
  $tempPath = $iconDir . $tempFilename;

  // 一時ファイルが存在しなければエラー
  if (!file_exists($tempPath)) {
    throw new RuntimeException('一時ファイルが見つかりません');
  }

  // 拡張子を取得
  $extension = pathinfo($tempFilename, PATHINFO_EXTENSION);

  // 既存の本番ファイルを削除
  deleteIconFile($userId);

  // 本番ファイル名を生成
  $filename = "{$userId}.{$extension}";
  $finalPath = $iconDir . $filename;

  // 一時ファイルを本番ファイルにリネーム
  if (!rename($tempPath, $finalPath)) {
    throw new RuntimeException('ファイルの確定に失敗しました');
  }

  // パーミッション設定（実行不可にする）
  chmod($finalPath, 0644);

  return $filename;
}

/**
 * 一時アイコンファイルを削除
 *
 * @param int $userId ユーザーID
 */
function deleteTempIconFile(int $userId): void
{
  $iconDir = dirname(__DIR__, 2) . '/public/image/icon/';
  $extensions = ['jpg', 'jpeg', 'png'];

  foreach ($extensions as $ext) {
    $tempPath = $iconDir . "{$userId}_temp.{$ext}";

    // file_exists 指定したファイルやディレクトリが存在するかチェック
    if (file_exists($tempPath)) {
      unlink($tempPath); //ファイルが存在する時に削除
      // unlinkはディレクトリは削除できない　ディレクトリ削除はrmdir()
    }
  }
}

/**
 * 本番アイコンファイルを削除
 *
 * @param int $userId ユーザーID
 */
function deleteIconFile(int $userId): void
{
  $iconDir = dirname(__DIR__, 2) . '/public/image/icon/';
  $extensions = ['jpg', 'jpeg', 'png'];

  foreach ($extensions as $ext) {
    $path = $iconDir . "{$userId}.{$ext}";
    if (file_exists($path)) {
      unlink($path);
    }
  }
}

/**
 * ユーザーのアイコンファイル名をDBに更新
 *
 * @param int $userId ユーザーID
 * @param string|null $filename ファイル名（NULLの場合はデフォルトに戻す）
 */
function updateUserIcon(int $userId, ?string $filename): void
{
  $pdo = connectDb();
  $stmt = $pdo->prepare('UPDATE users SET icon = :icon WHERE id = :id');
  $stmt->execute([
    ':icon' => $filename,
    ':id' => $userId
  ]);
}

/**
 * アイコンをデフォルトに戻す
 *
 * @param int $userId ユーザーID
 */
function resetIcon(int $userId): void
{
  // 本番ファイルを削除
  deleteIconFile($userId);

  // DBをNULLに更新
  updateUserIcon($userId, null);
}
