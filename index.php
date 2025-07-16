<?php
session_start();
require_once('funcs.php');
// check_session_id();
if(isset($_SESSION['user_id']) && isset($_SESSION['session_id'])) {
  check_session_id();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/index_style.css">
  <title>森高ランド(仮)</title>
</head>

<body>
  <small>※すべて開発中のダミーデータです。</small>
  <header>
    <div class="header-left">
      <h1><span>森高</span>ランド（仮）</h1>
    </div>
    <div class="header-right">
      <?php if (isset($_SESSION['username']) && $_SESSION['username'] !== '') : ?>
        <div><span class="welcome">ようこそ<?= htmlspecialchars($_SESSION["username"], ENT_QUOTES, "UTF-8") ?>さん</span></div>
      <?php endif; ?>
      <div class="to-ticket"><a href="ticket_entry.php">チケット申し込み</a></div>
      <?php if (isset($_SESSION['username']) && $_SESSION['username'] !== '') :?>
        <div class="to-logout"><a href="logout.php">ログアウト</a></div>
      <?php else: ?>
        <div class="to-login"><a href="login.php">ログイン</a></div>
      <?php endif; ?>
      <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) :?>
        <div class="to-admin"><a href="admin.php">管理者用画面</a></div>
      <?php endif; ?>
    </div>
  </header>
  <div class="bg-wrapper">
    <h2><img src="img/moritaka_anime00.png" alt="森高アイコン">　森高千里MVシーンジェネレーター</h2>
    <img src="img/moritakaSample03.png" class="bg-img" alt="">
    <div class="player-wrapper">
      <div class="video-wrapper">
        <div id="player-container"></div>
      </div>
      <div id="desc-area">
        <p><strong>曲 名：</strong> <span id="song-title"></span></p>
        <p><strong>シーン説明：</strong><br> <span id="scene-desc"></span></p>
        <button id="next-scene-btn">次のシーンを見る</button>
      </div>
    </div>
  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- axiosライブラリの読み込み -->
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="js/main.js?v=1234"></script>

</body>

</html>