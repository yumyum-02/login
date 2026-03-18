<?php

// 認証ユーザークラス
// ログイン状態の管理とユーザー情報の取得を担当
class AuthUser
{
    //ログインしているかどうかを確認
    public static function isLogin(): bool
    {
        return isset($_SESSION['user']);
    }

    // ログインユーザー情報を取得
    public static function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    // 未ログインの場合はログインページにリダイレクト
    public static function requireLogin(string $loginPath = './login.php'): void
    {
        if (!self::isLogin()) {
            $_SESSION['msg'] = "ログインしてください。";
            redirect($loginPath);
        }
    }

    // ログイン処理 ユーザー情報をセッションに保存
    public static function login(array $user): void
    {
        $_SESSION['user'] = $user;
    }

    // ログアウト処理 セッションからユーザー情報を削除
    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    // ログインユーザーのIDを取得
    public static function getUserId(): ?int
    {
        $user = self::getUser();
        return $user['id'] ?? null;
    }

    // ログインユーザーのメールアドレスを取得
    public static function getEmail(): ?string
    {
        $user = self::getUser();
        return $user['email'] ?? null;
    }

    // ログインユーザーの名前を取得
    public static function getName(): ?string
    {
        $user = self::getUser();
        return $user['name'] ?? null;
    }

    //  ログインユーザーが管理者かどうかを確認
    public static function isAdmin(): bool
    {
        $user = self::getUser();
        return isset($user['is_admin']) && $user['is_admin'] == 1;
    }
}
