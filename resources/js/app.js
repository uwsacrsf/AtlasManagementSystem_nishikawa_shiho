import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

(function ($) {
  $(function () {
    /*削除モーダル*/
    $('.js-delete-modal-open').on('click', function () {
      $('#delete-modal').fadeIn();
      return false;
    });

    $('.js-delete-modal-close').on('click', function () {
      $('#delete-modal').fadeOut();
      return false;
    });

    /*サブカテゴリー制御*/
    $('.subject_edit_btn').on('click', function () {
      $('.subject_inner').slideToggle();
    });

  });
})(jQuery);
