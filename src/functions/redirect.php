<?php

function redirect(string $redirectUrl): never
{
	header("Location: $redirectUrl");
	exit;
}

/**
 * ログイン認証チェック
 * 未ログインの場合はログインページにリダイレクト
 *
 * @param string $loginPath ログインページへのパス
 * @return void
 * @deprecated AuthUser::requireLogin() を使用してください
 */
function requireLogin(string $loginPath = './login.php'): void
{
	AuthUser::requireLogin($loginPath);
}

/**
 * エラーがあれば登録処理を中止し、入力した内容をキープしたままフォームに戻る
 * エラーをセッションに保存してリダイレクト
 * @param array $errors エラー配列
 * @param array $oldInput 入力値（再表示用）
 * @param string $redirectUrl リダイレクト先URL
 */
function redirectWithErrors(array $errors, array $oldInput, string $redirectUrl): never
{
	$_SESSION['errors'] = $errors;
	$_SESSION['old_input'] = $oldInput;
	redirect($redirectUrl);
}