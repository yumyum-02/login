<?php
require_once './db.php';
session_start();
session_regenerate_id(); // 安全のためにセッションIDを毎回変える

require_once './validation.php'; //バリデーションの呼び出し