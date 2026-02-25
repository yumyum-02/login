<?php

class AuthController extends Controller
{
  /**
   * ログイン画面を表示
   */
  public function showLogin()
  {
    // ログアウト成功メッセージ
    $success_logout_msg = $_GET['msg'] ?? '';

    // セッションメッセージ
    $login_msg = '';
    if (isset($_SESSION['msg'])) {
      $login_msg = $_SESSION['msg'];
      unset($_SESSION['msg']);
    }

    // ログインエラーメッセージ
    $err_msg = '';
    if (isset($_SESSION['login_error_msg'])) {
      $err_msg = $_SESSION['login_error_msg'];
      unset($_SESSION['login_error_msg']);
    }

    // バリデーションエラー
    $errors = [];
    if (isset($_SESSION['login_errors'])) {
      $errors = $_SESSION['login_errors'];
      unset($_SESSION['login_errors']);
    }

    // 入力値の保持
    $email = '';
    if (isset($_SESSION['login_email'])) {
      $email = $_SESSION['login_email'];
      unset($_SESSION['login_email']);
    }

    // CSRFトークンを生成
    $csrf_token = generateCsrfToken();

    $this->view('login_template', compact('success_logout_msg', 'login_msg', 'err_msg', 'errors', 'email', 'csrf_token'));
  }

  /**
   * ログイン処理
   */
  public function login()
  {
    // POST送信でない場合はログイン画面へ
    if (!isset($_POST['login_btn'])) {
      $this->redirect('/login');
    }

    // CSRFトークン検証
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
      exit('不正なリクエストです');
    }

    // バリデーション
    $email = getTrimmedPostValue('email');
    $password = $_POST['password'] ?? '';
    $errors = [
      'email' => [],
      'password' => []
    ];

    // メールアドレスのバリデーション
    if (isEmpty($email)) {
      $errors['email'][] = 'メールアドレスを入力してください。';
    } else {
      if (!isEmailFormat($email)) {
        $errors['email'][] = 'メールアドレスの形式が正しくありません。';
      }
      if (!isWithinLength($email, null, 320)) {
        $errors['email'][] = 'メールアドレスは320文字以内で入力してください。';
      }
    }

    // パスワードのバリデーション
    if (isEmpty($password)) {
      $errors['password'][] = 'パスワードを入力してください。';
    } else {
      if (!isPasswordFormat($password)) {
        $errors['password'][] = 'パスワードの形式が正しくありません。';
      }
      if (!isWithinLength($password, 8, 100)) {
        $errors['password'][] = 'パスワードは8文字以上100文字以内で入力してください。';
      }
    }

    // エラーチェック
    $hasErrors = !empty($errors['email']) || !empty($errors['password']);
    if ($hasErrors) {
      $_SESSION['login_errors'] = $errors;
      $_SESSION['login_email'] = $email;
      $this->redirect('/login');
    }

    // DBのデータと照会
    try {
      $user_info = User::findByEmailForLogin($email);

      if (count($user_info) && password_verify($password, $user_info[0]['password'])) {
        $_SESSION['user'] = array(
          'id'    => $user_info[0]['id'],
          'name'  => $user_info[0]['name'],
          'email' => $user_info[0]['email'],
        );

        // ログイン成功時はCSRFトークンを破棄
        destroyCsrfToken();

        $this->redirect('/');
      } else {
        $_SESSION['login_error_msg'] = 'ログイン情報に誤りがあります。';
        $this->redirect('/login');
      }
    } catch (PDOException $e) {
      echo '接続失敗' . $e->getMessage();
      exit();
    }
  }

  /**
   * 登録画面を表示
   */
  public function showRegister()
  {
    // CSRFトークンを生成
    $csrf_token = generateCsrfToken();

    $this->view('regist_template', compact('csrf_token'));
  }

  /**
   * ユーザー登録処理
   */
  public function register()
  {
    // CSRFトークン検証
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
      exit('不正なリクエストです');
    }

    $name = getTrimmedPostValue('name');
    $email = getTrimmedPostValue('email');
    $password = $_POST['password'] ?? '';

    // バリデーション
    $errors = [
      'name' => [],
      'email' => [],
      'password' => []
    ];

    // ユーザー名のバリデーション
    if (isEmpty($name)) {
      $errors['name'][] = 'ユーザー名を入力してください。';
    } else {
      if (!isUserNameFormat($name)) {
        $errors['name'][] = '使用できない文字が含まれています。';
      }
      if (!isWithinLength($name, 3, 16)) {
        $errors['name'][] = 'ユーザー名は3文字以上16文字以内で入力してください。';
      }
    }

    // メールアドレスのバリデーション
    if (isEmpty($email)) {
      $errors['email'][] = 'メールアドレスを入力してください。';
    } else {
      if (!isEmailFormat($email)) {
        $errors['email'][] = 'メールアドレスの形式が正しくありません。';
      }
      if (!isWithinLength($email, null, 320)) {
        $errors['email'][] = 'メールアドレスは320文字以内で入力してください。';
      }
    }

    // パスワードのバリデーション
    if (isEmpty($password)) {
      $errors['password'][] = 'パスワードを入力してください。';
    } else {
      if (!isPasswordFormat($password)) {
        $errors['password'][] = 'パスワードは半角英数字と記号で入力してください。';
      }
      if (!isWithinLength($password, 16, 64)) {
        $errors['password'][] = 'パスワードは16文字以上64文字以内で入力してください。';
      }
    }

    // エラーチェック
    $hasErrors = !empty($errors['name']) || !empty($errors['email']) || !empty($errors['password']);
    if ($hasErrors) {
      $csrf_token = generateCsrfToken();
      $this->view('regist_template', compact('errors', 'name', 'email', 'csrf_token'));
      exit();
    }

    // メールアドレスを小文字に変換
    $email = mb_strtolower($email, 'UTF-8');

    // パスワードのハッシュ化
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
      // すでに登録されているIDかどうか確認
      $user_info = User::findByEmailForRegister($email);

      if (count($user_info)) {
        $errors[] = 'そのメールアドレスはすでに使用されています。';
        $csrf_token = generateCsrfToken();
        $this->view('regist_template', compact('errors', 'name', 'email', 'csrf_token'));
        exit();
      }

      // ユーザー登録
      $user_id = User::create($name, $email, $password_hash);

      if ($user_id === false) {
        echo '登録に失敗しました。もう一度お試しください。';
        exit();
      }

      // 登録成功時はCSRFトークンを破棄
      destroyCsrfToken();

      $_SESSION['msg'] = "会員登録が完了しました。ログインしてください。";
      $this->redirect('/login');
    } catch (PDOException $e) {
      echo '接続失敗' . $e->getMessage();
      exit();
    }
  }

  /**
   * ログアウト処理
   */
  public function logout()
  {
    // CSRFトークン検証
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
      exit('不正な投稿です');
    }

    // ログアウト実行
    executeLogout();
  }
}
