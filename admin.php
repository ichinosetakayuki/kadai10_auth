<?php
session_start();
require_once('funcs.php');
check_session_id();

if($_SESSION['is_admin'] === 0) {
  header('Location:index.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/admin.css">
  <title>moritaka_mv管理画面</title>
</head>

<body>
  <header>
    <div class="to-ticket-read"><a href="ticket_read.php">チケット一覧画面へ</a></div>
    <div class="to-index"><a href="index.php">INDEX画面へ</a></div>
  </header>
  <main>
    <h1>森高MVシーンジェネレーター管理画面</h1>
    <h2>シーンの登録</h2>
    <form action="create.php" method="POST">
      <div class="form-item">
        <label for="song_title">曲名</label>
        <input type="text" name="song_title" id="song_title">
      </div>
      <div class="form-item">
        <label for="youtube_id">Youtube ID</label>
        <input type="text" name="youtube_id" id="youtube_id">
      </div>
      <div class="form-item">
        <label for="start_time_sec">開始秒数</label>
        <input type="number" name="start_time_sec" id="start_time_sec">
        <label for="end_time_sec">終了秒数</label>
        <input type="number" name="end_time_sec" id="end_time_sec">
      </div>
      <div class="form-item">
        <label for="description">説明</label>
        <textarea name="description" id="description" rows="6" cols="80"></textarea>
      </div>
      <div class="form-item">
        <label for="keywords">キーワード</label>
        <textarea name="keywords" id="keywords" rows="4" cols="80"></textarea>
      </div>
      <div><input type="hidden" id="id"></div>
      <div class="form-buttons">
        <button type="submit" id="submit_btn">新規登録</button>
        <button type="button" id="update_btn">内容更新</button>
        <button type="button" id="clear_btn">クリア</button>
      </div>
    </form>

    <h2>登録済みシーン一覧</h2>
    <table>
      <thead>
        <tr>
          <th>id</th>
          <th>タイトル</th>
          <th>Youtube_id</th>
          <th>開始(秒)</th>
          <th>終了(秒)</th>
          <th>説明</th>
          <th>キーワード</th>
          <th>編集</th>
          <th>削除</th>
        </tr>
      </thead>
      <tbody id="scene-list">

      </tbody>
    </table>
  </main>




  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- axiosライブラリの読み込み -->
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="js/admin.js"></script>

</body>

</html>