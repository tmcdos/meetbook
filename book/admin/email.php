<?php 
if(recode($_SERVER['PHP_SELF']) != WEBDIR.'/admin/index.php')
{
	exit;
}

if($_REQUEST['tmp']!=0) $_SESSION['template'] = $_REQUEST['tmp'];
if($_SESSION['template']==0) $_SESSION['template'] = 1;

if(isset($_POST['cmdDef']))
{
  $query = 'REPLACE INTO TEMPLATE(USER_ID,TIP,SUBJECT,BODY) SELECT '.$user->login['ID'].',ID,SUBJ,BODY FROM DEF_MAIL WHERE ID='.$_SESSION['template'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
}

if(isset($_POST['cmdSave']))
{
  $sbj = ivo_str($_POST['subject']);
  $eml = ivo_str2($_POST['template']);
  $query = 'REPLACE INTO TEMPLATE(USER_ID,TIP,SUBJECT,BODY) VALUES('.$user->login['ID'].','.$_SESSION['template'].',"'.mysql_real_escape_string($sbj).'","'.mysql_real_escape_string($eml).'")';
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
}

	if($b = @file_get_contents($tmpdir.'/temp/admin/email.htm'))
	{
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

		$b = str_replace('{TMP_1}',$_SESSION['template']==1 ? 'no' : 'yes',$b);
		$b = str_replace('{TMP_2}',$_SESSION['template']==2 ? 'no' : 'yes',$b);
		$b = str_replace('{TMP_3}',$_SESSION['template']==3 ? 'no' : 'yes',$b);

    // load template
		$query = 'SELECT SUBJECT,BODY FROM TEMPLATE WHERE USER_ID='.$user->login['ID'].' AND TIP='.(int)$_SESSION['template'];
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
	 	if(mysql_num_rows($result))
	 	{
  	 	$sub = mysql_result($result,0,0);
  	 	$body = mysql_result($result,0,1);
  	}
		$b = str_replace('{SUBJECT}',$sub,$b);
		$b = str_replace('{TEMP}',$body,$b);
		// get available commands
		if($_SESSION['template']!=0)
		{
  		$query = 'SELECT CMD,TXT FROM TEMP_CMD WHERE FLG_'.(int)$_SESSION['template'];
  	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
  	 	$z = '';
  	 	while($row = mysql_fetch_array($result,MYSQL_NUM))
  	 	  $z.= '<br><b>&#123;'.strtoupper($row[0]).'&#125;</b> = '.$row[1];
  	}
  	else $z = '';
		$b = str_replace('{TEMP_CMD}',$z,$b);

		$b = str_replace('{PREP}',WEBDIR,$b);
		if($err!='') $z = 'alert("'.$err.'");';
			else $z = '';
		$b = str_replace('<!--{ERROR}-->',$z,$b);
		$b = str_replace('{PAGEID}',$_SESSION['pageid'],$b);

		echo $b;
	}
	else die('Could not find template - email.htm');
?>