<?php
namespace App\Searchs;

use App\Models\Users\User;

class SearchResultFactories{

  /**
   *
   * @param string $keyword
   * @param string $category
   * @param string $updown
   * @param string $gender
   * @param string $role
   * @param mixed $subjects
   * @return object
   */
  public function initializeUsers($keyword, $category, $updown, $gender, $role, $subjects){

    $shouldUseDetailSearch = !empty($subjects);

    if($category == 'name'){
      if(!$shouldUseDetailSearch){
        $searchResults = new SelectNames();
      }else{
        $searchResults = new SelectNameDetails();
      }
      return $searchResults->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    }else if($category == 'id'){
      if(!$shouldUseDetailSearch){
        $searchResults = new SelectIds();
      }else{
        $searchResults = new SelectIdDetails();
      }
      return $searchResults->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    }else{
      $allUsers = new AllUsers();
      return $allUsers->resultUsers($keyword, $category, $updown, $gender, $role, $subjects);
    }
  }
}
