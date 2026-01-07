<?
// 不正リクエストチェック
// トークンがセッションに存在しない、または一致しない場合は処理を中止
if (empty($_SESSION['regist_token']) || ($_SESSION['regist_token'] !== $_POST['regist_token'])) exit('不正なリクエストです');
// トークンの破棄（1回限り有効にするため）
if (isset($_SESSION['regist_token'])) unset($_SESSION['regist_token']);
if (isset($_POST['regist_token'])) unset($_POST['regist_token']);
