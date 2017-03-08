<?php 
include_once("./INC/DBASE.PHP");

  include('./INC/phpmailer.php');
  include('./INC/smtp.php');

  // find client ID
  $query = 'SELECT ID FROM PROSPECT WHERE LONG_ID="'.$_REQUEST['cl'].'"';
 	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	if(mysql_num_rows($result)) $uid = mysql_result($result,0,0);

function send_mail($tip,$pid)
{
  // find owner of the calendar
  $query = 'SELECT NAME,EMAIL,LONG_ID,TITLE,MAIL,DATUM,BEG_HOUR,END_HOUR,PROSPECT.USER_ID FROM PERIOD 
    LEFT JOIN PROSPECT ON PROSPECT.ID=CLIENT_ID
    LEFT JOIN CALENDAR ON CAL_ID=CALENDAR.ID 
    WHERE PERIOD.ID='.$pid;
 	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	$pro = mysql_fetch_array($result,MYSQL_ASSOC);
  // send email notification
	$mailer = new PHPMailer();
	$mailer->Encoding = 'quoted-printable';
	$mailer->Host = 'localhost';
	$mailer->Port = 25;
	$mailer->isSMTP(); 
	$mailer->CharSet = 'utf-8';
  $query = 'SELECT SUBJECT,BODY FROM TEMPLATE WHERE TIP='.$tip.' AND USER_ID='.(int)$pro['USER_ID'];
 	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	if(!mysql_num_rows($result))
 	{
    $query = 'SELECT SUBJ,BODY FROM DEF_MAIL WHERE ID='.$tip;
   	$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
 	}
 	$subj = mysql_result($result,0,0);
 	$body = mysql_result($result,0,1); 

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
	
	if($pro['MAIL']!='')
	{
	  $my_adr = $pro['MAIL'];
	  if(emailCheck($my_adr) != '') $my_adr = MAIL_SALES;
      else $mailer->addCC($pro['MAIL']);
	  $my_name = $pro['TITLE']; 
	}
	else 
	{
	  $my_adr = MAIL_SALES;
	  $my_name = 'Meetings reservation';
	} 
	$mailer->setFrom($my_adr,$my_name);
  $mailer->addAddress($pro['EMAIL'],$pro['NAME']);
  if(!$mailer->Send()) return $mailer->ErrorInfo;
  return '';
}

if(is_array($_POST['cmdBook']) AND count($_POST['cmdBook'])>0) foreach($_POST['cmdBook'] as $k=>$v)
{
  if($uid!=0)
  {
    $query = 'UPDATE PERIOD SET CLIENT_ID='.$uid.' WHERE CLIENT_ID IS NULL AND ID='.$k;
   	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
   	if(mysql_affected_rows())
   	{
   	  // send notification to client and dealer
   	  send_mail(2,$k);
   	}
  }
}

if(is_array($_POST['cmdCancel']) AND count($_POST['cmdCancel'])>0) foreach($_POST['cmdCancel'] as $k=>$v)
{
  if($uid!=0)
  {
    $query = 'UPDATE PERIOD SET CLIENT_ID=NULL WHERE CLIENT_ID='.$uid.' AND ID='.$k;
   	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
   	if(mysql_affected_rows())
   	{
   	  // send notification to client and dealer
   	  send_mail(3,$k);
   	}
  }
}

	if($b = @file_get_contents($tmpdir.'/temp/book.htm'))
	{
		$b = str_replace('{PREP}',WEBDIR,$b);
		if($err!='') $z = 'alert("'.$err.'");';
			else $z = '';
		$b = str_replace('<!--{ERROR}-->',$z,$b);
 		$b = str_replace('{BOOK_ID}',$_REQUEST['cl'],$b);

    if($uid!=0)
    {
      $author = (int)a_select('PROSPECT',$uid,'USER_ID');
  		$query = 'SELECT ID,TITLE,MAIL,MULTI_BOOK FROM CALENDAR WHERE USER_ID='.$author.' ORDER BY TITLE';
  	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
 		  $cnt = mysql_num_rows($result);
  	 	$list = Array();
  		while($row = mysql_fetch_array($result,MYSQL_ASSOC))
  		{
  		  if($_REQUEST['cal']==0) $_REQUEST['cal'] = $row['ID'];
  		  if($cnt==1 OR $_REQUEST['cal']==$row['ID']) $title = $row['TITLE'];
  		  $list[] = '<div style="padding:3px"><a href="'.WEBDIR.'/index.php?cl='.$_REQUEST['cl'].'&cal='.$row['ID'].'" class="btn btn-'.($_REQUEST['cal']==$row['ID'] ? 'no' : 'yes').'">'.$row['TITLE'].'</a></div>';
  		}
  		$b = str_replace('{CAL_LIST}',$cnt>1 ? implode(chr(13).chr(10), $list) : '',$b);
  
      // prepare table header
      $query = 'SELECT DATUM,BEG_HOUR,END_HOUR,COALESCE(NAME,""),UNIX_TIMESTAMP(ADDTIME(DATUM,BEG_HOUR)),UNIX_TIMESTAMP(ADDTIME(DATUM,END_HOUR)),PERIOD.ID,CLIENT_ID FROM PERIOD
        LEFT JOIN PROSPECT ON CLIENT_ID=PROSPECT.ID
        WHERE CAL_ID='.(int)$_REQUEST['cal'].' ORDER BY DATUM,BEG_HOUR,END_HOUR';
  	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
      $tbl = Array();
  	 	while($row = mysql_fetch_array($result,MYSQL_NUM))
  	 	{
  	 	  $slot = substr($row[1],0,5).' - '.substr($row[2],0,5); 
  	 	  $head[$slot] = true;
  	 	  $book = Array();
  	 	  if($row[7]==$uid) $z = '<input type="submit" class="button3" name="cmdCancel['.$row[6].']" value="CANCEL">';
  	 	  elseif($row[7]==0) $z = ''; // empty slot
  	 	  else $z = '^'; // booked by someone else
  	 	  $book[] = $z;
  	 	  if($z!='' AND $z!='^')
  	 	  {
    	 	  // check for overlaps with my bookings in other calendars
  	 	    $query = 'SELECT DISTINCT CAL_ID,TITLE,LEFT(BEG_HOUR,5),LEFT(END_HOUR,5) FROM PERIOD LEFT JOIN CALENDAR ON CAL_ID=CALENDAR.ID LEFT JOIN PROSPECT ON PROSPECT.ID=CLIENT_ID 
  	 	      WHERE PERIOD.ID<>'.$row[6].' AND CLIENT_ID='.$uid.' AND date_overlap(UNIX_TIMESTAMP(ADDTIME(DATUM,BEG_HOUR)),
  	 	      UNIX_TIMESTAMP(ADDTIME(DATUM,END_HOUR)),'.$row[4].','.$row[5].')>1 ORDER BY TITLE,NAME';
  	 	    $res = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
          while($over = mysql_fetch_array($res,MYSQL_NUM)) $book[] = '<font color="red">'.($over[0]==$_REQUEST['cal'] ? $over[2].' - '.$over[3] : $over[1].' ('.$over[2].' - '.$over[3].')').'</font>';
        }
  	 	  $tbl[$row[0]][$slot] = $book;
  	 	  $period[$row[0]][$slot] = $row[6];
  	 	}
      if(count($tbl)==0) $z = '<br><div align="center" style="font-size:16pt;color:red;">This meeting has no time slots assigned.</div><br>';
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
            if(!is_array($dlist[$vreme])) $z.= '<td bgcolor="#DBD5CB">&nbsp;</td>'; // no time slot here
            elseif($dlist[$vreme][0]=='') $z.= '<td bgcolor="#A0D89E"><input type="submit" class="button3" style="background-color:white" name="cmdBook['.$period[$den][$vreme].']" value="BOOK"></td>';
            elseif($dlist[$vreme][0]=='^') $z.= '<td bgcolor="#FFD6F6">&nbsp;</td>'; // reserved by someone else  
            else $z.= '<td style="font-weight:bold">'.implode('<br>',$dlist[$vreme]).'</td>'; 
          }
          $z.='</tr>'.chr(13).chr(10);
        }
        $z.= '<tr><th>&nbsp;</th>'.$hdr.'</tr></table>
          <div style="padding:10px"><b><u>LEGEND</u></b>
            <br><ul style="margin-top:0px">
              <li style="padding:3px"><b style="color:red">overlapped booking</b></li>
              <li style="padding:3px"><span style="background-color:#A0D89E;padding:2px">&nbsp;free time slot&nbsp;</span></li>
              <li style="padding:3px"><span style="background-color:#FFD6F6;padding:2px">&nbsp;occupied time slot&nbsp;</span></li>
            </ul>
          </div>';
      }
      if($_SERVER['REMOTE_ADDR']=='11.22.33.44') $z.='<a href="'.WEBDIR.'/admin/index.php" class="small new_cal">Administrative settings</a>';
  		$b = str_replace('{CALENDAR}',$cnt!=0 ? $z : '',$b);

  		if($cnt==0) $z = '<b style="color:red">Sorry, your inviter has not created any meeting yet</b>';
  		elseif($cnt==1) $z = '<a href="#" class="btn btn-yes">'.$title.'</a>';
  		elseif($_REQUEST['cal']==0) $z = '<b style="color:red">Choose a meeting from the list on the left side</b>';
  		else $z = ''; 
   		$b = str_replace('{CAL_MSG}',$z,$b);
    }
    else
    {
      if($_SERVER['REMOTE_ADDR']=='11.22.33.44') $z ='<a href="'.WEBDIR.'/admin/index.php" class="small new_cal">Administrative settings</a>';
        else $z = '';
   		$b = str_replace('{CAL_MSG}',$z,$b);
    }
 		$b = str_replace('{VIS_CAL}',$uid!=0 ? '' : 'display:none',$b);
 		$b = str_replace('{VIS_WARN}',$uid!=0 ? 'display:none' : '',$b);
 		$b = str_replace('{MEET}',$title!='' ? $title : 'UNKNOWN title',$b);
 		$b = str_replace('{VIS_MEET}',count($list)>0 ? '' : 'display:none',$b);
 		$b = str_replace('{CAL_ID}',$_REQUEST['cal'],$b);
    
		echo $b;
	}
	else die('Could not find template - book.htm');
?>