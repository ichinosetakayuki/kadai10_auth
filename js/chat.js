$(function () {
  $("#chat-form").on("submit", function (event) {
    event.preventDefault(); // フォームの送信を防ぐ
    const message = $("#message-input").val();
    if (!message) {
      alert("メッセージを入力してください");
      return;
    }

    const chatContainer = $("#chat-container");
    chatContainer.append(`<div class="user-message">${username}：${message}</div>`);
    $("#message-input").val(""); // 入力フィールドをクリア

    $.post('ai_chat_create.php', { message }, function (response) {
      chatContainer.append(`<div class="ai-message">MC風 AI：${response}</div>`);
      chatContainer.scrollTop(chatContainer.prop("scrollHeight"));
    }).fail(function () {
      chatContainer.append(`<div class="ai^messege>（エラー）Gemini APIが利用できません</div>`);
    });
  });
})