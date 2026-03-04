<?php
require_once __DIR__ . '/validation.php'; // バリデーションの呼び出し

// ユーザー名用（詳細）
function validateUserName($name){
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
function validateMail($email) {
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

/**
 * エラーがあれば登録処理を中止し、入力した内容をキープしたままフォームに戻る
 * エラーをセッションに保存してリダイレクト
 * @param array $errors エラー配列
 * @param array $oldInput 入力値（再表示用）
 * @param string $redirectUrl リダイレクト先URL
 */
function redirect($redirectUrl) {
	header("Location: $redirectUrl");
	exit;
}
function redirectWithErrors($errors, $oldInput, $redirectUrl) {
  $_SESSION['errors'] = $errors;
  $_SESSION['old_input'] = $oldInput;
	redirect($redirectUrl);
}