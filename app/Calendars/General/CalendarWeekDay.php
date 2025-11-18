<?php
namespace App\Calendars\General;

use App\Models\Calendars\ReserveSettings;
use Carbon\Carbon;
use Auth;

class CalendarWeekDay{
  protected $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  function getClassName(){
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function pastClassName(){
    return;
  }

  /**
   * @return
   */
   function render(){
     return '<p class="day">' . $this->carbon->format("j"). '日</p>';
   }

   function selectPart($ymd){
     // --- 予約データと最大枠数の取得 ---
     $one_part_data = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first();
     $two_part_data = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
     $three_part_data = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();

     // --- 残り枠数の計算 ---

     // リモ1部
     if($one_part_data){
       // 最大枠数から 予約ユーザー数 を引く
       $one_part_remaining = $one_part_data->limit_users - $one_part_data->users->count();
     }else{
       $one_part_remaining = 0;
     }

     // リモ2部
     if($two_part_data){
       $two_part_remaining = $two_part_data->limit_users - $two_part_data->users->count();
     }else{
       $two_part_remaining = 0;
     }

     // リモ3部
     if($three_part_data){
       $three_part_remaining = $three_part_data->limit_users - $three_part_data->users->count();
     }else{
       $three_part_remaining = 0;
     }

     //HTML
     $html = [];
     $html[] = '<select name="getPart[]" class="border-primary" style="width:70px; border-radius:5px;" form="reserveParts">';
     $html[] = '<option value="" selected></option>';

     // リモ1部
     if($one_part_remaining <= 0){
       $html[] = '<option value="1" disabled>リモ1部(残り0枠)</option>';
     }else{
       $html[] = '<option value="1">リモ1部(残り'.$one_part_remaining.'枠)</option>';
     }

     // リモ2部
     if($two_part_remaining <= 0){
       $html[] = '<option value="2" disabled>リモ2部(残り0枠)</option>';
     }else{
       $html[] = '<option value="2">リモ2部(残り'.$two_part_remaining.'枠)</option>';
     }

     // リモ3部
     if($three_part_remaining <= 0){
       $html[] = '<option value="3" disabled>リモ3部(残り0枠)</option>';
     }else{
       $html[] = '<option value="3">リモ3部(残り'.$three_part_remaining.'枠)</option>';
     }

     $html[] = '</select>';
     return implode('', $html);
   }

   function getDate(){
     return '<input type="hidden" value="'. $this->carbon->format('Y-m-d') .'" name="getData[]" form="reserveParts">';
   }

   function everyDay(){
     return $this->carbon->format('Y-m-d');
   }

   function authReserveDay(){
     if (Auth::check()) {
        return Auth::user()->reserveSettings->pluck('setting_reserve')->toArray();
     }
     return [];
   }

   function authReserveDate($reserveDate){
     if (Auth::check()) {
        return Auth::user()->reserveSettings->where('setting_reserve', $reserveDate);
     }
     return collect();
   }

}
