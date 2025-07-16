<?php
header('Content-Type: application/json');

if (
  !isset($_POST['song_title']) || $_POST['song_title'] === "" ||
  !isset($_POST['youtube_id']) || $_POST['youtube_id'] === "" ||
  !isset($_POST['start_time_sec']) || $_POST['start_time_sec'] === "" ||
  !isset($_POST['end_time_sec']) || $_POST['end_time_sec'] === "" ||
  !isset($_POST['description']) || $_POST['description'] === "" ||
  !isset($_POST['keywords']) || $_POST['keywords'] === "" ||
  !isset($_POST['id']) || $_POST['id'] === ""
) {
  exit("データがありません。");
}

$song_title = $_POST['song_title'];
$youtube_id = $_POST['youtube_id'];
$start_time_sec = $_POST['start_time_sec'];
$end_time_sec = $_POST['end_time_sec'];
$description = $_POST['description'];
$keywords = $_POST['keywords'];
$id = $_POST['id'];

require_once('connect.php');
$pdo = db_conn();

$sql = 'UPDATE mv_scenes_table SET song_title=:song_title, youtube_id=:youtube_id, start_time_sec=:start_time_sec, end_time_sec=:end_time_sec, description=:description, keywords=:keywords, update_at=now() WHERE id=:id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':song_title', $song_title, PDO::PARAM_STR);
$stmt->bindValue(':youtube_id', $youtube_id, PDO::PARAM_STR);
$stmt->bindValue(':start_time_sec', $start_time_sec, PDO::PARAM_INT);
$stmt->bindValue(':end_time_sec', $end_time_sec, PDO::PARAM_INT);
$stmt->bindValue(':description', $description, PDO::PARAM_STR);
$stmt->bindValue(':keywords', $keywords, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

try {
  $status = $stmt->execute();
  echo json_encode(["status" => "success", "massage" => "更新が完了しました"]);
} catch (PDOException $e) {
  echo json_encode(["status" => "error", "message" => "DBエラー: " . $e->getMessage()]);
  exit();
}
