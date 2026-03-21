<?php
// 画像アップロードデバッグ
session_start();

echo "=== セッション情報 ===\n";
echo "User ID: " . ($_SESSION['user']['id'] ?? 'なし') . "\n";
echo "temp_icon: " . ($_SESSION['temp_icon'] ?? 'なし') . "\n";
echo "errors: " . print_r($_SESSION['errors'] ?? [], true) . "\n";

echo "\n=== アップロードディレクトリ ===\n";
$iconDir = __DIR__ . '/public/image/icon/';
$files = scandir($iconDir);
foreach ($files as $file) {
  if ($file !== '.' && $file !== '..') {
    echo "$file\n";
  }
}
