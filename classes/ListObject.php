<?php
class ListObject {
  
  static function getObjectList ($listname) {
    $objectList = array();
    $res = self::getResults($listname);
    if(!empty($res)){ 
      foreach($res as $i){ 
        $objectList[$i->id]=$i->name; 
      } 
    }
    return $objectList;
  }
  
  static function getResults($listname) {
      $nc_core = nc_Core::get_object();
      return $nc_core->db->get_results('SELECT ' . $listname . '_ID AS id, ' . $listname . '_Name AS name FROM Classificator_' . $listname . ' WHERE Checked=1 ORDER BY ' . $listname . '_Name');
  }
}

?>