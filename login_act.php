<?php
session_name('kadai10');
session_start();
require_once('connect.php');

$email = $_POST['email'];
$password = $_POST['password'];

$pdo = db_conn();

$sql  = "SELECT * FROM users_table WHERE email=:email AND deleted_at IS NULL";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
// $stmt->bindValue(':password', $password, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// ユーザ有無で条件分岐
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$pw = password_verify($password, $user['password']);
if (!$user || !$pw) {
  echo "<p>ログイン情報に誤りがあります</p>";
  echo "<a href=login.php>ログイン画面へ</a>";
  exit();
} else {
  $_SESSION = array();
  $_SESSION['session_id'] = session_id();
  $_SESSION['is_admin'] = $user['is_admin'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['email'] = $user['email'];
  header("Location:index.php");
  exit();
}