<?php
// ログイン状態のチェック関数
function check_session_id()
{
  if (!isset($_SESSION['session_id']) || $_SESSION['session_id'] !== session_id()) {
    header('Location:login.php');
    exit();
  } else {
    session_regenerate_id(true);
    $_SESSION['session_id'] = session_id();
  }
}
