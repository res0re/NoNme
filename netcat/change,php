<?
/*
Универсальный обработчик для netcat позволяющий изменять объект с любым набором полей
для вставки в системные настройки компонента
*/

// игнорируем все системные запросы компонента
$ignore_all=1;

//подготавливаем полученные данные
$messageID = $nc_core->db->prepare($_POST['message']);
$guid = $nc_core->db->prepare($_POST['GuID']);

// полуучаем список полей компонента
$curFields = $nc_core->db->get_results("Select * from Field where Class_ID = ".$classID, ARRAY_A );

// форматируем массив
$curFields_arr = [];
foreach ($curFields as $row) {
  $curFields_arr[$row['Field_Name']] = $row['TypeOfData_ID'];
}


if (!empty($messageID) and !empty($guid)) {
   
  //прописываем начало запроса в переменную
  $queryUpdate = 'Update Message'.$classID.' set';
  
  //формируем тело запроса на изменение
  foreach ($_POST as $key => $row) {
    if (isset($curFields_arr[substr($key, 2)]) and $curFields_arr[substr($key, 2)]!=6) {
        $queryUpdate .= ' '.substr($key, 2).'="'.$nc_core->db->prepare($row).'",';
    }
  }

  //проверяем переданы ли файлы
if (!empty($_FILES)) 
{
   //указываем директорию для загрузки файлов
   $uploads_dir = '/web/pps/netcat_files/portfolioFiles/';
   
   //ищем и обрабатываем каждый файл в соответсвие с полями присутствующеми в таблице
   //и добавляем в тело запроса
   foreach ($_FILES as $key => $row) 
   {
      if (isset($curFields_arr[substr($key, 2)]) and $curFields_arr[substr($key, 2)]==6 and $_FILES[$key]['size']>0) 
      {
        $name = basename($classID.'_'.$messageID.'_'.$guid.'_'.$row["name"]);
        $tmp_name = $row["tmp_name"];
        $queryUpdate .= ' '.substr($key, 2).'="'.$uploads_dir.$name.'",';
        move_uploaded_file($tmp_name, "$uploads_dir$name");
      }
   }
}
  
  //убираем последний символ из запроса на изменение
  $queryUpdate = substr($queryUpdate, 0,-1);

  //добавляем условие обновления строки
  $queryUpdate .= ' Where Message_ID = "'.$messageID.'" and GuID = "'.$guid.'"';

  //выполняем запрос
  $nc_core->db->query($queryUpdate);
}


die;
?>
