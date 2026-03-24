<?php

// 依存ファイルを読み込み
require_once __DIR__ . '/db.php';

/**
 * プレビュー用アイコンファイルを保存
 * @throws RuntimeException ファイルの保存に失敗した場合
 */
function saveTempIcon(int $userId, array $file): string
{
  // MIMEタイプから拡張子を決定
  // ステップ1:アップロードされたファイルのMIMEタイプを取得 → "image/jpeg" とか "image/png" が返る
  // ステップ2: MIMEタイプから拡張子を決定 → "jpg" とか "png" が返る
  $mimeType = getImageMimeType($file['tmp_name']);
  $extension = getImageExtensionFromMime($mimeType);

  // ファイルパスを生成（文字列を作るだけ）
  // true:プレビュー用ファイル（*_temp.*）、false:本番ファイル
  $tempPath = getIconFilePath($userId, $extension, true);

  // 既存のプレビュー用ファイルを削除
  deleteTempIconFile($userId);

  // 選択した画像をicon画像ファイルに移動させる
  if (!moveUploadedIconFile($file['tmp_name'], $tempPath)) {
    throw new RuntimeException('ファイルの保存に失敗しました');
  }
  /*
  // PHPが自動的に作成する配列
  $_FILES = [
      'icon' => [
          'name'     => 'my_photo.jpg',        // 元のファイル名
          'type'     => 'image/jpeg',          // MIMEタイプ
          'tmp_name' => '/tmp/phpYhfR3F',      // 一時ファイルパス
          'error'    => 0,                     // エラーコード
          'size'     => 245678                 // ファイルサイズ
      ]
  ];
  */

  // パーミッション設定
  setIconFilePermission($tempPath);

  // ファイル名を返す
  return basename($tempPath);
}

/**
 * プレビュー用ファイルを本番ファイルに確定
 * @throws RuntimeException プレビュー用ファイルが見つからない、または確定に失敗した場合
 */
function confirmIcon(int $userId, string $tempFilename): string
{
  $tempPath = getIconDirectory() . $tempFilename;

  // プレビュー用ファイルの存在確認
  if (!file_exists($tempPath)) {
    throw new RuntimeException('プレビュー用ファイルが見つかりません');
  }

  // 拡張子を取得
  // pathinfo() PHPの組み込み関数で、ファイルパスから情報を抽出
  // PATHINFO_EXTENSION を指定すると拡張子だけ取得
  $extension = pathinfo($tempFilename, PATHINFO_EXTENSION);

  // 既存の本番ファイルを削除
  // 常に最新の画像ファイルだけにする（古い画像ファイルを残さない 容量）
  deleteIconFile($userId);

  // 本番ファイルパスを生成
  $finalPath = getIconFilePath($userId, $extension, false);

  // リネーム
  // 123_temp.jpg → 123.jpg にリネーム
  if (!renameIconFile($tempPath, $finalPath)) {
    throw new RuntimeException('ファイルの確定に失敗しました');
  }

  // パーミッション設定 0644
  setIconFilePermission($finalPath);

  // '/var/www/login/public/image/icon/123.jpg' から '123.jpg'を返す
  return basename($finalPath);
}

// プレビュー用アイコンファイル（*_temp.*）を削除
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
  // 特定ユーザーのアイコンファイルを全て削除
  deleteIconFile($userId);
  // DBのiconデータをnullにすることでデフォルトアイコンを表示させる
  updateUserIcon($userId, null);
}
