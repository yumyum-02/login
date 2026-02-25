<?php
// sanitize=無害化

// クロスサイトスクリプティング(XSS)対策用エスケープ関数
function escape($value)
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}