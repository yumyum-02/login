<?php

class Router
{
  private $routes = [];

  /**
   * ルートを登録
   */
  public function add($method, $path, $controller, $action)
  {
    $this->routes[] = [
      'method' => strtoupper($method),
      'path' => $path,
      'controller' => $controller,
      'action' => $action
    ];
  }

  /**
   * GETルートを登録
   */
  public function get($path, $controller, $action)
  {
    $this->add('GET', $path, $controller, $action);
  }

  /**
   * POSTルートを登録
   */
  public function post($path, $controller, $action)
  {
    $this->add('POST', $path, $controller, $action);
  }

  /**
   * リクエストをディスパッチ
   */
  public function dispatch()
  {
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    foreach ($this->routes as $route) {
      if ($route['method'] === $requestMethod && $route['path'] === $requestPath) {
        $controllerName = $route['controller'];
        $actionName = $route['action'];

        // コントローラーをインスタンス化
        $controller = new $controllerName();

        // アクションを実行
        return $controller->$actionName();
      }
    }

    // ルートが見つからない場合
    http_response_code(404);
    echo '404 Not Found';
  }
}
