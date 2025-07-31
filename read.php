<?php
header('Content-Type: application/json');
session_name('kadai10');
session_start();

// 未ログインの場合、単純にmvをランダムで１つ返す
// ログイン済みの場合、mv情報といいね数、ログインユーザーがそのmvにいいねしたかどうかを含めて返す
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] === "") {
  $sql =  'SELECT * FROM mv_scenes_table WHERE deleted_at IS NULL ORDER BY RAND() LIMIT 1';
} else {
  $user_id = $_SESSION['user_id'];
  $sql = 'SELECT mv_scenes_table.*, COALESCE(result_table.like_count, 0) AS like_count, CASE WHEN like_table.user_id IS NOT NULL THEN 1 ELSE 0 END AS is_liked FROM mv_scenes_table LEFT OUTER JOIN (SELECT mv_id, COUNT(*) AS like_count FROM `like_table` GROUP BY mv_id) AS result_table ON mv_scenes_table.id = result_table.mv_id LEFT JOIN `like_table` ON mv_scenes_table.id = like_table.mv_id AND like_table.user_id = :user_id WHERE mv_scenes_table.deleted_at IS NULL ORDER BY RAND() LIMIT 1';
}

require_once('connect.php');
$pdo = db_conn();

$stmt = $pdo->prepare($sql);

if (isset($user_id)) {
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
}

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($result, JSON_UNESCAPED_UNICODE);
