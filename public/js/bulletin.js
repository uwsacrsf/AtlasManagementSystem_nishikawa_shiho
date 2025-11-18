$(function () {
  // ----------------------------------------------------
  // 掲示板カテゴリの開閉
  // ----------------------------------------------------
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();
  });

  // ----------------------------------------------------
  // 投稿詳細：編集モーダルの開閉と初期値設定
  // ----------------------------------------------------
  $('.edit-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var post_title = $(this).attr('post_title');
    var post_body = $(this).attr('post_body');
    var post_id = $(this).attr('post_id');
    $('.modal-inner-title input').val(post_title);
    $('.modal-inner-body textarea').text(post_body);
    $('.edit-modal-hidden').val(post_id);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

  $('.js-delete-modal-open').on('click', function () {
    $('#delete-modal').fadeIn();
    return false;
  });
  $('.js-delete-modal-close').on('click', function () {
    $('#delete-modal').fadeOut();
    return false;
  });


  $('.subject_inner').hide();

  $('.subject_edit_btn').on('click', function () {
    $('.subject_inner').slideToggle();
  });

  // ----------------------------------------------------
  // いいね機能 (既存コード)
  // ----------------------------------------------------
  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault();
    // ... (いいね処理のコード) ...
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    // ... (いいね解除処理のコード) ...
  });
});
