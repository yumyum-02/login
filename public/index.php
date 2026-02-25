<?php
// Bootstrap
require_once __DIR__ . '/../src/bootstrap.php';

// Coreクラスを読み込み
require_once __DIR__ . '/../src/core/Router.php';
require_once __DIR__ . '/../src/core/Controller.php';

// Controllerを読み込み
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/HomeController.php';
require_once __DIR__ . '/../src/controllers/AdminController.php';

// Modelを読み込み
require_once __DIR__ . '/../src/models/User.php';

// ルーターを初期化
$router = new Router();

// ルート定義
// 認証系
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->post('/logout', 'AuthController', 'logout');

// メイン画面
$router->get('/', 'HomeController', 'index');

// 管理画面
$router->get('/admin', 'AdminController', 'index');
$router->get('/admin/delete', 'AdminController', 'deleteUser');

// リクエストをディスパッチ
$router->dispatch();
