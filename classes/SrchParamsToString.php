<?
/*
* Получает параметры поиска из системы администрирования и преобразует в строку get параметра где ключ = названию поля, значение = значение поиска
* $classsID   = номер таблицы на основе которой ведется поиск
* $srchPat    = системный массив с параметрами поиска
* $srchPatAdd = системный массив с параметрами поиска по id
* не работает с полями множественного выбора
*/
class SrchParamsToString {
  //преобразует массив с параметрами поиска в строку с get параметрами
  static function getSrchParamsFieldsToString ($classID, $srchPat) {
    $stringGet = [];
    $fields = self::getFields($classID);
    if (!empty($srchPat)) {
      foreach ($srchPat as $key=>$srchParam) {
        if (!empty($srchParam))
          $stringGet[$fields[$key]] = $srchParam;
      }
      return '&' . http_build_query($stringGet);
    }
  }
  
  //преобразует массив с диапазоном поиска по id в строку с get параметрами
  static function getIdParamsToString ($srchPatAdd) {
      $stringGet = [];
      if (!empty($srchPatAdd[0]))
          $stringGet['fromid'] = $srchPatAdd[0];
          
      if (!empty($srchPatAdd[1]))
          $stringGet['toid'] = $srchPatAdd[1];
          
      return '&' . http_build_query($stringGet);
  }
  
  //объединяет преобразованные строки отбора по параметрам и отбора по id
  static function getIdAndSrchParamsToString($classID, $srchPat, $srchPatAdd) {
      $getQuery = '';
      $srchParams = self::getSrchParamsFieldsToString($classID, $srchPat);
      $idParams = self::getIdParamsToString($srchPatAdd);
      if ($srchParams != '&')
          $getQuery .= $srchParams;
      
      if ($idParams != '&')
          $getQuery .= $idParams;
      return $getQuery;
  }
  
  //получает названия полей из системной таблицы
  static function getFields ($classID) {
    $nc_core = nc_Core::get_object();
    return $nc_core->db->get_col("select Field_Name from Field where Class_ID = " . $classID . " and DoSearch = 1 order by Priority");
  }
}
?>