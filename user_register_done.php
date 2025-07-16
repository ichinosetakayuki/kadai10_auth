<?php
session_start();
require_once('funcs.php');
check_session_id();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/common.css">
  <title>登録完了</title>
</head>

<body>
  <small>※すべて開発中のダミーデータです。</small>
  <header>
    <div class="header-left">
      <h1><span>森高</span>ランド（仮）</h1>
    </div>
    <div class="header-right">
      <!-- <div class="to-top"><a href="index.php">TOPへ</a></div> -->
    </div>
  </header>
  <div class="bg-wrapper">
    <h2><img src="img/moritaka_anime00.png" alt="森高アイコン">　登録完了</h2>
    <div class="form-wrapper">
      <div class="img-wrapper">
        <img src="img/moritaka_img02.jpg" alt="森高写真">
      </div>
      <div class="form-area">
        <p>ユーザー登録が完了しました。</p>
        <div><a href="index.php">TOP画面へ</a></div>
        <!-- <div class="form-item-b">
            <label for="username">ユーザー名</label>
            <input type="text" name="username" id="username" required>
          </div>
          <div class="form-item-b">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" required>
          </div>
          <div class="form-item-b">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" required>
          </div>
          <div class="form-btn">
            <button type="submit">新規ユーザー登録</button>
          </div> -->
      </div>
    </div>
  </div>


</body>

</html>