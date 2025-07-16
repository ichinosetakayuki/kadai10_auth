<?php
header('Content-Type: application/json');

session_start();
require_once('funcs.php');
check_session_id();

if($_SESSION['is_admin'] === 0) {
  header('Location:index.php');
  exit();
}

if (!isset($_POST['id']) || $_POST['id'] === "") {
  exit("データがありません。");
}

$id = $_POST['id'] ;

require_once('connect.php');
$pdo = db_conn();

$sql = 'UPDATE mv_scenes_table SET deleted_at=now() WHERE id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
  $status = $stmt->execute();
  echo json_encode(["status" => "success", "massage" => "削除が完了しました"]);
} catch (PDOException $e) {
  echo json_encode(["status" => "error", "message" => "DBエラー: " . $e->getMessage()]);
  exit();
}

