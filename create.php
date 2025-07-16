<?php
session_start();
require_once('funcs.php');
check_session_id();

if($_SESSION['is_admin'] === 0) {
  header('Location:index.php');
  exit();
}

if (
  !isset($_POST['song_title']) || $_POST['song_title'] === "" ||
  !isset($_POST['youtube_id']) || $_POST['youtube_id'] === "" ||
  !isset($_POST['start_time_sec']) || $_POST['start_time_sec'] === "" ||
  !isset($_POST['end_time_sec']) || $_POST['end_time_sec'] === "" ||
  !isset($_POST['description']) || $_POST['description'] === "" ||
  !isset($_POST['keywords']) || $_POST['keywords'] === ""
) {
  exit("データがありません。");
}

$song_title = $_POST['song_title'];
$youtube_id = $_POST['youtube_id'];
$start_time_sec = $_POST['start_time_sec'];
$end_time_sec = $_POST['end_time_sec'];
$description = $_POST['description'];
$keywords = $_POST['keywords'];

require_once('connect.php');
$pdo = db_conn();

$sql = 'INSERT INTO mv_scenes_table (id, song_title, youtube_id, start_time_sec, end_time_sec, description, keywords, created_at, updated_at) VALUES (NULL, :song_title, :youtube_id, :start_time_sec, :end_time_sec, :description, :keywords, now(), now())';

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':song_title', $song_title, PDO::PARAM_STR);
$stmt->bindValue(':youtube_id', $youtube_id, PDO::PARAM_STR);
$stmt->bindValue(':start_time_sec', $start_time_sec, PDO::PARAM_INT);
$stmt->bindValue(':end_time_sec', $end_time_sec, PDO::PARAM_INT);
$stmt->bindValue(':description', $description, PDO::PARAM_STR);
$stmt->bindValue(':keywords', $keywords, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

header('Location: admin.php');
exit();
