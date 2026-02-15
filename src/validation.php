<?php

// フォームからの入力値を取得し、前後の空白を削除して返す
function getPostValueTrim(string $key): string
{
  // キーがあるかまずチェック
  $value = $_POST[$key] ?? '';
  // 前後の空白を削除
  return trim($value);
}

// ユーザー名
function isValidateUserName(string $name): bool
{
  return preg_match('/^[a-zA-Z0-9]+$/', $name) === 1;
}

// ユーザー名の長さが DB 制約（255文字）以内か
function isValidateUserNameLength(string $name): bool
{
  return mb_strlen($name) <= 255;
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

// パスワードの形式：半角英数字と記号（スペース不可）
function isValidatePasswordFormat(string $password): bool
{
  return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)[A-Za-z0-9\W]$/', $password) === 1;
}

/** パスワードが10文字以上64文字未満（10〜63文字）か */
function isValidatePasswordLength(string $password): bool
{
  $len = mb_strlen($password);
  return $len >= 10 && $len < 64;
}