<?php
require_once __DIR__ . '/functions/db.php';
session_start();
session_regenerate_id(); // 安全のためにセッションIDを毎回変える

require_once __DIR__ . '/functions/csrf.php'; // CSRF対策の呼び出し
require_once __DIR__ . '/functions/validation.php'; // バリデーションの呼び出し
require_once __DIR__ . '/functions/sanitize.php'; // XSS対策のエスケープ関数
require_once __DIR__ . '/functions/logout.php'; // ログアウト関数