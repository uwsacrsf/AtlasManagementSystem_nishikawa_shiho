<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        $getPart = $request->input('getPart');
        $getDate = $request->input('getData');

        if (!is_array($getDate) || !is_array($getPart) || count($getDate) === 0 || count($getPart) === 0) {
            return redirect()->back()->with('err_message', '予約する日付と部を一つ以上選択してください。');
        }

        if (count($getDate) !== count($getPart)) {
             return redirect()->back()->with('err_message', '予約データに不整合があります。');
        }

        $reserveDays = array_filter(array_combine($getDate, $getPart));

        if (empty($reserveDays)) {
            return redirect()->back()->with('err_message', '予約する部を選択してください。');
        }

        DB::beginTransaction();
        try{
            $count = 0;
            foreach($reserveDays as $day => $part){
                $reserve_settings = ReserveSettings::with('users')->where('setting_reserve', $day)->where('setting_part', $part)->first();

                if ($reserve_settings && $reserve_settings->users->count() < $reserve_settings->limit_users) {
                    // 予約処理を実行
                    $reserve_settings->users()->attach(Auth::id());
                    $count++;
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->back()->with('err_message', '予約処理中にエラーが発生しました。再度お試しください。');
        }

        $message = ($count > 0) ? $count . '件の予約が完了しました。' : '予約可能な枠がありませんでした。';

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()])->with('success_message', $message);
    }

public function delete(Request $request){
    $reserveDate = $request->input('delete_date');

    if (empty($reserveDate)) {
        return redirect()->back()->with('err_message', 'キャンセル対象の日付が指定されていません。');
    }

    DB::beginTransaction();
    try {
        $reserveSetting = Auth::user()->reserveSettings()
                            ->where('setting_reserve', $reserveDate)
                            ->first();

        if ($reserveSetting) {
            $reserveSetting->users()->detach(Auth::id());

            DB::commit();

            return redirect()->route('calendar.general.show', ['user_id' => Auth::id()])->with('success_message', '予約をキャンセルしました。');
        } else {
            DB::rollback();
            return redirect()->back()->with('err_message', 'キャンセル対象の予約が見つかりませんでした。');
        }

    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('err_message', 'キャンセル処理中にエラーが発生しました。再度お試しください。');
    }
}
}
