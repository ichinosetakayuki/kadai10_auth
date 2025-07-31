<?php
session_name('kadai10');
session_start();
require_once('connect.php');


if (
  !isset($_POST['username']) || $_POST['username'] === '' ||
  !isset($_POST['email']) || $_POST['email'] === '' ||
  !isset($_POST['password']) || $_POST['password'] === ''
) {
  exit('paramError');
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$hashed_pw = password_hash($password, PASSWORD_DEFAULT); // パスワードハッシュ化

$pdo = db_conn();

$sql = 'SELECT COUNT(*) FROM users_table WHERE email=:email'; // 一致するレコードの件数を調べる
$stmt = $pdo->prepare($sql); // SQL分を準備
$stmt->bindValue(':email', $email, PDO::PARAM_STR);

try {
  $status = $stmt->execute(); // 実行して結果セットが$stmtに格納、$stmtは「箱」
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$count = $stmt->fetchColumn(); // fetchColumn→$stmtの箱から最初の行の最初の列の値だけを返す。

if ($count > 0) {
  echo 'このユーザーは登録済みです' ;
  echo '<a href=user_input.php>新規ユーザー登録へ</a>';
  exit();
} else {

  $sql = 'INSERT INTO users_table(id, username, email, password, is_admin, created_at, updated_at, deleted_at) VALUES(NULL, :username, :email, :hashed_pw, 0, now(), now(), NULL)';

  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':username', $username, PDO::PARAM_STR);
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $stmt->bindValue(':hashed_pw', $hashed_pw, PDO::PARAM_STR);

  try {
    $status = $stmt->execute();
  } catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
  }

  $_SESSION['user_id'] = $pdo->lastInsertId();
  $_SESSION['username'] = $username;
  $_SESSION['email'] = $email;
  $_SESSION['is_admin'] = 0;
  $_SESSION['session_id'] = session_id();

  header("Location:user_register_done.php");
  exit();
}
