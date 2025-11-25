<x-sidebar>
<div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
  <div class="w-50 m-auto h-75">

  @php
        $reserveSetting = $reservePersons->first();
        $userCount = $reserveSetting ? $reserveSetting->users->count() : 0;
    @endphp

    <p class="text-lg font-semibold mb-4">
      <span>{{ \Carbon\Carbon::parse($date)->format('Y-m-d日') }}</span>
      <span class="ml-4">{{ $part }}部</span>
    </p>

    <div class="h-75 border overflow-auto shadow-md rounded-lg">
      <table class="table table-striped mb-0 w-full">
        <thead class="bg-gray-200 sticky top-0">
          <tr class="text-center">
            <th class="w-20 p-2">ID</th>
            <th class="w-40 p-2">名前</th>
            <th class="w-40 p-2">場所</th>
          </tr>
        </thead>
        <tbody>
          @if ($reserveSetting && $reserveSetting->users->isNotEmpty())
            @foreach($reserveSetting->users as $user)
              <tr class="text-center">
                <td class="w-20 p-2">{{ $user->id }}</td>
                <td class="w-40 p-2">{{ $user->over_name }}{{ $user->under_name }}</td>
                <td class="w-40 p-2">リモート</td>
              </tr>
            @endforeach
          @else
            <tr class="text-center">
              <td colspan="3" class="p-4 text-gray-500">この日時・部数に予約ユーザーはいません。</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

  </div>
</div>
</x-sidebar>
