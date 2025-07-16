let sceneData; // read.phpで読み込んだデータを格納
let player; // Youtube Playerオブジェクト
let timerId; // 再生停止用のタイマーID

// Youtube IFrame Player API をロード
// scriptタグをhtmlに直接書かず、jsから動的に書くことでメリット
// jsだけでAPIを読む、他のscriptよりも早く読む、再利用性・保守性
const tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
const firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
// ↑scriptタグの直前に挿入するため。他のスクリプトよりも先に読み込む意図
// youtubeドキュメントにも同様に記載

// axiosでread.phpからJSONデータを読み込み
window.addEventListener("DOMContentLoaded", () => {
  axios.get("read.php")
    .then(function (response) {
      sceneData = response.data;
      console.log("axiosで取得したデータ：", sceneData);
      inputInfo(); // 曲情報などをhtmlに表示

      // Youtube APIが読み込み済みならプレイヤー作成
      // YT→YouTube IFrame Player API によって提供されるグローバルオブジェクト
      // YT.player→YouTubeのプレイヤーを作るためのクラス（コンストラクタ関数）
      if(typeof YT !== 'undefined' && typeof YT.Player !== 'undefined') {
        createPlayer(); // APIが完全に読み込まれているならすぐにプレーヤーを作る
      } else {
        // API読み込み後にプレイヤー作成
        window.onYouTubeIframeAPIReady = createPlayer;
        // YouTube公式APIの仕組みとしてYouTube IFrame APIが読み込まれると、
        // window.onYouTubeIframeAPIReady() という関数を自動で実行、読み込まれていない場合、
        // createPlayer をセットして待機
      }
    })
    .catch(function (error) {
      console.error("データ取得エラー:", error);
    })
});


// プレイヤー生成処理
function createPlayer() {
  // YT.Playerクラスを使用して、動画プレイヤーを生成
  //第1引数にhtmlの場所を指定、第2引数に詳細な設定を指定
  player = new YT.Player('player-container', {
    height: '390',
    width: '640',
    videoId: sceneData.youtube_id, //動画のIDを設定
    playerVars: { // 動画プレイヤーの初期設定
      'controls': 0, // コントロールバー非表示、1で表示
      'showinfo': 0, // 動画情報非表示
      'rel': 0, // 関連動画非表示
      'modestbranding': 1 // Youtubeロゴを小さく
    },
    // eventプロパティではAPIの何らかのきっかけで発動するイベントを受け取る
    events: {
      'onReady': onPlayerReady, // 動画プレイヤーの準備が完了、再生可能な状態な時に発動
      'onStateChange': onPlayerStateChange // プレイヤーの状況が変化した時に発動
    }
  });
}

// プレイヤーが準備できたら再生
function onPlayerReady(event) {
  // 動画の開始位置を設定して再生
  event.target.seekTo(sceneData.start_time_sec, true); // 再生位置を指定
  event.target.playVideo(); // 動画を再生
}

// プレイヤーの状態が変化した時
function onPlayerStateChange(event) {
  const state = event.data;

  if(state === YT.PlayerState.PLAYING) { // 再生が実際に始まったタイミングでsetStopTimerを呼ぶ
    console.log("動画が再生されました。stopタイマーをセットします。");
    setStopTimer();
  }
  // 動画終了、再生停止、中断された場合にタイマーを解除して二重処理を防止
  if (state === YT.PlayerState.ENDED || state === YT.PlayerState.PAUSED || state === YT.PlayerState.BUFFERING) {
    clearTimeout(timerId);
  }
}

// 再生を止めるためのタイマーを設定
function setStopTimer() {
  // シーンの再生時間（秒）を計算
  let duration = sceneData.end_time_sec - sceneData.start_time_sec;
  if (duration <= 0) { // 負の値や0の場合は少なくとも3秒再生
    duration = 3;
    console.log("durationが0以下だったので、3秒に設定されました。"); // ★これも追加
  }
  // 指定時間後に動画を停止
  timerId = setTimeout(() => {
    console.log("setTimeoutが呼び出されました。動画を停止しようとしています。"); // ★これを追加
    if (player && player.stopVideo) {
      console.log("player.stopVideo()を実行します。"); // ★これも追加
      player.stopVideo(); // 指定時間後に動画を停止
    } else {
      console.error("playerオブジェクトが未定義か、stopVideoメソッドがありません。"); // ★これも追加
    }
  }, duration * 1000); // ミリ秒に変換
}

// 「次のシーンを見る」ボタンのクリックイベント
$("#next-scene-btn").on("click", function () {
  location.reload(); // 現在のページをリロードするメソッド
});

// 曲情報などをhtmlに表示
function inputInfo() {
  $("#song-title").html(sceneData.song_title);
  $("#scene-desc").html(sceneData.description);
}