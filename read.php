<?php
header('Content-Type: application/json');

require_once('connect.php');
$pdo = db_conn();

// index.phpに表示する１曲をランダムで選ぶ
$sql =  'SELECT * FROM mv_scenes_table WHERE deleted_at IS NULL ORDER BY RAND() LIMIT 1';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($result, JSON_UNESCAPED_UNICODE);

?>