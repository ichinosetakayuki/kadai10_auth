<?php
header('Content-Type: application/json');

require_once('connect.php');
$pdo = db_conn();

// すべてのデータを抽出→管理画面admin.phpに一覧表示するため
$sql =  'SELECT * FROM mv_scenes_table WHERE deleted_at IS NULL ORDER BY id ASC';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$result = $stmt->fetchALL(PDO::FETCH_ASSOC);
echo json_encode($result, JSON_UNESCAPED_UNICODE);
