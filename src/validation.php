<?php

// string は「文字列型」という意味で、「$email は文字列型ですよ」という宣言 :boolは「戻り値は真偽値ですよ」という宣言
function validationLoginMail(string $email): bool
{
  // 前後の空白を削除
  $email = trim($email);

  // 空文字チェック
  if ($email === ''){
    return false;
  }

  // メールアドレス形式チェック
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
    return false;
  }

  // 長さチェック（DB が VARCHAR(255) を想定）
  if (mb_strlen($email) > 255) {
    return false;
  }

  return true;
}