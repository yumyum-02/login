<?php

// フォームからの入力値を取得し、前後の空白を削除して返す
function getPostValueTrim(string $key): string
{
  // キーがあるかまずチェック
  $value = $_POST[$key] ?? '';
  // 前後の空白を削除
  return trim($value);
}

// メールアドレスの形式が正しいか（filter_var でチェック）
function isValidateEmailFormat(string $email): bool
{
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// メールアドレスの長さが DB 制約（255文字）以内か
function isValidateEmailLength(string $email): bool
{
  return mb_strlen($email) <= 255;
}