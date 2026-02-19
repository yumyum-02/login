<?php

// フォームからの入力値を取得し、前後の空白を削除して返す
function getTrimmedPostValue(string $key): string
{
  // キーがあるかチェック
  // 前後の空白を削除
  return trim($_POST[$key] ?? '');
}

// ユーザー名
function isUserNameFormat(string $value): bool
{
  return  preg_match('/^[a-zA-Z0-9\x{3041}-\x{3096}\x{30A1}-\x{30FC}\x{4E00}-\x{9FFF
  }\x{3400}-\x{4DBF}]+$/u', $value) !== 1;
}

// メールアドレスの形式が正しいか（filter_var でチェック）
function isEmailFormat(string $email): bool
{
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// 長さのバリデーション
function isWithinLength(string $value, ?int $minLength, ?int $maxLength): bool
{
  $length = mb_strlen($value);

  if ($minLength !== null && $length < $minLength) {
    return false;
  }

  if ($maxLength !== null && $length > $maxLength) {
    return false;
  }

  return true;
}

// パスワードの形式：半角英数字と記号（スペース不可）
function isPasswordFormat(string $password): bool
{
  return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]) (?=.*[!@#$%^&*()\-_+=])[a-zA-Z0-9_-]+$/', $password) === 1;
}