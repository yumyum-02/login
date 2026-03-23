<?php

// フォームからの入力値を取得し、前後の空白を削除して返す
function getTrimmedPostValue(string $key): string
{
  // キーがあるかチェック
  // 前後の空白を削除
  return trim($_POST[$key] ?? '');
}

// 空欄チェック
function isEmpty(string $value): bool
{
  return $value === '';
}

// ユーザー名
function isUserNameFormat(string $value): bool
{
  return  preg_match('/^[a-zA-Z0-9 \x{3041}-\x{3096}\x{30A1}-\x{30FC}\x{4E00}-\x{9FFF}\x{3400}-\x{4DBF}]+$/u', $value) === 1;
  /*
  半角英字: a-z, A-Z
  半角数字: 0-9
  半角スペース:
  ひらがな: あ-ん
  カタカナ: ア-ン、長音記号 ー
  漢字:
  */
}

// メールアドレスの形式が正しいか（filter_var でチェック）
function isEmailFormat(string $email): bool
{
  return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  /*
  RFC 5321準拠
  @ が1つだけあるか
  @の前後が正しいか
  ドットの位置が適切か
  ドットが連続していないか など世界共通のルールに基づく
  実在するメールアドレスかはチェックしていない
  */
}

// 長さのバリデーション
function isWithinLength(string $value, ?int $minLength, ?int $maxLength): bool
{
  // mb_strlenは日本語などのマルチバイト文字に対応した文字数カウント　日本語でも英語でも1文字=1カウント
  $length = mb_strlen($value);

  if ($minLength !== null && $length < $minLength) {
    return false;
  }

  if ($maxLength !== null && $length > $maxLength) {
    return false;
  }

  return true;
}

// パスワードの形式
function isPasswordFormat(string $password): bool
{
  return preg_match('/^[a-zA-Z0-9!@#$%^&*()\-_+=]+$/', $password) === 1;
  //英数字とよくある記号群
}

// 現在のパスワードが正しいかチェック
function isCurrentPasswordCorrect(string $current_password, int $user_id): bool
{
	// DBからユーザーのパスワードハッシュを取得
	$pdo = connectDb();
	$stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id');
	$stmt->execute([':id' => $user_id]);
	$user = $stmt->fetch();

	// ユーザーが存在しない、またはパスワードが一致しない
  // password_verifyはPHP標準関数 ハッシュ化されたパスワードと平文パスワードが一致するか検証
	if (!$user || !password_verify($current_password, $user['password'])) {
		return false;
	}

	return true;
}

// 画像がアップロードされているかチェック
function isImageUploaded(?array $file): bool
{
  return isset($file) && $file['error'] !== UPLOAD_ERR_NO_FILE;
}

// アップロードされたファイルが本当に画像かチェック
function isValidImageFormat(array $file): bool
{
  $imageInfo = getimagesize($file['tmp_name']);

  if ($imageInfo === false){
    return false;
  }

  // 許可された画像タイプ（JPEG, PNG）
  $allowedType = [IMAGETYPE_JPEG, IMAGETYPE_PNG];
  return in_array($imageInfo[2], $allowedType, true);

  /*
  getimagesize()の戻り値：
  $imageInfo = [
    0 => 800,              // 画像の幅（ピクセル）
    1 => 600,              // 画像の高さ（ピクセル）
    2 => IMAGETYPE_JPEG,   // 画像タイプ（定数） これをみたいので[2]
    3 => 'width="800" height="600"',
    'mime' => 'image/jpeg'
  ];
  */
}

// ファイルサイズが指定されたバイト数以下かチェック
function isValidImageFileSize(array $file, int $maxSizeInBytes): bool
{
  return $file['size'] <= $maxSizeInBytes;
  // maxsizeはvalidation-error.phpの方で値を入れている
}

// 画像の幅と高さが指定サイズ以下かチェック
function isValidImageDimensions(array $file, int $maxWidth, int $maxHeight): bool
{
  $imageInfo = getimagesize($file['tmp_name']);

  // もし $file['tmp_name'] が画像じゃない場合、$imageInfo は false になる
  if ($imageInfo === false) {
    return false;
  }

  $width = $imageInfo[0];
  $height = $imageInfo[1];

  return $width <= $maxWidth && $height <= $maxHeight;
}

// MIMEタイプが許可されたタイプかチェック
function isValidImageMimeType(array $file, array $allowedMimeTypes): bool
{
  $imageInfo = getimagesize($file['tmp_name']);

  if ($imageInfo === false) {
    return false;
  }

  $mimeType = $imageInfo['mime'];

  return in_array($mimeType, $allowedMimeTypes, true);
}