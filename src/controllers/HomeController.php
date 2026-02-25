<?php

class HomeController extends Controller
{
  /**
   * メイン画面を表示
   */
  public function index()
  {
    // ログイン認証チェック
    if (!isset($_SESSION['user'])) {
      $_SESSION['msg'] = "ログインしてください。";
      $this->redirect('/login');
    }

    // CSRFトークンを生成
    $csrf_token = generateCsrfToken();

    $this->view('index_template', compact('csrf_token'));
  }
}
