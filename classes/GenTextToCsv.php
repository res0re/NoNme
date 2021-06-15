<?
class GenTextToCsv {
  private $nc_core;
  private $table;
  private $fields = [];
  private $elements = [];

  public function __construct($table) {
    $this->nc_core = nc_Core::get_object();
    $this->table = (int) $table;
  }
  
  public function getText($from = 'Windows-1251', $to = 'UTF-8') {
    $file_content = $this->genFieldsString() . $this->genElementsString();
    return $this->convertEncoding($file_content, $from, $to);
  }
  
  private function convertEncoding($file_content,$from,$to) {
      return mb_convert_encoding($file_content, $from, $to);
  }
  
  //generatie fields string
  private function genFieldsString() {
  
    $this->fields = $this->nc_core->db->get_results("Select Description,Field_Name from Field where Class_ID='".$this->table."' order by Field_ID", ARRAY_A);
    
    $fieldsName = '';
    if (!empty($this->fields)) {
      foreach ($this->fields as $field) {
        $fieldsName .= '"' . $field['Description']. '";';
      }
      $fieldsName .= "\n";
      return $fieldsName;
    }
  }

  //generate elements string
  private function genElementsString() {
  
    $this->elements = $this->nc_core->db->get_results("select * from Message".$this->table, ARRAY_A);
    
    $file_content = '';
    if (!empty($this->elements)) {
      foreach ($this->elements as $key=>$element) {
        foreach ($this->fields as $field) {
          $file_content .= '"' . $this->prepareStr($element[$field['Field_Name']]) . '";';
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
}

?>