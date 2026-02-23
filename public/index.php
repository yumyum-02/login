<?php
require_once '../src/bootstrap.php';

// sessionにuser情報がなければログイン画面へリダイレクト
if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

require_once '../src/functions/logout.php';
require_once '../src/template/index_template.php';
