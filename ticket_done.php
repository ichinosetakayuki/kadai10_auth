<?php
session_name('kadai10');
session_start();
require_once('funcs.php');
check_session_id();


if (
  empty($_SESSION['user_id']) ||
  empty($_SESSION['username']) ||
  empty($_SESSION['email'])
) {
  header('Location:login.php');
  exit();
}

if (empty($_SESSION['ticket_data'])) {
  header('Location:ticket_entry.php');
  exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$ticket_data = $_SESSION['ticket_data'];

$live_date = $ticket_data['live_date'];
$num_tickets = $ticket_data['num_tickets'];
$seat_type = $ticket_data['seat_type'];


require_once('connect.php');
$pdo = db_conn();

$sql = 'INSERT INTO tickets_table (id, user_id, username, email, live_date, num_tickets, seat_type, created_at, updated_at, deleted_at) VALUES (NULL, :user_id, :username, :email, :live_date, :num_tickets, :seat_type, now(), now(), NULL)';

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':live_date', $live_date, PDO::PARAM_STR);
$stmt->bindValue(':num_tickets', $num_tickets, PDO::PARAM_STR);
$stmt->bindValue(':seat_type', $seat_type, PDO::PARAM_STR);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

// unset($_SESSION['ticket_data']);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/common.css">
  <title>チケット申し込み完了</title>
</head>

<body>
  <small>※すべて開発中のダミーデータです。</small>
  <header>
    <div class="header-left">
      <h1><span>森高</span>ランド（仮）</h1>
    </div>
    <div class="header-right">
      <div class="to-top"><a href="index.php">TOPへ</a></div>
    </div>
  </header>
  <div class="bg-wrapper">
    <h2><img src="img/moritaka_anime00.png" alt="森高アイコン">　ライブチケット申し込み完了</h2>
    <div class="form-wrapper">
      <div class="img-wrapper">
        <img src="img/moritaka_img07.jpg" alt="森高写真">
      </div>
      <div id="form-area">
        <h3>お申し込み完了</h3>
        <p>お申し込みありがとうございました。</p>
        <div class="fz8"><a href="ticket_entry.php">もう一度申し込む</a></div>
        <div class="fz8"><a href="index.php">トップページへ戻る</a></div>
      </div>
    </div>
  </div>



</body>

</html>