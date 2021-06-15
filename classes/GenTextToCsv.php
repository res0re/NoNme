<?
class GenTextToCsv {
  private $nc_core;
  private $table;
  private $fields = [];
  private $elements = [];
  private $limit = 0;
  private $classificators = [];

  public function __construct($table, $limit = 0) {
    $this->nc_core = nc_Core::get_object();
    $this->table = (int) $table;
    if (!empty((int) $limit)) {
      $this->limit = $limit;
    }
  }
  
  //return text csv
  public function getText($from = 'Windows-1251', $to = 'UTF-8') {
    $file_content = $this->genFieldsString() . $this->genElementsString();
    return $this->convertEncoding($file_content, $from, $to);
  }
  
  //converting text
  private function convertEncoding($file_content, $from, $to) {
    return mb_convert_encoding($file_content, $from, $to);
  }

  //generatie fields string
  private function genFieldsString() {
  
    //get fields
    $this->fields = $this->nc_core->db->get_results("Select Description, Field_Name, TypeOfData_ID, Format from Field where Class_ID='" . $this->table . "' and TypeOfData_ID not in (6, 9, 11) order by Field_ID", ARRAY_A);
    
    $fieldsName = '';
    if (!empty($this->fields)) {
      foreach ($this->fields as $field) {
        if ($field['TypeOfData_ID'] == 4) {
            $this->getClassificator($field['Format']);
        }

        $fieldsName .= '"' . $field['Description'] . '";';
      }
      $fieldsName .= "\n";
      return $fieldsName;
    }
  }

  //generate elements string
  private function genElementsString() {
    $limit = '';
    if (!empty($this->limit)) $limit = ' limit ' . $this->limit;
    
    //get elements
    $this->elements = $this->nc_core->db->get_results("select * from Message" . $this->table . $limit, ARRAY_A);
    
    $file_content = '';
    if (!empty($this->elements)) {
      foreach ($this->elements as $key=>$element) {
        foreach ($this->fields as $field) {
          if ($field['TypeOfData_ID'] == 4) {
            $file_content .= '"' . $this->prepareStr($this->classificators[$field['Format']][$element[$field['Field_Name']]]) . '";';
          } else {
            $file_content .= '"' . $this->prepareStr($element[$field['Field_Name']]) . '";';
          }
        }
        $file_content .= "\n";
      }
    }
    return $file_content;
  }

  //prepare string
  private function prepareStr($string) {
    return str_replace('"', '\'', $string);
  }

  //get classificator
  private function getClassificator($name) {
  
    //get classificator
    $classificator = $this->nc_core->db->get_results("select * from Classificator_" . $name, ARRAY_A);
    
    foreach ($classificator as $element) {
      $this->classificators[$name][$element[$name . '_ID']] = $element[$name . '_Name'];
    }
  }
}

?>