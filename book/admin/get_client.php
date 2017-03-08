<?php 
include_once("../INC/DBASE.PHP");
include_once("../INC/logon.php");
session_start();
timeout();

	$query = 'SELECT PROSPECT.*,EXISTS(SELECT 1 FROM PERIOD WHERE CLIENT_ID=PROSPECT.ID) BOOK 
	  FROM PROSPECT '.($_SESSION['pros']!=2 ? 'WHERE USER_ID='.$user->login['ID'] : '').' ORDER BY NAME';
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
 	$x = Array();
 	$j = 1;
	while($row = mysql_fetch_array($result,MYSQL_ASSOC))
	{
	  $x[] = Array('id'=>$row['ID'], 'data'=>Array($j, $row['NAME'], $row['USER_ID']==$user->login['ID'] ? $row['EMAIL'] : '*** HIDDEN ***', $row['CNT_EMAIL'], $row['VIEW_ALL'], $_SESSION['pros']!=2 ? ($row['BOOK'] ? -$row['ID'] : $row['ID']) : ''));
	  $j++;
	}
	$item['rows'] = $x;
	echo json_encode($item);

?>