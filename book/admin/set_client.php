<?php 
include_once("../INC/DBASE.PHP");
include_once("../INC/logon.php");
session_start();
timeout();

header('Content-Type: text/xml');
header('Cache-Control: no-cache');

if($_REQUEST['gr_id']!=0 AND $_REQUEST['!nativeeditor_status']=='updated')
{
  $ime = $_REQUEST['name'];
  $email = $_REQUEST['email'];
  $view = (int)$_REQUEST['view_any'];
  if($ime == '') $err = 'Name of prospect can not be empty';
  elseif($email == '') $err = 'Missing e-mail address';
  elseif(($err = emailCheck($email)) != '');
  if($err!='') echo '<?xml version="1.0" encoding="utf-8"?><data><action type="error" sid="'.$_REQUEST['gr_id'].'" tid="'.$_REQUEST['gr_id'].'">'.$err.'</action></data>';
  else
  {   
    $query = 'UPDATE PROSPECT SET NAME="'.mysql_real_escape_string($ime).'",EMAIL="'.mysql_real_escape_string($email).'",VIEW_ALL='.$view.' WHERE ID='.$_REQUEST['gr_id'];  
   	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
 	  $query = 'UPDATE PROSPECT SET LONG_ID=SHA1(RAND()) WHERE LONG_ID IS NULL AND ID='.$_REQUEST['gr_id'];
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
   	echo '<?xml version="1.0" encoding="utf-8"?><data><action type="update" sid="'.$_REQUEST['gr_id'].'" tid="'.$_REQUEST['gr_id'].'" /></data>';
  }
}
else
{
 	echo '<?xml version="1.0" encoding="utf-8"?><data><action type="invalid" sid="'.$_REQUEST['gr_id'].'" tid="'.$_REQUEST['gr_id'].'" /></data>';
}

?>