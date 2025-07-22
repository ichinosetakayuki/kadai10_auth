let allSceneData = [];

// データ一覧を読み込み
axios.get('read_all.php')
  .then(function (response) {
    allSceneData = response.data;
    console.log(allSceneData);
    sceneListMaker(allSceneData);
  }).catch(function (error) {
    console.error("データ取得エラー:", error);
  })

// データ(配列)を入力し、登録済みシーン一覧を描画する関数
function sceneListMaker(dataArray) {
  if (!Array.isArray(dataArray)) {
    console.error("sceneListMaker:データが配列ではありません", dataArray);
    return;
  }
  let elements = "";
  dataArray.forEach(scene => {
    elements += `<tr>
          <td>${scene.id}</td>
          <td>${scene.song_title}</td>
          <td>${scene.youtube_id}</td>
          <td>${scene.start_time_sec}</td>
          <td>${scene.end_time_sec}</td>
          <td>${scene.description}</td>
          <td>${scene.keywords}</td>
          <td><img src="img/pencil-square.svg" class="edit_icon" data-id="${scene.id}"></td>
          <td><img src="img/trash.svg" class="delete_icon" data-id="${scene.id}"></td>
          </tr>`
  })
  $("#scene-list").html(elements);
}

// 編集アイコンクリックし、編集内容をフォームに表示
$("#scene-list").on("click", ".edit_icon", function () {
  const id = $(this).data("id");
  // .data()はdata-*属性をそのまま扱えるので数値変換不要
  const scene = allSceneData.find(item => item.id === id);
  console.log(scene);


  $("#song_title").val(scene.song_title);
  $("#youtube_id").val(scene.youtube_id);
  $("#start_time_sec").val(scene.start_time_sec);
  $("#end_time_sec").val(scene.end_time_sec);
  $("#description").val(scene.description);
  $("#keywords").val(scene.keywords);
  $("#id").val(scene.id);

  $("#submit_btn").hide();
  $("#update_btn").show();

});

// フォームの内容をクリアする関数
function clearForm() {
  $("#song_title, #youtube_id, #start_time_sec, #end_time_sec, #description, #keywords, #id").val("");
}

// クリアボタンでフォーム内容をクリアする
$("#clear_btn").on("click", clearForm);

// 内容更新ボタンクリックで、変更内容をupdate.phpに送信し、データ更新
// 更新されたデータをread_all.phpから取得し、一覧画面に表示
$("#update_btn").on("click", function () {
  // console.log("ok");
  const updateData = {
    id: $("#id").val(),
    song_title: $("#song_title").val(),
    youtube_id: $("#youtube_id").val(),
    start_time_sec: $("#start_time_sec").val(),
    end_time_sec: $("#end_time_sec").val(),
    description: $("#description").val(),
    keywords: $("#keywords").val()
  }

  $.post("update.php", updateData, function (results) {
    if (results.status === "success") {
      alert("更新が完了しました")
      $.getJSON("read_all.php", function (data) {
        allSceneData = data;
        sceneListMaker(allSceneData);
        clearForm();
      })
    } else {
      console.error("更新に失敗しました：", results);
    }
  }).fail(function (xhr, status, error) {
    console.error("検索に失敗しました");
    console.error("xhr.status", xhr.status);
    console.error("status", status);
    console.error("error", error);
  })
});

// データの削除
$("#scene-list").on("click", ".delete_icon", function () {
  const sceneId = $(this).data("id");
  const message = confirm(`「id：${sceneId}」のデータを削除してよろしいですか？`);
  if(!message) {
    return;
  }
  const deleteData = {
    id: sceneId
  }

  $.post("delete.php", deleteData, function (results) {
    if (results.status === "success") {
      alert("削除が完了しました");
      $.getJSON("read_all.php", function (data) {
        allSceneData = data;
        sceneListMaker(allSceneData);
      })
    } else {
      console.error("削除に失敗しました：", results);
    }
  }).fail(function (xhr, status, error) {
    console.error("検索に失敗しました");
    console.error("xhr.status", xhr.status);
    console.error("status", status);
    console.error("error", error);
  })
});
