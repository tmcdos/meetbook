<?php 
if(recode($_SERVER['PHP_SELF']) != WEBDIR.'/admin/index.php')
{
	exit;
}

  include('../INC/phpmailer.php');
  include('../INC/smtp.php');

if($_REQUEST['cal']!=0) $_SESSION['cal'] = $_REQUEST['cal'];
if($_SESSION['cal']==0) $_SESSION['cal'] = 1;
if($_REQUEST['cur_cal']!=0) $_SESSION['cur_cal'] = $_REQUEST['cur_cal'];

function send_mail($pid)
{
global $user;

  // send Cancellation
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
  $query = 'SELECT SUBJECT,BODY FROM TEMPLATE WHERE TIP=3 AND USER_ID='.$user->login['ID'];
 	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	if(!mysql_num_rows($result))
 	{
    $query = 'SELECT SUBJ,BODY FROM DEF_MAIL WHERE TIP=3';
   	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	}
 	$subj = mysql_result($result,0,0);
 	$body = mysql_result($result,0,1); 
  $query = 'SELECT NAME,EMAIL,LONG_ID,TITLE,DATUM,BEG_HOUR,END_HOUR FROM PERIOD 
    LEFT JOIN PROSPECT ON PROSPECT.ID=CLIENT_ID
    LEFT JOIN CALENDAR ON CAL_ID=CALENDAR.ID 
    WHERE PERIOD.ID='.$pid;
 	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	$pro = mysql_fetch_array($result,MYSQL_ASSOC);
 	$body = str_replace('{PREP}',WEBDIR,$body);
 	$body = str_replace('{CLIENT}',$pro['NAME'],$body);
 	$body = str_replace('{CALENDAR}',$pro['TITLE'],$body);
 	$body = str_replace('{MY_EMAIL}',$my_adr,$body);
 	$body = str_replace('{BOOK}',$_SERVER['SERVER_NAME'].WEBDIR.'/index.php?cl='.$pro['LONG_ID'],$body);
 	$body = str_replace('{DATUM}',ADate($pro['DATUM'],DELIM),$body);
 	$body = str_replace('{BEG_TIME}',substr($pro['BEG_HOUR'],0,5),$body);
 	$body = str_replace('{END_TIME}',substr($pro['END_HOUR'],0,5),$body);

	$mailer->Subject = $subj;
  $mailer->Body = $body;
  $mailer->addAddress($pro['EMAIL'],$pro['NAME']);
  if(!$mailer->Send()) return $mailer->ErrorInfo;
  return '';
}

if($_POST['new_name']!='')
{
  $query = 'INSERT IGNORE INTO CALENDAR(USER_ID,TITLE) VALUES('.$user->login['ID'].',"'.mysql_real_escape_string($_POST['new_name']).'")';
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
  header('Location:'.WEBDIR.'/admin/index.php');
  die;
}

if(isset($_POST['cmdNotify']) AND $_SESSION['cur_cal']!=0)
{
  $email = ivo_str($_POST['notify']);
  if($email=='' OR ($err = emailCheck($email)) == '')
  {
    $query = 'UPDATE CALENDAR SET MAIL="'.mysql_real_escape_string($email).'" WHERE ID='.$_SESSION['cur_cal'];
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
  }
}

if(isset($_POST['cmdDel']))
{
  $query = 'DELETE FROM PERIOD WHERE CAL_ID='.(int)$_SESSION['cur_cal'].' AND CLIENT_ID IS NULL';
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
  $query = 'DELETE FROM CALENDAR WHERE ID='.(int)$_SESSION['cur_cal'].' AND NOT EXISTS(SELECT 1 FROM PERIOD WHERE CAL_ID=CALENDAR.ID)';
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
 	unset($_SESSION['cur_cal']);
}

if($_POST['chk_box']!=0)
{
  $query = 'UPDATE CALENDAR SET MULTI_BOOK='.(int)$_POST['multi_book'].' WHERE ID='.(int)$_SESSION['cur_cal'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
}

if(isset($_POST['cmdClear']) OR isset($_POST['cmdClearInfo']))
{
  if(isset($_POST['cmdClearInfo']))
  {
    $query = 'SELECT ID FROM PERIOD WHERE CLIENT_ID IS NOT NULL AND CAL_ID='.(int)$_SESSION['cur_cal'];
   	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
    while($row = mysql_fetch_array($result,MYSQL_NUM)) send_mail($row[0]);
  }
  $query = 'UPDATE PERIOD SET CLIENT_ID=NULL WHERE CAL_ID='.(int)$_SESSION['cur_cal'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
}

	if($b = @file_get_contents($tmpdir.'/temp/admin/calendar.htm'))
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

		$b = str_replace('{CAL_1}',$_SESSION['cal']==1 ? 'no' : 'yes',$b);
		$b = str_replace('{CAL_2}',$_SESSION['cal']==2 ? 'no' : 'yes',$b);

		$query = 'SELECT CALENDAR.ID,USER_ID,TITLE,MAIL,MULTI_BOOK,EXISTS(SELECT 1 FROM PERIOD WHERE CAL_ID=CALENDAR.ID AND CLIENT_ID IS NOT NULL) MARK 
		  FROM CALENDAR '.($_SESSION['cal']!=2 ? 'WHERE USER_ID='.$user->login['ID'] : '').' ORDER BY TITLE';
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
	 	$calendar = Array();
    $vis_mail = 'display:none';
		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{
		  $s = '<a href="'.WEBDIR.'/admin/index.php?cur_cal='.$row['ID'].'" class="small small-'.($_SESSION['cur_cal']==$row['ID'] ? 'yes' : ($row['MARK']!=0 ? 'act' : 'no')).'">'.$row['TITLE'].'</a>';
  	  if($_SESSION['cur_cal']==$row['ID']) 
		  {
			  if($row['USER_ID']==$user->login['ID'])
  		  {
  		    $cur_mail = $row['MAIL'];
  		    $multi_book = $row['MULTI_BOOK'];
  		    unset($vis_mail);
          if($row['MARK']==0) $del_mark = '<div align="center"><input type="submit" class="button2" name="cmdDel" value="Destroy" onClick="javascript: return FinalConfirm(\'Do you really want to delete this calendar ?\');"></div>';
            else $del_mark = '<div align="center">
              <input type="submit" class="button2" name="cmdClear" value="Clear bookings without notification" onClick="javascript: return FinalConfirm(\'Do you really want to cancel ALL bookings ?\');">
              &nbsp;&nbsp;&nbsp;
              <input type="submit" class="button2" name="cmdClearInfo" value="Clear bookings and notify attenders" onClick="javascript: return FinalConfirm(\'Do you really want to cancel ALL bookings ?\');">
              </div>';
        }
        $is_found = true;
  		}
		  $calendar[] = $s;
		  $my_cnt += ($row['USER_ID']==$user->login['ID'] ? 1 : 0);
		}
		$b = str_replace('{CAL_LIST}',implode('<br>',$calendar),$b);

    // show current calendar
    $z = '';
    if($_SESSION['cur_cal']==0)
    {
      if($my_cnt==0) $z = '<br style="font-size:5pt"><b style="color:red;font-size:12pt;">You have not defined any calendars</b>';
        else $z = '<br><br><br><div align="center" style="color:red;font-size:16pt;">Please choose a calendar from the list on the left side.</div>';
    }
    elseif($is_found)
    {
      // prepare table header
      $query = 'SELECT DATUM,BEG_HOUR,END_HOUR,COALESCE(NAME,""),UNIX_TIMESTAMP(ADDTIME(DATUM,BEG_HOUR)),UNIX_TIMESTAMP(ADDTIME(DATUM,END_HOUR)),PERIOD.ID FROM PERIOD
        LEFT JOIN PROSPECT ON CLIENT_ID=PROSPECT.ID
        WHERE CAL_ID='.(int)$_SESSION['cur_cal'].' ORDER BY DATUM,BEG_HOUR,END_HOUR';
  	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
      $tbl = Array();
  	 	while($row = mysql_fetch_array($result,MYSQL_NUM))
  	 	{
  	 	  $slot = substr($row[1],0,5).' - '.substr($row[2],0,5); 
  	 	  $head[$slot] = true;
  	 	  // check for overlaps with other of my calendars
  	 	  $book = Array($row[3]);
	 	    $query = 'SELECT DISTINCT NAME,TITLE,CAL_ID FROM PERIOD LEFT JOIN CALENDAR ON CAL_ID=CALENDAR.ID LEFT JOIN PROSPECT ON PROSPECT.ID=CLIENT_ID 
	 	      WHERE CLIENT_ID IS NOT NULL AND PERIOD.ID<>'.$row[6].' AND CALENDAR.USER_ID='.$user->login['ID'].' AND date_overlap(UNIX_TIMESTAMP(ADDTIME(DATUM,BEG_HOUR)),
	 	      UNIX_TIMESTAMP(ADDTIME(DATUM,END_HOUR)),'.$row[4].','.$row[5].')>1 ORDER BY TITLE,NAME';
	 	    $res = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
        while($over = mysql_fetch_array($res,MYSQL_NUM)) $book[] = '<font color="red">'.$over[0].($over[2]!=$_SESSION['cur_cal'] ? ' ('.$over[1].')' : '').'</font>';  	 	  
  	 	  $tbl[$row[0]][$slot] = $book;
  	 	}
      if(count($tbl)==0) $z = '<br><br><br><div align="center" style="font-size:16pt;color:red;">This calendar has no time slots assigned.</div>';
      else 
      {
        // generate calendar
        $z = '<table class="list_1" cellspacing="0" cellpadding="2" border="1" bordercolor="black"><tr><th>&nbsp;</th>';
        $hdr = '';
        foreach($head as $k=>$v)
          $hdr.= '<th>'.$k.'</th>';
        $z.=$hdr.'</tr>'.chr(13).chr(10);
        foreach($tbl as $den=>$dlist)
        {
          $z.= '<tr align="center"><td bgcolor="#3279F2" style="color:white">&nbsp;'.ADate($den,DELIM).'&nbsp;</td>';
          foreach($head as $vreme=>$s)
          {
            if(!is_array($dlist[$vreme])) $z.= '<td bgcolor="#DBD5CB">&nbsp;</td>';
            elseif($dlist[$vreme][0]=='') $z.= '<td bgcolor="#A0D89E">&nbsp;</td>'; 
            else $z.= '<td style="font-weight:bold">'.implode('<br>',$dlist[$vreme]).'</td>'; 
          }
          $z.='</tr>'.chr(13).chr(10);
        }
        $z.= '<tr><th>&nbsp;</th>'.$hdr.'</tr></table>
          <div style="padding:10px"><b><u>LEGEND</u></b>
            <br><ul style="margin-top:0px">
              <li style="padding:3px"><b style="color:red">overlapped booking</b></li>
              <li><span style="background-color:#A0D89E;padding:2px">&nbsp;free time slot&nbsp;</span></li>
            </ul>
          </div>';
      }
    }
    $z.='<br><br>'.$del_mark;
		$b = str_replace('{CALENDAR}',$z,$b);
 		$b = str_replace('{MAIL}',$cur_mail,$b);
 		$b = str_replace('{MULTI_BOOK}',$multi_book ? 'checked' : '',$b);
 		$b = str_replace('{VIS_MAIL}',$vis_mail,$b);

		echo $b;
	}
	else die('Could not find template - calendar.htm');
?>