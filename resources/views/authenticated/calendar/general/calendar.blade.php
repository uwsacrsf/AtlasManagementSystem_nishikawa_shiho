<x-sidebar>
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
      </div>
    </div>

    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
</div>

<!-- キャンセル確認モーダル -->
<div class="modal js-modal" role="dialog">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
        <!-- モーダルヘッダー -->
        <p class="mb-3 text-center" style="font-size:16px; font-weight:bold;">
            予約キャンセル確認
        </p>

        <!-- 予約情報表示 -->
        <div class="modal-info mb-4" style="border: 1px solid #ccc; padding: 15px; border-radius: 5px;">
            <p>予約日: <span id="modal-reserve-date"></span></p>
            <p>時間: <span id="modal-reserve-part"></span></p>
        </div>

        <p class="text-center mb-4">
            上記の予約をキャンセルしてもよろしいですか？
        </p>

        <!-- キャンセル実行フォーム -->
        <form action="/delete/calendar" method="POST" id="cancel-form">
            @csrf
            <input type="hidden" name="delete_date" id="modal-delete-date" value="">
        </form>

        <div class="d-flex justify-content-center">
            <!-- 閉じるボタン -->
            <button type="button" class="btn btn-secondary js-modal-close mr-3" style="width: 100px;">閉じる</button>

            <!-- キャンセル実行ボタン-->
            <button type="submit" class="btn btn-danger" form="cancel-form" style="width: 100px;">キャンセル</button>
        </div>
    </div>
</div>

<style>
/* モーダル背景 */
.modal {
    display: none;
    height: 100vh;
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 99999;
}
.modal__bg {
    background: rgba(0, 0, 0, 0.8);
    height: 100vh;
    width: 100%;
    position: absolute;
}
.modal__content {
    background: #fff;
    width: 90%;
    max-width: 450px;
    padding: 30px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 10px;
}
.modal-info {
    font-size: 14px;
}
.modal-info p {
    margin-bottom: 5px;
}
</style>
</x-sidebar>
