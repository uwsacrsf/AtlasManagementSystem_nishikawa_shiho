<x-guest-layout>
  <form action="{{ route('loginPost') }}" method="POST">
    <div class="w-100 vh-100 d-flex flex-column" style="background-color: #f0f8ff; align-items:center; justify-content:center;">

    <div class="text-center mb-4">
          <img src="{{ asset('image/atlas-black.png') }}" style="width: 150px; height: auto;">
      </div>

      <div class="border vh-50 w-25" style="background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <div class="w-75 m-auto pt-5">
          <label class="d-block m-0" style="font-size:13px;">メールアドレス</label>
          <div class="border-bottom border-primary w-100">
            <input type="text" class="w-100 border-0" name="mail_address">
          </div>
        </div>
        <div class="w-75 m-auto pt-5">
          <label class="d-block m-0" style="font-size:13px;">パスワード</label>
          <div class="border-bottom border-primary w-100">
            <input type="password" class="w-100 border-0" name="password">
          </div>
        </div>
        <div class="text-right m-3">
          <input type="submit" class="btn btn-primary" value="ログイン">
        </div>
        <div class="text-center">
          <a href="{{ route('registerView') }}">新規登録はこちら</a>
        </div>
      </div>
      {{ csrf_field() }}
    </div>
  </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
</x-guest-layout>
