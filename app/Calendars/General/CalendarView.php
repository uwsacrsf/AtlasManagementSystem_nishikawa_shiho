<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    //今日の日付を取得
    $todayString = Carbon::today()->toDateString();

    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';

      $days = $week->getDays();
      foreach($days as $day){

        // カレンダーの日付文字列を取得
        $currentDayString = $day->everyDay();

        // 過去日かどうか
        $isPastDay = ($currentDayString < $todayString && !empty($currentDayString));

        $tdClass = 'calendar-td ' . $day->getClassName();
        if ($isPastDay) {
          $tdClass .= ' past-day';
        }

        $html[] = '<td class="'.$tdClass.'">';
        $html[] = $day->render();
        $html[] = $day->getDate();

        if ($isPastDay) {
          // 過去日なら何も表示しない
          $html[] = '<p class="reservation-closed">受付終了</p>';
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';

        } elseif(in_array($day->everyDay(), $day->authReserveDay())){

          $reservedSetting = $day->authReserveDate($day->everyDay())->first();

          if ($reservedSetting) {
              $reservePartNum = $reservedSetting->setting_part;
              $reservePartName = "";

              if($reservePartNum == 1){
                $reservePartName = "リモ1部";
              }else if($reservePartNum == 2){
                $reservePartName = "リモ2部";
              }else if($reservePartNum == 3){
                $reservePartName = "リモ3部";
              }

              $html[] = '<button type="button" '.
                        'class="btn btn-danger p-0 w-75 js-cancel-modal" '.
                        'data-date="'.$reservedSetting->setting_reserve.'" '.
                        'data-part-name="'.$reservePartName.'" '.
                        'data-reserve-id="'.$reservedSetting->setting_reserve.'" '.
                        'style="font-size:12px">'. $reservePartName .'</button>';

              $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          } else {
              $html[] = $day->selectPart($day->everyDay());
          }

        } else {
          $html[] = $day->selectPart($day->everyDay());
        }

        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
