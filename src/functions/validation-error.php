<?php
require_once __DIR__ . '/validation.php'; // バリデーションの呼び出し

// ユーザー名用（詳細）
function getUserNameValidationErrors($name){
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
function getMailValidationErrors($email) {
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
function getSimpleEmailErrors($email) {
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
function getPasswordValidationErrors($password) {
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
function getSimplePasswordErrors($password) {
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
function getPasswordCheck($password, $password_check){
	if (isEmpty($password_check)) {
		return ['パスワード（確認用）を入力してください。'];
	}

	if ( $password !== $password_check) {
		return ['パスワードが一致していません'];
	}
	return [];
}

/**
 * エラーがあれば登録処理を中止し、入力した内容をキープしたままフォームに戻る
 * エラーをセッションに保存してリダイレクト
 * @param array $errors エラー配列
 * @param array $oldInput 入力値（再表示用）
 * @param string $redirectUrl リダイレクト先URL
 */
function redirectWithErrors($errors, $oldInput, $redirectUrl) {
  $_SESSION['errors'] = $errors;
  $_SESSION['old_input'] = $oldInput;
	redirect($redirectUrl);
}