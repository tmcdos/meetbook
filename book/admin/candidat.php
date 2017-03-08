<?php 
if(recode($_SERVER['PHP_SELF']) != WEBDIR.'/admin/index.php')
{
	exit;
}

  include('../INC/phpmailer.php');
  include('../INC/smtp.php');

$_SESSION['pros']= ($_REQUEST['usr']!=0 ? $_REQUEST['usr'] : max(1,$_SESSION['pros']));

function send_mail($ide)
{
global $user;

  // send Invitation
	$mailer = new PHPMailer();
	$mailer->Encoding = 'quoted-printable';
	if($user->login['EMAIL']!='')
	{
	  $my_adr = $user->login['EMAIL'];
	  if(emailCheck($my_adr) != '') $my_adr = MAIL_SALES;
	  $my_name = $user->login['NAME']; 
	}
	else 
	{
	  $my_adr = MAIL_SALES;
	  $my_name = 'Meetings reservation';
	} 
	$mailer->setFrom($my_adr,$my_name);
	$mailer->Host = 'localhost';
	$mailer->Port = 25;
	$mailer->isSMTP(); 
	$mailer->isHTML();
	$mailer->CharSet = 'utf-8';
  $query = 'SELECT SUBJECT,BODY FROM TEMPLATE WHERE TIP=1 AND USER_ID='.$user->login['ID'];
 	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	if(!mysql_num_rows($result))
 	{
    $query = 'SELECT SUBJ,BODY FROM DEF_MAIL WHERE ID=1';
   	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	}
 	$subj = mysql_result($result,0,0);
 	$body = mysql_result($result,0,1); 
  $query = 'SELECT NAME,EMAIL,LONG_ID FROM PROSPECT WHERE ID='.$ide;
 	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	$pro_name = mysql_result($result,0,0);
 	$pro_mail = mysql_result($result,0,1);
 	$pro_book =mysql_result($result,0,2); 
 	$body = str_replace('{PREP}',WEBDIR,$body);
 	$body = str_replace('{CLIENT}',$pro_name,$body);
 	$body = str_replace('{INVITER}',$my_name,$body);
 	$body = str_replace('{MY_EMAIL}',$my_adr,$body);
 	$body = str_replace('{BOOK}',$_SERVER['SERVER_NAME'].WEBDIR.'/index.php?cl='.$pro_book,$body);

	$mailer->Subject = $subj;
  $mailer->Body = $body;
  $mailer->addAddress($pro_mail,$pro_name);
  if(!$mailer->Send()) return $mailer->ErrorInfo;
  else
  {
    $query = 'UPDATE PROSPECT SET CNT_EMAIL=CNT_EMAIL+1 WHERE ID='.$ide;
   	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
  }
  return '';
}

if(isset($_POST['cmdAdd']))
{
  $ime = ivo_str($_POST['pro_name']);
  $email = ivo_str($_POST['pro_mail']);
  $view = (int)$_POST['pro_view'];
  if($ime == '') $err = 'Name of prospect can not be empty';
  elseif($email == '') $err = 'Missing e-mail address';
  elseif(($err = emailCheck($email)) != '');
  else
  {
    $query = 'INSERT IGNORE INTO PROSPECT(USER_ID,NAME,EMAIL,VIEW_ALL) VALUES('.$user->login['ID'].',"'.mysql_real_escape_string($ime).'","'.mysql_real_escape_string($email).'",'.$view.')';
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
 	  $query = 'UPDATE PROSPECT SET LONG_ID=SHA1(RAND()) WHERE LONG_ID IS NULL';
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
	 	unset($_POST['cmdAdd']);
  }  
}

if($_POST['del_id']!=0)
{
	$query = 'UPDATE PERIOD SET CLIENT_ID=NULL WHERE CLIENT_ID='.$_POST['del_id'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
	$query = 'DELETE FROM PROSPECT WHERE ID='.$_POST['del_id'].' AND USER_ID='.$user->login['ID'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
}

if($_POST['item_id']!=0) $err = send_mail($_POST['item_id']);

if(isset($_POST['cmdAll']))
{
  $query = 'SELECT ID FROM PROSPECT WHERE USER_ID='.$user->login['ID'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
 	while($row = mysql_fetch_array($result,MYSQL_NUM)) 
 	{
 	  $e = send_mail($row[0]);
 	  if($e != '') $err = ($err!='' ? chr(13) : '').$e;
 	}
}

	if($b = @file_get_contents($tmpdir.'/temp/admin/candidat.htm'))
	{
		$b = str_replace('{PREP}',WEBDIR,$b);
		if($err!='') $z = 'alert("'.$err.'");';
			else $z = '';
		$b = str_replace('<!--{ERROR}-->',$z,$b);
		$b = str_replace('{PAGEID}',$_SESSION['pageid'],$b);

		$query = 'SELECT ID,MENU FROM MENU_ADMIN ORDER BY ID';
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
	 	$z = '';
		while($row = mysql_fetch_array($result,MYSQL_NUM))
		{
			$z.='<tr align="center"><td><a href="';
			if($_SESSION['pageid'] != $row[0])
			{
				$z.= WEBDIR.'/admin/index.php?pageid='.$row[0];
				$a = 'yes';
			}
			else
			{
				$z.= 'javascript:;';
				$a = 'no';
			}
			$z.='" class="btn btn-'.$a.'" onClick="javascript: blur();">&nbsp;&nbsp;'.$row[1].'&nbsp;&nbsp;</a></td></tr>';
		}
		$b = str_replace('<tr><td>{LMENU}</td></tr>',$z,$b);

		$b = str_replace('{USR_1}',$_SESSION['pros']==1 ? 'no' : 'yes',$b);
		$b = str_replace('{USR_2}',$_SESSION['pros']==2 ? 'no' : 'yes',$b);

		$b = str_replace('{INLINE}',$_SESSION['pros']==1 ? 'possible' : 'disabled',$b);
		$b = str_replace('{CAN_EDIT}',$_SESSION['pros']==1 ? '1' : '0',$b);
		$b = str_replace('{COL_EDIT}',$_SESSION['pros']==1 ? '#049E19' : 'red',$b);
		
		$show = isset($_POST['cmdAdd']);
		$b = str_replace('{PRO_NAME}',$show ? $ime : '',$b);
		$b = str_replace('{PRO_MAIL}',$show ? $email : '',$b);
		$b = str_replace('{PRO_VIEW}',$show ? ($view ? 'checked' : '') : '',$b);

		echo $b;
	}
	else die('Could not find template - candidat.htm');
?>