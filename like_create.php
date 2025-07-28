<?php
header('Content-Type: application/json');
session_name('kadai10');
session_start();
require_once('connect.php');

$user_id = $_GET['user_id']; // main.jsのaxiosのparamsから取得
$mv_id = $_GET['mv_id']; //  main.jsのaxiosのparamsから取得

$pdo = db_conn();

// ログインユーザーがcurrentのmvがをいいねしたかどうかを調べる
$sql =  'SELECT COUNT(*) FROM like_table WHERE user_id=:user_id AND mv_id=:mv_id';

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':mv_id', $mv_id, PDO::PARAM_INT);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}


$like_count = $stmt->fetchColumn();

if ($like_count > 0) {
  // ログインユーザーがいいねしている場合、いいねを取り消す
  $sql = 'DELETE FROM like_table WHERE user_id=:user_id AND mv_id=:mv_id';
} else {
  // ログインユーザーがいいねしていない場合、いいねを登録する
  $sql = 'INSERT INTO like_table (id, user_id, mv_id, created_at) VALUES (NULL, :user_id, :mv_id, now())';
}

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':mv_id', $mv_id, PDO::PARAM_INT);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// currentのmvのいいねをクリックしたあと、そのmvのいいね数とログインユーザーがいいねしたか(is_like 1 or 0)どうか
// mvのid、曲名、ユーザーid、いいね数（nullの時は0にする）、is_likeを返す
$sql = 'SELECT mv_scenes_table.id, mv_scenes_table.song_title, like_table.user_id, COALESCE(result_table.like_count, 0) AS like_count, CASE WHEN like_table.user_id IS NOT NULL THEN 1 ELSE 0 END AS is_liked FROM mv_scenes_table LEFT OUTER JOIN (SELECT mv_id, COUNT(*) AS like_count FROM `like_table` GROUP BY mv_id) AS result_table ON mv_scenes_table.id = result_table.mv_id LEFT JOIN `like_table` ON mv_scenes_table.id = like_table.mv_id AND like_table.user_id = :user_id WHERE mv_scenes_table.id = :mv_id AND mv_scenes_table.deleted_at IS NULL';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':mv_id', $mv_id, PDO::PARAM_INT);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);


try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// $resultは、mvのid、曲名、ユーザーid、いいね数（nullの時は0にする）、is_likeを返す
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($result, JSON_UNESCAPED_UNICODE);
exit();
