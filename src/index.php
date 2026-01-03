<?php
require_once './bootstrap.php';

if (!isset($_SESSION['user'])) {
  $msg = urlencode("ログインしてください。");
  header('Location: ./login.php?msg=' . $msg);
  exit();
}

require_once './logout.php';
require_once './template/index_template.php';
