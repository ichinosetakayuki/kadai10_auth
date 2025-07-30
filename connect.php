<?php
// DB接続関数 :db_conn()
function db_conn()
{
  require_once __DIR__ . '/vendor/autoload.php';

  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  $isLocalhost = ($_SERVER["SERVER_NAME"] === "localhost");

  $db_name = $isLocalhost ? $_ENV['DB_NAME'] : $_ENV['SAKURA_DB_NAME'];
  $db_host = $isLocalhost ? $_ENV['DB_HOST'] : $_ENV['SAKURA_DB_HOST'];
  $user = $isLocalhost ? $_ENV['DB_USER'] : $_ENV['SAKURA_DB_USER'];
  $pwd = $isLocalhost ? $_ENV['DB_PASS'] : $_ENV['SAKURA_DB_PASS'];

  $dbn = "mysql:dbname={$db_name};charset=utf8mb4;port=3306;host={$db_host}";

  try {
    $pdo = new PDO($dbn, $user, $pwd);
    return $pdo;
  } catch (PDOException $e) {
    echo json_encode(["db error" => "{$e->getMessage()}"]);
    exit();
  }
}