<?php
require_once './bootstrap.php';

// sessionにuser情報がなければログイン画面へリダイレクト
if (!isset($_SESSION['user'])) {
  $_SESSION['msg'] = "ログインしてください。";
  header('Location: ./login.php');
  exit();
}

require_once './logout.php';
require_once './template/index_template.php';
