<?php

class AdminController extends Controller
{
  /**
   * 管理画面を表示
   */
  public function index()
  {
    // ログイン認証チェック
    if (!isset($_SESSION['user'])) {
      $_SESSION['msg'] = "ログインしてください。";
      $this->redirect('/login');
    }

    $users_info = User::all();

    // セッションメッセージがあればGETパラメータに移す
    if (isset($_SESSION['msg'])) {
      $_GET['msg'] = $_SESSION['msg'];
      unset($_SESSION['msg']);
    }

    // CSRFトークンを生成
    $csrf_token = generateCsrfToken();

    $this->view('admin_template', compact('users_info', 'csrf_token'));
  }

  /**
   * ユーザー削除処理
   */
  public function deleteUser()
  {
    // ログイン認証チェック
    if (!isset($_SESSION['user'])) {
      $this->redirect('/login');
    }

    // CSRFトークン検証
    if (!verifyCsrfToken($_GET['token'] ?? '')) {
      exit('不正なリクエストです');
    }

    // ユーザーID取得
    $user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$user_id) {
      exit('無効なIDです');
    }

    // 削除処理
    try {
      // ログイン中のユーザーは削除不可
      if (isset($_SESSION['user']['id']) && $user_id === (int)$_SESSION['user']['id']) {
        $_SESSION['msg'] = 'ログイン中のユーザー情報は削除できません';
        $this->redirect('/admin');
      }

      // 削除実行
      User::delete($user_id);

      $_SESSION['msg'] = 'ユーザーを削除しました';
    } catch (PDOException $e) {
      $_SESSION['msg'] = '削除に失敗しました';
    }

    // CSRFトークンを破棄
    destroyCsrfToken();

    $this->redirect('/admin');
  }
}
