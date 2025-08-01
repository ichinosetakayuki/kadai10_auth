<?php
header('Content-Type: text/plain; charset=UTF-8');

session_name('kadai10');
session_start();
require_once('funcs.php');
check_session_id();

require_once __DIR__ . '/vendor/autoload.php'; // Composerのオートローダーを読み込む
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load(); // .envファイルから環境変数を読み込む

// Gemini APIキーとエンドポイント
$apiKey = $_ENV['GOOGLE_API_KEY']; // 環境変数からAPIキーを取得
if (empty($apiKey)) {
  header('HTTP/1.1 500 Internal Server Error');
  echo 'APIキーが設定されていません。';
  exit;
}
$model = 'gemini-2.5-flash'; // 使用するモデル名
$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . urlencode($apiKey);


// リクエストのメソッドがPOSTであり、'message'が存在する場合
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['message'])) {
  header('HTTP/1.1 400 Bad Request');
  echo '無効なリクエストです。';
  exit;
}

$userMessage = trim($_POST['message']);

// Gemini APIへのプロンプト設定 ヒアドキュメント（複数行のテキストを扱うための構文）を使用
// ここでは、森高千里風のキャラクターとしての設定、あくまでも風のキャラクターであることを強調
$systemPrompt = <<<PROMPT
あなたは森高千里風のキャラクターです。以下のルールに従って、ユーザーと会話してください。
* 話し方: 明るく、親しみやすい口調で話します。
* 性格: 親しみやすく、明るい、少しお茶目な一面もあります。
* 知識: 自身の音楽活動、ヒット曲、ドラム、料理、ファッション、熊本県出身であること、そして一般的な話題について話せます。
* 応答: 質問には簡潔に答えます。たまにユニークな表現を使うことがあります。
* 重要: あなたはあくまで「森高千里風のキャラクター」であり、ご本人ではありません。
PROMPT;


// リクエストデータ構成
$postData = [ //Gemini APIに送るデータをPHPの配列で定義
  'contents' => [ // Gemini APIが期待する形式のキー
    [
      'role' => 'user', // 会話の発信者がユーザー側のあることを示す
      'parts' => [
        ['text' => $systemPrompt . "\nユーザー: " . $userMessage] // 実際の発言を入れる、MC風の設定と一緒に送る
      ]
    ]
  ]
];

$jsonData = json_encode($postData, JSON_UNESCAPED_UNICODE); // PHPの配列をJSON形式に変換

// cURL(HTTPリクエストをすることにより、外部サイトの情報を取得することができる)
$ch = curl_init($url); // Gemini APIのURLを指定して通信の準備をする
curl_setopt_array($ch, [ // cURLのオプションを設定、複数ある場合、配列でまとめて設定
  CURLOPT_RETURNTRANSFER => true, // レスポンスを文字列として返す
  CURLOPT_POST => true, // POSTリクエストを使用
  CURLOPT_HTTPHEADER => ['Content-Type: application/json'], // データ形式はJSONであると明示
  CURLOPT_POSTFIELDS => $jsonData // 送るデータ本体
]);

$response = curl_exec($ch); // cURLの実行、リクエストを送り、レスポンスを取得
if ($response === false) { // cURLの実行に失敗した場合
  header('HTTP/1.1 500 Internal Server Error');
  echo 'Gemini APIへのリクエストに失敗しました: ' . curl_error($ch);
  exit;
}
curl_close($ch); // 通信セッションを閉じる

// レスポンスのデコード
$json = json_decode($response, true); // JSON形式のレスポンスをPHPの配列に変換

if (isset($json['error'])) { // レスポンスにエラーが含まれている場合
  echo '(エラー)' . htmlspecialchars($json['error']['message'], ENT_QUOTES, 'UTF-8');
  exit;
}

$reply = $json['candidates'][0]['content']['parts'][0]['text'] ?? '（応答がありません）'; // レスポンスからAIの応答を取得
echo htmlspecialchars($reply, ENT_QUOTES, 'UTF-8'); // HTMLエスケープして出力