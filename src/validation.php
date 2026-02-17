<?php

// フォームからの入力値を取得し、前後の空白を削除して返す
function getTrimmedPostValue(string $key): string
{
  // キーがあるかチェック
  // 前後の空白を削除
  return trim($_POST[$key] ?? '');
}

// ユーザー名
function isSafeInput(string $value): bool
{
  return preg_match('/[\r\n<>]/u', $value) !== 1;
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
  return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)[A-Za-z0-9\W]+$/', $password) === 1;
}