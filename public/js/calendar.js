$(function () {
  $(document).on('click', '.js-cancel-modal', function (e) {
    e.preventDefault();

    const reserveDate = $(this).data('date');
    const reservePartName = $(this).data('part-name');
    const reserveId = $(this).data('reserve-id');

    $('#modal-reserve-date').text(reserveDate);
    $('#modal-reserve-part').text(reservePartName);

    $('#modal-delete-date').val(reserveId);

    // モーダルを開く
    $('.js-modal').fadeIn();

    return false;
  });

  $('.js-modal-close').on('click', function (e) {
    e.preventDefault();

    $('.js-modal').fadeOut();
    return false;
  });
});
