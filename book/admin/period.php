<?php 
if(recode($_SERVER['PHP_SELF']) != WEBDIR.'/admin/index.php')
{
	exit;
}

// return HTML for 4 months starting at the given month
function calendaren($mon,$yar)
{
global $day_list;

	$my_time = mktime(0,0,0,$mon,1,$yar);
	$start_day = date('N', $my_time);
	$start_week = (int)date('W',$my_time); // because date() returns zero-padded value
	$daysIM = date('t',$my_time);
	$dayn = date('z',$my_time)+1;
	$today = date('z')+1;
	$godina = date('Y');

	// show first week
	$dd = 1;
	$daye = 1;
	$rows[0] = '<td class="week_num">'.($start_week++).'</td>';
	if($start_week>52) $start_week = 1;
	while($dd < $start_day)
	{
		$rows[0].= '<td class="day" bgcolor="#DBD5CB" style="cursor:default">&nbsp;</td>'; // days from the previous year
		$dd++;
	}

	while($dd <= 7) // days in 1st week of the chosen year
	{
		$bgcolor = '';
		$ch = true;
		if($_SESSION['cal_date']==$dayn) 
		{
		  $bgcolor = 'day_blue';
		  $ch = false;
		}
		elseif($day_list[$dayn]) $bgcolor = 'day_green';
		elseif($yar<$godina OR ($dayn<$today AND $yar==$godina)) $bgcolor = 'day_red';
		//elseif($dd>5) $bgcolor = '';
		$rows[0].= '<td class="day '.$bgcolor.'">
		  <span class="week_'.($dd>5 ? 'end' : 'day').'" style="padding:2px;'.($_SESSION['cal_date']==$dayn ? 'color:white' : '').'" '.($_SESSION['cal_date']!=$dayn ? 'onClick="javascript: with(document.admin) { dayn.value='.$dayn.'; submit(); }"' : '').'>'
		  .($daye++)
		  .'</span>'.($ch ? '<input type="checkbox" name="chk['.$dayn.']" value="1">' : '').'</td>'; // dayn
		$dd++;
		$dayn++;
	}

	$red = 1; // horizontal row of the calendar table
	while($daye <= $daysIM)
	{
		$rows[$red] = '<td class="week_num">'.($start_week++).'</td>';
		if($start_week>52) $start_week = 1;
		$dd = 1;
		while($dd <= 7)
		{
			$bgcolor = '';
			$ch = true;
  		if($_SESSION['cal_date']==$dayn) 
  		{
  		  $bgcolor = 'day_blue';
  		  $ch = false;
  		}
			elseif($day_list[$dayn]) $bgcolor = 'day_green';
			elseif($yar<$godina OR ($dayn<$today AND $yar==$godina)) $bgcolor = 'day_red';
			//elseif($dd>5) $bgcolor = '';
			if($daye <= $daysIM) $rows[$red].= '<td class="day '.$bgcolor.'">
			  <span class="week_'.($dd>5 ? 'end' : 'day').'" style="padding:2px;'.($_SESSION['cal_date']==$dayn ? 'color:white' : '').'" '.($_SESSION['cal_date']!=$dayn ? 'onClick="javascript: with(document.admin) { dayn.value='.$dayn.'; submit(); }"' : '').'>'
			  .($daye++)
			  .'</span>'.($ch ? '<input type="checkbox" name="chk['.$dayn.']" value="1">' : '').'</td>'; // dayn
				else $rows[$red].= '<td class="day" bgcolor="#DBD5CB" style="cursor:default">&nbsp;</td>';
			$dd++;
			$dayn++;
		}
		$red++;
	}
	return $rows;
}

function weeks($god,$mes)
{
	$mesec_1 = calendaren($mes,$god);
	$mesec_2 = calendaren($mes+1,$god);
	$mesec_3 = calendaren($mes+2,$god);
	$mesec_4 = calendaren($mes+3,$god);
	$k = max(count($mesec_1),count($mesec_2),count($mesec_3),count($mesec_4));
	$g = '';
	for($i = 1; $i <= 8; $i++)
		$g.= '<td class="day" bgcolor="#DBD5CB" style="cursor:default">&nbsp;</td>';
	for($i = 0; $i < $k; $i++)
	{
		$z.= '<tr align="center">';
		if($mesec_1[$i]!='') $z.= $mesec_1[$i];
			else $z.= $g;
		if($mesec_2[$i]!='') $z.= $mesec_2[$i];
			else $z.= $g;
		if($mesec_3[$i]!='') $z.= $mesec_3[$i];
			else $z.= $g;
		if($mesec_4[$i]!='') $z.= $mesec_4[$i];
			else $z.= $g;
		$z.= '</tr>'.chr(13).chr(10);
	}
	return $z;
}

if($_POST['godina']!=0) $_SESSION['godina'] = $_POST['godina'];
if($_SESSION['godina']==0) $_SESSION['godina'] = date('Y');

if($_POST['cmdPrev_x']!='' OR $_POST['cmdPrev_y']!='') $_SESSION['godina']-= 1;
if($_POST['cmdNext_x']!='' OR $_POST['cmdNext_y']!='') $_SESSION['godina']+= 1;
if($_POST['dayn']!=0) $_SESSION['cal_date'] = $_POST['dayn'];
if($_REQUEST['cal']!=0) 
{
  $_SESSION['cur_cal'] = $_REQUEST['cal'];
  $_SESSION['cal_date'] = 0;
} 

if($_REQUEST['del_slot']!='')
{
  $query = 'DELETE FROM PERIOD WHERE CAL_ID='.(int)$_SESSION['cur_cal'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
}

if(isset($_POST['cmdCopy']) AND is_array($_POST['chk']))
{
 	foreach($_POST['chk'] as $k=>$v)
 	  if($v)
   	{
      $query = 'DELETE FROM PERIOD WHERE CAL_ID='.(int)$_SESSION['cur_cal'].' AND DATUM=MAKEDATE('.$_SESSION['godina'].','.$k.')';
     	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
     	$query = 'INSERT INTO PERIOD(CAL_ID,DATUM,BEG_HOUR,END_HOUR) SELECT '.(int)$_SESSION['cur_cal'].',MAKEDATE('.$_SESSION['godina'].','.$k.'),BEG_HOUR,END_HOUR 
     	  FROM PERIOD WHERE CAL_ID='.(int)$_SESSION['cur_cal'].' AND DATUM=MAKEDATE('.$_SESSION['godina'].','.(int)$_SESSION['cal_date'].')';
     	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
    }
}

if(isset($_POST['cmdClear']) AND $_SESSION['cal_date']!=0)
{
  $query = 'DELETE FROM PERIOD WHERE CAL_ID='.(int)$_SESSION['cur_cal'].' AND DATUM=MAKEDATE('.$_SESSION['godina'].','.$_SESSION['cal_date'].')';
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
}

if(isset($_POST['cmdDel']))
{
  $query = 'DELETE FROM PERIOD WHERE CAL_ID='.(int)$_SESSION['cur_cal'];
 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
}

if(isset($_POST['cmdSlot']) AND $_SESSION['cur_cal']!=0 AND $_SESSION['cal_date']!=0)
{
  list($beg_h,$beg_m) = explode(':',$_POST['beg_time']);  
  list($end_h,$end_m) = explode(':',$_POST['end_time']);
  $num_s = (int)$_POST['num_slot'];
  $size_s = (int)$_POST['slot_size'];
  $t_beg = $beg_h * 60 + $beg_m;
  $t_end = $end_h * 60 + $end_m;
  if($t_beg < 1 OR $t_beg > 1440) $err = 'Invalid starting time';
  elseif($t_end < 1 OR $t_end > 1440) $err = 'Invalid ending time';
  elseif($t_end <= $t_beg) $err = 'Starting time ('.$t_beg.') is AFTER the ending time ('.$t_end.')';
  elseif($num_s<1 AND $size_s<10) $err = 'Missing number of time slots - or too short size';
  elseif($num_s!=0 AND ($t_end - $t_beg)/$num_s < 10) $err = 'Too many time slots - maximum of '.intval(($t_end - $t_beg)/10).' are possible';
  elseif($size_s > $t_end - $t_beg) $err = 'Too large time slots - maximum of '.($t_end - $t_beg).' minutes are possible';
  else
  {
    if($num_s > 0) $leng = ($t_end - $t_beg) / $num_s;
      else $leng = $size_s;
    while($t_beg < $t_end)
    {
      $next = $t_beg + $leng;
      $query = 'REPLACE INTO PERIOD(CAL_ID,DATUM,BEG_HOUR,END_HOUR) VALUES('.$_SESSION['cur_cal'].',MAKEDATE('.$_SESSION['godina'].','.$_SESSION['cal_date'].'),"'.intval($t_beg/60).':'.($t_beg % 60).':00","'.intval($next/60).':'.($next % 60).':00")';
  	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
      $t_beg = $next;
    }
  }  
}

	if($b = @file_get_contents($tmpdir.'/temp/admin/period.htm'))
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

    // show calendars
    $query = 'SELECT ID,TITLE FROM CALENDAR WHERE USER_ID='.$user->login['ID'].' ORDER BY TITLE';
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
    $x = Array();
    while($row = mysql_fetch_array($result,MYSQL_NUM))    
    {
      if($_SESSION['cur_cal']==0) $_SESSION['cur_cal'] = $row[0];
      $x[] = '<a href="'.WEBDIR.'/admin/index.php?cal='.$row[0].'" class="btn btn-'.($_SESSION['cur_cal']==$row[0] ? 'no' : 'yes').'">'.$row[1].'</a>';
    }
    if(count($x)==0) $_SESSION['cur_cal'] = 0;
		$b = str_replace('{CAL_LIST}',implode(' ',$x),$b);
    
    // preload time slots 
		$b = str_replace('{VIS_TIME}',$_SESSION['cal_date']!=0 ? '' : 'display:none',$b);
    $query = 'SELECT DAYOFYEAR(DATUM),BEG_HOUR,END_HOUR,NAME,UNIX_TIMESTAMP(ADDTIME(DATUM,BEG_HOUR)),UNIX_TIMESTAMP(ADDTIME(DATUM,END_HOUR)),PERIOD.ID FROM PERIOD 
      LEFT JOIN PROSPECT ON PROSPECT.ID=CLIENT_ID 
      WHERE CAL_ID='.(int)$_SESSION['cur_cal'].' AND YEAR(DATUM)='.$_SESSION['godina'].' ORDER BY DATUM,BEG_HOUR';
	 	$result = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
	 	$day_list = Array();
	 	$slot = '';
	 	while($row = mysql_fetch_array($result,MYSQL_NUM)) 
	 	{
	 	  $day_list[$row[0]] = true;
	 	  if($row[0]==$_SESSION['cal_date'])
	 	  {
	 	    $slot .= '<tr align="center"><td><a href="'.WEBDIR.'/admin/index.php?del_slot='.$row[1].'" class="tit"><img src="'.WEBDIR.'/images/stop.gif" alt="DEL" border="0" align="absmiddle" width="16" height="16"></a></td>
	 	      <td>'.substr($row[1],0,5).'</td><td>'.substr($row[2],0,5).'</td><td align="left">'.($row[3]!='' ? $row[3] : '&nbsp;').'</td><td align="left" nowrap>';
	 	    // check overlappings
	 	    $query = 'SELECT DISTINCT CAL_ID,TITLE,BEG_HOUR,END_HOUR FROM PERIOD LEFT JOIN CALENDAR ON CAL_ID=CALENDAR.ID 
	 	      WHERE PERIOD.ID<>'.$row[6].' AND USER_ID='.$user->login['ID'].' AND date_overlap(UNIX_TIMESTAMP(ADDTIME(DATUM,BEG_HOUR)),
	 	      UNIX_TIMESTAMP(ADDTIME(DATUM,END_HOUR)),'.$row[4].','.$row[5].')>1 ORDER BY TITLE';
	 	    $res = mysql_query($query,$conn) or trigger_error($query.'<br>'.mysql_error($conn),E_USER_ERROR);
	 	    while($red = mysql_fetch_array($res,MYSQL_NUM)) 
	 	      if($red[0]==$_SESSION['cur_cal']) $slot.= substr($red[2],0,5).' - '.substr($red[3],0,5).'<br>'; 
	 	        else $slot.= $red[1].' ('.substr($red[2],0,5).' - '.substr($red[3],0,5).')<br>';
	 	    $slot .= '</td></tr>'.chr(13).chr(10);
	 	  }
	 	}
		$b = str_replace('<tr><td>{PLIST}</td></tr>',$slot!='' ? $slot : '<tr align="center"><td colspan="5"><b style="color:red">No time slots defined</b></td></tr>',$b);

		// show calendar
		$b = str_replace('<tr><td>{WEEK_1}</td></tr>',weeks($_SESSION['godina'],1),$b);
		$b = str_replace('<tr><td>{WEEK_2}</td></tr>',weeks($_SESSION['godina'],5),$b);
		$b = str_replace('<tr><td>{WEEK_3}</td></tr>',weeks($_SESSION['godina'],9),$b);
		$b = str_replace('{GODINA}',$_SESSION['godina'],$b);
		if($_SESSION['cal_date']!=0) $z = doy2date($_SESSION['cal_date'], 'd-M-Y');
		elseif($_SESSION['cur_cal']!=0) $z = 'Please choose a date from the calendar';
		else $z = 'There are no <a href="'.WEBDIR.'/admin/index.php?pageid=1" class="small new_cal">calendars</a> defined yet';
		$b = str_replace('{CAP}',$z,$b);
		
		$b = str_replace('{SLOT_SIZE}',$_POST['slot_size'],$b);
		$b = str_replace('{NUM_SLOT}',$_POST['num_slot'],$b);
		$b = str_replace('{BEG_TIME}',$_POST['beg_time'],$b);
		$b = str_replace('{END_TIME}',$_POST['end_time'],$b);

		echo $b;
	}
	else die('Could not find template - period.htm');
?>