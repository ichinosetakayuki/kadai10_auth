// データの削除
$("#ticket-list").on("click", ".delete_icon", function () {
  const ticketId = $(this).data("id");
  const message = confirm(`「id：${ticketId}」のデータを削除してよろしいですか？`);
  if(!message) {
    return;
  }
  const deleteData = {
    id: ticketId
  }

  $.post("ticket_delete.php", deleteData, function (results) {
    if (results.status === "success") {
      alert("削除が完了しました");
      window.location.reload();
      // $.getJSON("ticket_read.php", function (data) {
      //   allSceneData = data;
      //   sceneListMaker(allSceneData);
      // })
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