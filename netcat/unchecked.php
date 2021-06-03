<?
/*
Универсальный макет для выключения объектов в netcat
для вставки в системные настройки компонента
*/

// игнорируем все системные запросы компонента
$ignore_all=1;

//подготавливаем полученные данные
$messageID = $nc_core->db->prepare($_POST['message']);
$guid = $nc_core->db->prepare($_POST['GuID']);

//если полученные данные не пустые выполняем запрос на отключение объекта
if (!empty($messageID) and !empty($guid)) {
  $queryUpdate = 'Update Message'.$classID.' set Checked = 0 Where Message_ID = "'.$messageID.'" and GuID = "'.$guid.'"';
  $nc_core->db->query($queryUpdate);
}

die;
?>