<?
/*
Функции которые могут пригодится
*/


//проверяем наличие фотографии
//imageurl - значение из таблицы
function isImage ($imageurl) {
  if (!empty($imageurl)) {
    return $imageurl;
  } else {
    return '/images/tm/img/noimg.jpg';
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
    foreach ($all_arr as $row) {
      $thisid=$row['ThisID'];
      if ($row['ParentID']==$firstparent) {
        if (empty($childparents[$row['ThisID']])) {
          $html.='<li class="structure-item"><span class="structure-title">'.$row['FIO'].'<br> Должность: '.$row['Position'].'<br> Ставка: '.$row['Rate'].'</span></li>';
        } else {
          $html.='<li data-accordion-item class="structure-item"><a class="structure-title" href="#" target="_blank">'.$row['FIO'].'<br> Должность: '.$row['Position'].'<br> Ставка: '.$row['Rate'].'</a><div class="structure-content" data-tab-content><ul class="structure" data-accordion>';
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
        clear_dir($dir.$file.'/');
        rmdir($dir.$file);
        echo $dir.$file.' dir is deleted <br>';
      }
      else {
        unlink($dir.$file);
        echo $dir.$file.' is deleted <br>';
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


#функция для сортировки массива по определенному полю
#$array - массив для сортировки
#$key - ключ по которому будет осуществлятся сортировка
#$sort - сортировка SORT_ASC(по возрастанию) или SORT_DESC(по убыванию)
#Пример использования arraySort($pages,'sort',SORT_ASC)
function arraySort($array, $key = 'sort', $sort = SORT_ASC) 
{
  if ($sort!=SORT_ASC and $sort!=SORT_DESC) 
  {
    $sort=SORT_ASC;
  }
  usort($array, function($a, $b) use ($key, $sort) 
  {
    if ($a == $b) 
    {
      return 0;
    }
    if ($sort==SORT_ASC) {
      return ($a > $b) ? -1 : 1;
    } else {
      return ($a < $b) ? -1 : 1;
    }
  });
  return $array;
}
?>
