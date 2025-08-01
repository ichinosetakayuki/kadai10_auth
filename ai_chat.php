<?php
session_name('kadai10');
session_start();
require_once('funcs.php');
check_session_id();

if (!isset($_SESSION['username']) || $_SESSION['username'] === '') {
  header('Location: login.php');
  exit();
}

$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/chat.css">
  <title>MC風 AI Chat</title>
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
    <h2><img src="img/moritaka_anime00.png" alt="森高アイコン">　MC風AIチャット</h2>
    <div class="chat-box card shadow-lg p-3">
      <div id="chat-container" class="mb-3"></div>
      <form id="chat-form">
        <div class="input-group mb-3">
          <input type="text" id="message-input" class="form-control form-control-sm" placeholder="メッセージを入力..." autocomplete="off"
            required>
          <button class="btn btn-dark btn-sm" type="submit">送信</button>
        </div>
      </form>
    </div>
    <p>※開発者個人の趣味です。森高さん本人とは一切関係ありません。</p>

  </div>

  <script>
    // chat.jsにusernameを渡す。json_encodeにより、引用符などを自動でエスケープし、安全に渡す
    const username = <?= json_encode($username); ?>
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="js/chat.js"></script>

</body>

</html>