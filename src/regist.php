<?php
require './bootstrap.php';

// CSRFトークンを生成
$csrf_token = generateCsrfToken();

require_once './template/regist_template.php';
