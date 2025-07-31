<?php
session_name('kadai10');
session_start();
require_once('funcs.php');
check_session_id();

if ($_SESSION['is_admin'] === 0) {
  header('Location:index.php');
  exit();
}

// DB接続
require_once('connect.php');
$pdo = db_conn();

// すべてのデータを抽出→管理画面admin.phpに一覧表示するため
$sql =  'SELECT * FROM tickets_table WHERE deleted_at IS NULL ORDER BY created_at ASC';
$stmt = $pdo->prepare($sql);

try {
  $status = $stmt->execute();
} catch (PDOException $e) {
  echo json_encode(["sql error" => "{$e->getMessage()}"]);
  exit();
}

$results = $stmt->fetchALL(PDO::FETCH_ASSOC);
// echo json_encode($result, JSON_UNESCAPED_UNICODE);
$output = "";
$num = 0;
$seat_counts = [
  '2025年X月X日（福岡）' => ['S席' => 0, 'A席' => 0],
  '2025年y月y日（東京）' => ['S席' => 0, 'A席' => 0],
]; // 公演別の販売数集計のための連想配列
$ticket_values = ['1枚' => 1, '2枚' => 2]; // チケット枚数を数値に置き換え
$summary = "";

foreach ($results as $result) {
  $id = htmlspecialchars($result['id'], ENT_QUOTES, 'UTF-8');
  $user_id = htmlspecialchars($result['user_id'], ENT_QUOTES, 'UTF-8');
  $username = htmlspecialchars($result['username'], ENT_QUOTES, 'UTF-8');
  $email = htmlspecialchars($result['email'], ENT_QUOTES, 'UTF-8');
  $live_date = htmlspecialchars($result['live_date'], ENT_QUOTES, 'UTF-8');
  $num_tickets = htmlspecialchars($result['num_tickets'], ENT_QUOTES, 'UTF-8');
  $seat_type = htmlspecialchars($result['seat_type'], ENT_QUOTES, 'UTF-8');
  $num ++;
  $output .= "<tr>
  <td>{$num}</td>
  <td>{$id}</td>
  <td>{$user_id}</td>
  <td>{$username}</td>
  <td>{$email}</td>
  <td>{$live_date}</td>
  <td>{$num_tickets}</td>
  <td>{$seat_type}</td>
  <td><img src='img/trash.svg' class='delete_icon' data-id='{$result['id']}'></td>
  </tr>";

  // 公演別の販売数の集計
  if (
    isset($seat_counts[$live_date]) &&
    isset($seat_counts[$live_date][$seat_type]) &&
    isset($ticket_values[$num_tickets])
  ) {
    $seat_counts[$live_date][$seat_type] += $ticket_values[$num_tickets];
  } else {
    echo "集計エラー：{$live_date}/{$seat_type}{$num_tickets}";
  }
}

// 公演別の販売数集計の表示
foreach ($seat_counts as $live_date => $seats) {
  $S_seat_count = $seats['S席'];
  $A_seat_count = $seats['A席'];
  $total = $S_seat_count + $A_seat_count;

  $summary .= "<tr>
  <td>{$live_date}</td>
  <td>{$S_seat_count}</td>
  <td>{$A_seat_count}</td>
  <td>{$total}</td>
  </tr>";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
    }

    header {
      text-align: end;
      margin-right: 40px;
    }

    main {
      max-width: 960px;
      width: calc(100% - 40px);
      margin: 0 auto;
    }

    h2 {
      font-size: 1.2rem;
      border-left: 8px solid lightcoral;
      padding-left: 8px;
    }

    table {
      border-collapse: collapse;
      border: 1px solid lightgray;
    }

    th,
    tr,
    td {
      border: 1px solid lightgray;
      padding: 8px;
      text-align: center;
    }
  </style>
  <title>チケット申し込み一覧</title>
</head>

<body>
  <header>
    <div class="to-index"><a href="index.php">INDEX画面へ</a></div>
    <div class="to-admin"><a href="admin.php">管理者用画面</a></div>
  </header>
  <main>

    <h2>チケット申し込み一覧</h2>
    <table>
      <thead>
        <tr>
          <th>NO.</th>
          <th>id</th>
          <th>user_id</th>
          <th>名前</th>
          <th>email</th>
          <th>公演日</th>
          <th>枚数</th>
          <th>座席</th>
          <th>削除</th>
        </tr>
      </thead>
      <tbody id="ticket-list">
        <?= $output ?>
      </tbody>
    </table>
    <h2>公演別販売数</h2>
    <table>
      <thead>
        <tr>
          <th>公演</th>
          <th>S席</th>
          <th>A席</th>
          <th>合計</th>
        </tr>
      </thead>
      <tbody id="ticket-sale-summary">
        <?= $summary ?>
      </tbody>
    </table>

  </main>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="js/ticket_admin.js"></script>
</body>

</html>