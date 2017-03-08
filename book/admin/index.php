<?php 
include_once("../INC/DBASE.PHP");
include_once("../INC/logon.php");
session_start();
timeout();

if($_REQUEST['pageid']!=0) $_SESSION['pageid'] = $_REQUEST['pageid'];
if($_SESSION['pageid']==0) $_SESSION['pageid'] = 1;

	$query = 'SELECT SCRIPT FROM MENU_ADMIN WHERE ID='.$_SESSION['pageid'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
 	if(mysql_num_rows($result))	include(mysql_result($result,0,0).'.php');

?>