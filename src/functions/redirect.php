<?php

function redirect(string $redirectUrl): never
{
	header("Location: $redirectUrl");
	exit;
}

/**
 * ログイン認証チェック（未ログインの場合はログインページにリダイレクト）
 * @deprecated AuthUser::requireLogin() を使用してください
 */
function requireLogin(string $loginPath = './login.php'): void
{
	AuthUser::requireLogin($loginPath);
}

// エラーをセッションに保存してリダイレクト（入力値も保持）
function redirectWithErrors(array $errors, array $oldInput, string $redirectUrl): never
{
	$_SESSION['errors'] = $errors;
	$_SESSION['old_input'] = $oldInput;
	redirect($redirectUrl);
}