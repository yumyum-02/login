<?php

class Controller
{
  /**
   * ビューをレンダリング
   */
  protected function view($viewName, $data = [])
  {
    // データを変数として展開
    extract($data);

    // ビューファイルを読み込み
    $viewPath = __DIR__ . '/../views/' . $viewName . '.php';

    if (file_exists($viewPath)) {
      require $viewPath;
    } else {
      die("View not found: $viewPath");
    }
  }

  /**
   * リダイレクト
   */
  protected function redirect($path)
  {
    header('Location: ' . $path);
    exit();
  }

  /**
   * JSONレスポンスを返す
   */
  protected function json($data, $statusCode = 200)
  {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
  }
}
