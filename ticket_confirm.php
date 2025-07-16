<?php
session_start();
require_once('funcs.php');
check_session_id();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
  header('Location:ticket_entry.php');
  exit();
}

// 送られて来たチケット申し込み情報を'ticket_data'として一括してSESSIONに入れる
$_SESSION['ticket_data'] = $_POST;

$live_date = htmlspecialchars($_POST['live_date'], ENT_QUOTES, 'UTF-8');
$num_tickets = htmlspecialchars($_POST['num_tickets'], ENT_QUOTES, 'UTF-8');
$seat_type = htmlspecialchars($_POST['seat_type'], ENT_QUOTES, 'UTF-8');

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/common.css">
  <title>チケット確認画面</title>
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
    <h2><img src="img/moritaka_anime00.png" alt="森高アイコン">　ライブチケット確認</h2>
    <div class="form-wrapper">
      <div class="img-wrapper">
        <img src="img/moritaka_img03.png" alt="森高写真">
      </div>
      <div class="form-area">
        <p class="ttl-1">申し込み内容の確認</p>
        <ul>
          <li>希望公演：<?= $live_date ?></li>
          <li>枚　　数：<?= $num_tickets ?></li>
          <li>座席種別：<?= $seat_type ?></li>
        </ul>
        <form action="ticket_done.php" method="POST">
          <div class="form-btn">
            <button type="submit">この内容で申し込む</button>
          </div>
        </form>
        <div class="form-btn">
          <button><a href="ticket_entry.php">修正する</a></button>
        </div>
      </div>
    </div>
  </div>



</body>

</html>