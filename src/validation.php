<?php

// フォームからの入力値を取得し、前後の空白を削除して返す
function getTrimmedPostValue(string $key): string
{
  // キーがあるかチェック
  // 前後の空白を削除
  return trim($_POST[$key] ?? '');
}

// ユーザー名
function isUserName(string $name): bool
{
  return preg_match('/^[a-zA-Z0-9]+$/', $name) === 1;
}

// ユーザー名の長さが DB 制約（255文字）以内か
function isUserNameLength(string $name): bool
{
  return mb_strlen($name) <= 255;
}

// メールアドレスの形式が正しいか（filter_var でチェック）
function isEmailFormat(string $email): bool
{
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// 長さのバリデーション
function isWithinLength(string $value, int $maxLength): bool
{
    return mb_strlen($value) <= $maxLength;
}

// パスワードの形式：半角英数字と記号（スペース不可）
function isPasswordFormat(string $password): bool
{
  return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)[A-Za-z0-9\W]+$/', $password) === 1;
}

/** パスワードが10文字以上64文字未満（10〜63文字）か */
function isPasswordLength(string $password): bool
{
  $len = mb_strlen($password);
  return $len >= 10 && $len < 64;
}