<?php
require '../src/bootstrap.php';

// セッションからエラーと入力値を取得
$errors = $_SESSION['errors'] ?? ['name' => [], 'email' => [],'password' => []];
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors'], $_SESSION['old_input']);

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

require_once '../src/template/regist_template.php';
