<?php

/**
 * セッションメッセージを取得（削除はしない）
 *
 * @return string|null メッセージ（存在しない場合はnull）
 */
function getSessionMessage(): ?string
{
  return $_SESSION['msg'] ?? null;
}

/**
 * URLパラメータからメッセージを取得
 *
 * @return string|null メッセージ（存在しない場合はnull）
 */
function getUrlMessage(): ?string
{
  return $_GET['msg'] ?? null;
}

/**
 * セッションメッセージを削除
 *
 * @return void
 */
function clearSessionMessage(): void
{
  unset($_SESSION['msg']);
}

/**
 * セッションまたはURLからメッセージを取得し、セッションメッセージは削除する
 * 既存コードとの互換性のため、この関数はセッションメッセージを自動削除する
 *
 * @return string|null メッセージ（存在しない場合はnull）
 */
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
