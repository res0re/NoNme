<?
//проверяем наличие фотографии
//imageurl - значение из таблицы
function imageThere ($imageurl) {
  if (!empty($imageurl)) {
    return $imageurl;
  } else {
    return '/images/tm/img/noimg.jpg';
  }
}

//приводим дату к нормальному виду
//day - день
//month - месяц
//months_inner - массив с месяцами
function dataWithMonth($day,$month,$year,$months_inner) {
    return $day.' '.$months_inner[$month].', '.$year;
}


// вывод даты в нормальном формате даты и времени дд.мм.гггг и времени чч:мм из формата даты mysql
//$date - дата из таблицы mysql 
//$separator - разделитель для даты
//$time - 1 - вывести время 0 - не выводить время
//$dateenable - 1 вывести дату 0 - не выводить дату
//$separatorTime - разделитель для времени
function createDateFromTable($date,$separator='.',$time='0',$dateenable='1',$separatorTime=':') {
  $date = explode(' ', $date);
  if ($time==1) {
    $time=explode(':',$date[1]);
    $time = ' '.$time[0].$separatorTime.$time[1].$separatorTime.$time[2];
  } else {
    $time='';
  }
  if ($dateenable==1) {
    $date = explode('-', $date[0]);
    return $date[2].$separator.$date[1].$separator.$date[0].$time;
  } else {
    return $time;
  }
}


#функция вывода бесконечно вложенных списков штатки
#$all_arr - массив с данными полученный запросом
#$childparents - массив с количеством дочерних эллементов в списке
#$firstparent - id эллемента который является родителем для всех эллементов 1-го уровня
function childParent ($all_arr,$childparents,$firstparent) {
  
  $html='<ul class="structure" data-accordion>';
  $parent='';
  $thisid='';
  
  if (!empty($all_arr)) {
    foreach ($all_arr as $rowx) {
      $thisid=$rowx['ThisID'];
      if ($rowx['ParentID']==$firstparent) {
        if (empty($childparents[$rowx['ThisID']])) {
          $html.='<li class="structure-item"><span class="structure-title">'.$rowx['FIO'].'<br> Должность: '.$rowx['Position'].'<br> Ставка: '.$rowx['Rate'].'</span></li>';
        } else {
          $html.='<li data-accordion-item class="structure-item"><a class="structure-title" href="#" target="_blank">'.$rowx['FIO'].'<br> Должность: '.$rowx['Position'].'<br> Ставка: '.$rowx['Rate'].'</a><div class="structure-content" data-tab-content><ul class="structure" data-accordion>';
          $html.=childParent($all_arr,$childparents,$thisid);
          $html.='</ul></div></li>';
        }
      }
    }
  }

  $html.='</ul>';
  return $html;
}

#получение всех файлов в директории
#$dir - директория 
#$defoultseparator - дефолтный разделитель пути
function myscandir($dir,$defoultseparator='/')
{
  $dir = str_replace($defoultseparator,DIRECTORY_SEPARATOR,$dir);
  $list = scandir($dir);
  foreach ($list as $k => $v)  {
    if ($v=='.' or $v=='..'){ 
      unset($list[$k]);
    }
  }
  return array_values($list);
}

#получить дату создания файла с возможностью выбора формата
#$file - имя файла
#$dir - директория
#$dateformat - формат вывода даты (дефолт ггггммдд)
function datefile($file,$dir,$dateformat='Ymd') {
  $date=date($dateformat, filectime($dir.$file));
  return $date;
}


#удаление файлов из дериктории
#используется вместе с функцией myscandir и функцией datefile
#$dir - директория
#$date - дата до которой будут удаляться файлы (по умолчанию текущая дата)
#$limited - выставить лимит обработки (по умолчанию отклбчено)
#$limit - лимит обработки (по умолчанию 1000)
function clear_dir($dir,$date=0,$limited=0,$limit=1000) {
  if ($date==0) {
    $date=date('Ymd');
  }
  
  if ($limited==1) {
    $i=1;
  }
  
  $list = myscandir($dir);
  
  foreach ($list as $file) {
    if ($date>datefile($file,$dir)) {
    echo $date.'>'.datefile($file,$dir).'<br>';
      if (is_dir($dir.$file)) {
        //clear_dir($dir.$file.'/');
        //rmdir($dir.$file);
        //echo $dir.$file.' dir is deleted <br>';
      }
      else {
        //unlink($dir.$file);
        //echo $dir.$file.' is deleted <br>';
      }
      
      if ($limited==1) {
        $i++;
        if ($i==$limit) {
          exit;
        }
      }
      
    }
  }
}



#формирует строку для поиска по таблице
#$getpar - строка с поисковыми значениями прописанными через ';'
#$fieldname - название поля по которому ведется поиск
#$separator - разделитель между поисковыми значениями в строке $getpar. По умолчанию ';'
#выходные данные типа: and (1=0 or fieldname=getpar[0] or fieldname=getpar[1] ...)
function getSearch($getpar='',$fieldname='',$separator=';') {
  $getpar=explode($separator,$getpar);
  $x='';
  if (!empty($getpar)){
    foreach ($getpar as $rowes) {
      if (!empty($rowes)) {
        $x.=' or '.$fieldname.'="'.$rowes.'"';
      }
    }
    if (!empty($x)) {
      $x=' and (1=0 '.$x.')';
      return $x;
    }
  }
}

#формируем поисковой запрос
$query_where='1=1';
$query_where.=getSearch($nc_core->db->prepare($_GET['position']),'VacancyName');
$query_where.=getSearch($nc_core->db->prepare($_GET['division']),'Division');
$query_where.=getSearch($nc_core->db->prepare($_GET['type']),'TypePosition');

#test change whith git
#another change at file

?>