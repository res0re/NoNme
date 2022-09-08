<?
class queryGetToQueryBase {

  static function getQueryWhereParams($classID, $getParams) {
      $nc_core = nc_Core::get_object();
      $fieldTypes = self::getFieldsTypes($classID, $nc_core);
      $result = $fileType = '';
      if (!empty($getParams)) {
          foreach ($getParams as $key=>$param) {
              $fieldType = self::getFieldType($fieldTypes, $key);
              if ($fieldType) {
                  $result .= " and " . self::replaceAndPrepare(self::getQueryWhereType($fieldType), $key, $param, $nc_core);
              }
          }
      }
      return $result;
  }
  
  static function getQueryWhereId ($getParams, $nameFrom, $nameTo) {
      $from = $getParams[$nameFrom];
      $to = $getParams[$nameTo];
      $result = '';
      if ($from)
          $result .= ' and Message_ID >= ' . $from;
      
      if ($to)
          $result .= ' and Message_ID <= ' . $to;
          
      return $result;
  }
  
  static function getQueryWhereIdAndParams ($classID, $getParams, $nameFrom, $nameTo) {
      return self::getQueryWhereId($getParams, $nameFrom, $nameTo) . self::getQueryWhereParams($classID, $getParams);
  }
  
  static function replaceAndPrepare ($whereType, $paramName, $paramValue, $nc_core) {
      return $paramName . str_replace('#PARAM', $nc_core->db->prepare($paramValue), $whereType);
  }

  static function getQueryWhereType($fieldType) {
    $whereType = '';
    switch ($fieldType) {
      case 1:
          $whereType = ' like "%#PARAM%"';
      break;
      case 4:
          $whereType = ' = "#PARAM"';
      break;
      default:
          $whereType = ' like "%#PARAM%"';
      break;
    }
    return $whereType;
  }
  
  static function getFieldType ($fieldsTypesArr, $fieldName) {
      return $fieldsTypesArr[$fieldName];
  }

  static function getFieldsTypes ($classID, $nc_core) {
    $return = [];
    $results = $nc_core->db->get_results("select Field_Name, TypeOfData_ID from Field where Class_ID = " . $classID . " and DoSearch = 1 order by Priority", ARRAY_A);
    if (!empty($results)) {
      foreach ($results as $row) {
        $return[$row['Field_Name']] = $row['TypeOfData_ID'];
      }
    }
    return $return;
  }
}
?>