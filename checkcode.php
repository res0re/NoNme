<?
$NETCAT_FOLDER = join( strstr(__FILE__, "/") ? "/" : "\\", array_slice( preg_split("/[\/\\\]+/", __FILE__), 0, -5 ) ).( strstr(__FILE__, "/") ? "/" : "\\" ).'../'; 
include_once ($NETCAT_FOLDER."vars.inc.php");     
include_once ($NETCAT_FOLDER."netcat/connect_io.php"); 

if ($_GET['key']!='asdlnlsdlfybv2867gvzlskb3872zsxcvnldoubnasevxdyf723') {
  die;
}
if (empty($_GET['phone'])) {
  die;
}
if (empty($_GET['code'])) {
  die;
}

$phone = $nc_core->db->prepare($_GET['phone']);
$code = $nc_core->db->prepare($_GET['code']);



function ($code,$phone) {
$codeintable = $nc_core->db->get_var("select Code from Message1147 where Phone=".$phone." order by Message_ID desc limit 1 ");
 if ($code!=$codeintable){
 	return 'Код не совпадает';
 }
 if ($code==$codeintable) {
 	return 1;
 }
}

?>


https://corp.rgsu.net/netcat/modules/default/lib/sms/checkcode.php?key=asdlnlsdlfybv2867gvzlskb3872zsxcvnldoubnasevxdyf723&code=4330&phone=79035704566