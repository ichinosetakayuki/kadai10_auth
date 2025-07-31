/**
 * phpから返された曲情報からなどを、htmlに表示する
 * @param {Array} sceneData - 曲情報データの配列
 */
export function displayMvInfo(sceneData) {

  $("#like-count").html(sceneData.like_count);
  $("#song-title").html(sceneData.song_title);
  $("#scene-desc").html(sceneData.description);
}

/**
 * phpから帰ってきたログインユーザーがいいねしたかどうかの情報からいいねアイコンの色をかえる
 * @param {num} is_liked - (1 or 0)  
 */
export function changedLikeIconColor(is_liked) {
  if (is_liked === 1) {
    $("#like-link").css('color', 'lightcoral');
  } else if (is_liked === 0) {
    $("#like-link").css('color', 'lightgrey');
  } else {
    console.log('is_likeの値が不正です。');
  }
}