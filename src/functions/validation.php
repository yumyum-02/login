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
  return  preg_match('/^[a-zA-Z0-9 \x{3041}-\x{3096}\x{30A1}-\x{30FC}\x{4E00}-\x{9FFF}\x{3400}-\x{4DBF}]+$/u', $value) === 1;
}

// メールアドレスの形式が正しいか（filter_var でチェック）
function isEmailFormat(string $email): bool
{
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// 長さのバリデーション
function isWithinLength(string $value, ?int $minLength, ?int $maxLength): bool
{
  // mb_strlenは日本語などのマルチバイト文字に対応した文字数カウント　日本語でも英語でも1文字=1カウント
  $length = mb_strlen($value);

  if ($minLength !== null && $length < $minLength) {
    return false;
  }

  if ($maxLength !== null && $length > $maxLength) {
    return false;
  }

  return true;
}

// パスワードの形式
function isPasswordFormat(string $password): bool
{
  return preg_match('/^[a-zA-Z0-9!@#$%^&*()\-_+=]+$/', $password) === 1;
}

/**
 * 現在のパスワードが正しいかチェック
 *
 * @param string $current_password 入力された現在のパスワード
 * @param int $user_id ユーザーID
 * @return bool パスワードが一致すればtrue、一致しなければfalse
 */
function isCurrentPasswordCorrect(string $current_password, int $user_id): bool
{
	// DBからユーザーのパスワードハッシュを取得
	$pdo = connectDb();
	$stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
	$stmt->execute([':id' => $user_id]);
	$user = $stmt->fetch();

	// ユーザーが存在しない、またはパスワードが一致しない
	if (!$user || !password_verify($current_password, $user['password'])) {
		return false;
	}

	return true;
}

// 空欄チェック
function isEmpty(string $value): bool
{
  return $value === '';
}