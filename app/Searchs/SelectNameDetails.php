<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectNameDetails implements DisplayUsers{

  /**
   *
   * @param string $keyword
   * @param string $category
   * @param string|null $updown
   * @param string|null $gender
   * @param string|null $role
   * @param mixed $subjects
   * @return \Illuminate\Support\Collection
   */
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){

    $genderArray = is_null($gender) ? ['1', '2', '3'] : [$gender];
    $roleArray = is_null($role) ? ['1', '2', '3', '4'] : [$role];

    $query = User::with('subjects')
    ->where(function($q) use ($keyword){
        $q->where('over_name', 'like', '%'.$keyword.'%')
        ->orWhere('under_name', 'like', '%'.$keyword.'%')
        ->orWhere('over_name_kana', 'like', '%'.$keyword.'%')
        ->orWhere('under_name_kana', 'like', '%'.$keyword.'%');
    })
    ->whereIn('sex', $genderArray)
    ->whereIn('role', $roleArray);


    if (!empty($subjects)) {
        $subjectsArray = is_array($subjects) ? $subjects : [$subjects];

        $query->whereHas('subjects', function($q) use ($subjectsArray){
            $q->whereIn('subject_id', $subjectsArray);
        });
    }
    $users = $query->orderBy('over_name_kana', $updown)->get();

    return $users;
  }
}
