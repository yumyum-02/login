<?php
require_once __DIR__ . '/validation.php'; // バリデーションの呼び出し

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