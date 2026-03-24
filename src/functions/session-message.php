<?php

// セッションメッセージを取得（削除はしない）
function getSessionMessage(): ?string
{
  return $_SESSION['msg'] ?? null;
}

// URLパラメータからメッセージを取得
function getUrlMessage(): ?string
{
  return $_GET['msg'] ?? null;
}

// セッションメッセージを削除
function clearSessionMessage(): void
{
  unset($_SESSION['msg']);
}

// セッションメッセージを優先して取得し、あれば削除する
function getMessage(): ?string
{
  // セッションメッセージを優先
  $message = getSessionMessage();

  if ($message !== null) {
    // セッションメッセージがあれば削除
    clearSessionMessage();
    return $message;
  }

  // セッションになければURLから取得
  return getUrlMessage();
}

// メッセージを取得してクリア（タイプ別に対応）
function getMessages(): array
{
  $messages = [];

  if (!empty($_SESSION['success'])) {
    $messages[] = [
      'text' => $_SESSION['success'],
      'type' => 'success'
    ];
    unset($_SESSION['success']);
  }

  if (!empty($_SESSION['error'])) {
    $messages[] = [
      'text' => $_SESSION['error'],
      'type' => 'danger'
    ];
    unset($_SESSION['error']);
  }

  return $messages;
}
