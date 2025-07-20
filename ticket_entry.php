<?php
session_start();
require_once('funcs.php');
check_session_id();

if (
  !isset($_SESSION['user_id']) || $_SESSION['user_id'] === '' ||
  !isset($_SESSION['username']) || $_SESSION['username'] === '' ||
  !isset($_SESSION['email']) || $_SESSION['email'] === ''
) {
  header('Location:login.php');
  exit();
}

// 一度、確認画面から「修正する」で戻って来た来た時に登録している情報を表示するため
$live_date = isset($_SESSION['ticket_data']['live_date']) ? $_SESSION['ticket_data']['live_date'] : '';
$num_tickets = isset($_SESSION['ticket_data']['num_tickets']) ? $_SESSION['ticket_data']['num_tickets'] : '';
$seat_type = isset($_SESSION['ticket_data']['seat_type']) ? $_SESSION['ticket_data']['seat_type'] : '';

// var_dump($num_tickets);
// exit();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/common.css">
  <title>チケット申し込み画面</title>
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
    <h2><img src="img/moritaka_anime00.png" alt="森高アイコン">　ライブチケットエントリー</h2>
    <div class="form-wrapper">
      <div class="img-wrapper">
        <img src="img/moritaka_img05.jpg" alt="森高写真">
      </div>
      <div id="form-area">
        <p><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>さんの申し込み入力</p>
        <form action="ticket_confirm.php" method="POST">
          <div class="form-item-b">
            <label for="live_date">希望公演選択</label>
            <select name="live_date" id="live_date" required>
              <option value="">選択してください</option>
              <option value="2025年X月X日（福岡）" <?= $live_date === '2025年X月X日（福岡）' ? 'selected' : '' ?>>2025年X月X日（福岡）</option>
              <option value="2025年y月y日（東京）" <?= $live_date === '2025年y月y日（東京）' ? 'selected' : '' ?>>2025年y月y日（東京）</option>
            </select>
          </div>
          <div class="form-item-b">
            <label for="num_tickets">チケット枚数</label>
            <select name="num_tickets" id="num_tickets" required>
              <option value="1枚" <?= $num_tickets === '1枚' ? 'selected' : '' ?>>1枚</option>
              <option value="2枚" <?= $num_tickets === '2枚' ? 'selected' : '' ?>>2枚</option>
            </select>
          </div>
          <div class="form-item-b">
            <label for="seat_type">座席種別</label>
            <select name="seat_type" id="seat_type" required>
              <option value="S席" <?= $seat_type === 'S席' ? 'selected' : '' ?>>S席</option>
              <option value="A席" <?= $seat_type === 'A席' ? 'selected' : '' ?>>A席</option>
            </select>
          </div>
          <div class="form-btn">
            <button type="submit">確認画面へ</button>
          </div>
        </form>

      </div>
    </div>
  </div>



</body>

</html>