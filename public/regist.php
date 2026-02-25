<?php
require '../src/bootstrap.php';

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

require_once '../src/template/regist_template.php';
