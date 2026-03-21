<?php
require_once __DIR__ . '/validation.php'; // バリデーションの呼び出し

// ユーザー名用（詳細）
function getUserNameValidationErrors(string $name): array
{
	if (isEmpty($name)){
		return ['ユーザー名を入力してください。'];
	}

	$errors = [];
	if (!isUserNameFormat($name)) {
		$errors[] = '使用できない文字が含まれています。';
	}
	if (!isWithinLength($name, 3, 16)) {
		$errors[] = 'ユーザー名は3文字以上16文字以内で入力してください。';
	}
	return $errors;
}

// メールアドレス用（詳細）
function getMailValidationErrors(string $email): array
{
	if (isEmpty($email)) {
			return ['メールアドレスを入力してください。'];
	}

	$errors = [];
	if (!isEmailFormat($email)) {
			$errors[] = 'メールアドレスの形式が正しくありません。';
	}
	// メールアドレスはローカルパートが最大64文字 + ドメインが255文字 + ＠で 合計320文字 MAX
	if (!isWithinLength($email, null, 320)) {
			$errors[] = 'メールアドレスは320文字以内で入力してください。';
	}
	return $errors;
}

// メールアドレス用（簡易）
function getSimpleEmailErrors(string $email): array
{
	if (isEmpty($email)) {
		return ['メールアドレスを入力してください。'];
	}
	$errors = [];
	if (!isEmailFormat($email)) {
		$errors[] = 'メールアドレスの形式が正しくありません。';
	}
	if (!isWithinLength($email, null, 320)) {
		$errors[] = 'メールアドレスは320文字以内で入力してください。';
	}
	return $errors;
}

// パスワード用（詳細）
function getPasswordValidationErrors(string $password): array
{
	if (isEmpty($password)) {
			return ['パスワードを入力してください。'];
	}

	$errors = [];
	if (!isPasswordFormat($password)) {
			$errors[] = 'パスワードは半角英数字と記号で入力してください。';
	}
	if (!isWithinLength($password, 16, 64)) {
    $errors[] = 'パスワードは16文字以上64文字以内で入力してください。';
  }
	return $errors;
}

// パスワード用（簡易）
function getSimplePasswordErrors(string $password): array
{
	if (isEmpty($password)) {
		return ['パスワードを入力してください。'];
	}

	$errors = [];
	if (!isPasswordFormat($password)) {
		$errors[] = 'パスワードの形式が正しくありません。';
	}
	if (!isWithinLength($password, 16, 64)) {
		$errors[] = 'パスワードは16文字以上64文字以内で入力してください。';
	}
	return $errors;
}

// パスワード2重チェック
function getPasswordCheck(string $password, string $password_check): array
{
	if (isEmpty($password_check)) {
		return ['パスワード（確認用）を入力してください。'];
	}

	if ( $password !== $password_check) {
		return ['パスワードが一致していません'];
	}
	return [];
}

// 現在のパスワード検証（パスワード変更用）
/**
 * 現在のパスワードのバリデーションエラーを取得
 *
 * @param string $current_password 入力された現在のパスワード
 * @param int $user_id ユーザーID
 * @return array エラーメッセージの配列
 */
function getCurrentPasswordErrors(string $current_password, int $user_id): array
{
	if (isEmpty($current_password)) {
		return ['現在のパスワードを入力してください。'];
	}

	if (!isCurrentPasswordCorrect($current_password, $user_id)) {
		return ['現在のパスワードが正しくありません。'];
	}

	return [];
}

// アイコンのバリデーションエラーを取得
/**
 * アイコンのバリデーションエラーを取得
 *
 * @param array|null $file アップロードされたファイル（$_FILES['icon']）
 * @return array エラーメッセージの配列
 */
function getIconValidationErrors($file): array
{
	// 画像がアップロードされていない場合
	if (!isImageUploaded($file)) {
		return ['画像がアップロードされていません'];
	}

	// アップロードされたものが画像ファイルかチェック
	if (!isValidImageFormat($file)) {
		return ['PNG または JPEG 形式の画像をアップロードしてください'];
	}

	$errors = [];

	// アップロードエラーチェック
	if ($file['error'] !== UPLOAD_ERR_OK) {
		$errors[] = '画像のアップロードに失敗しました';
	}

	// ファイルサイズチェック（1MB以下）
	if (!isValidImageFileSize($file, 1024 * 1024)) {
		$errors[] = '容量は1MB以下の画像をアップロードしてください';
	}

	// MIMEタイプチェック（PNG または JPEG）
	if (!isValidImageMimeType($file, ['image/jpeg', 'image/png'])) {
		$errors[] = 'PNG または JPEG 形式の画像をアップロードしてください';
	}

	// 画像サイズチェック（400px × 400px以下）
	if (!isValidImageDimensions($file, 400, 400)) {
		$errors[] = '画像サイズは400px × 400px以下にしてください';
	}

	return $errors;
}