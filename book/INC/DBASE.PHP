<?php
include("conf.php");

define('MAIL_SALES','sales@yoursite.com'); // This is used as FROM address and REPLY-TO when sending e-mails to clients
define('DELIM','-');
define('PLOG','/system.log');
define('COL_EDIT','FFFFFF');
define('COL_DEL','FFD1E5');
define('COL_NEW','A5E5BA'); //new event background
define('COL_OLD','F7BEAD'); //inactive request background 

define('PREG_FIND_RECURSIVE', 1);
define('PREG_FIND_DIRMATCH', 2);
define('PREG_FIND_FULLPATH', 4);
define('PREG_FIND_NEGATE', 8);
define('PREG_FIND_DIRONLY', 16);
define('PREG_FIND_RETURNASSOC', 32);
// PREG_FIND_RECURSIVE   - go into subdirectorys looking for more files
// PREG_FIND_DIRMATCH    - return directorys that match the pattern also
// PREG_FIND_DIRONLY     - return only directorys that match the pattern (no files)
// PREG_FIND_FULLPATH    - search for the pattern in the full path (dir+file)
// PREG_FIND_NEGATE      - return files that don't match the pattern
// PREG_FIND_RETURNASSOC - Instead of just returning a plain array of matches,
//                         return an associative array with file stats
// to use more than one simply seperate them with a | character


$conn = mysql_connect('localhost',DATABASE_USER,DATABASE_PASSWORD) or die ('Cannot connect to MySQL!');
$db = mysql_select_db(DATABASE_NAME,$conn);
mysql_query('SET NAMES cp1251');

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
//header ('Expires: '.gmdate('D, d M Y H:i:s', time() + 3600).' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header ('Cache-Control: no-cache, must-revalidate');  // HTTP/1.1
header ('Pragma: no-cache');                          // HTTP/1.0

function myErrorHandler ($errno, $errstr, $errfile, $errline, $vars)
{
  // Only handle the errors specified by the error_reporting directive or function
  // Ensure that we should be displaying and/or logging errors
  //if ( ! ($errno & error_reporting ()) || ! (ini_get ('display_errors') || ini_get ('log_errors'))) return;
  if(($errno & (E_NOTICE | E_STRICT)) OR error_reporting()==0) return;

  // define an assoc array of error string
  // in reality the only entries we should
  // consider are 2,8,256,512 and 1024
  $errortype = array (
    1   =>  'Error',
    2   =>  'Warning',
    4   =>  'Parsing Error',
    8   =>  'Notice',
    16  =>  'Core Error',
    32  =>  'Core Warning',
    64  =>  'Compile Error',
    128 =>  'Compile Warning',
    256 =>  'User Error',
    512 =>  'User Warning',
    1024=>  'User Notice',
    2048=>  'Strict Mode',
    4096=>  'Recoverable Error'
    );
  $s = "</table></table></table><br>\n<b>".$errortype[$errno]."</b><br>\n$errstr<br><br>\n\n# $errline, $errfile";
  $s2 = "\n".$errortype[$errno]."\n$errstr\n\n# $errline, $errfile";
	$MAXSTRLEN = 64;
	$s .= '<pre>'; 
	$a = debug_backtrace();
	//array_shift($a);
	$traceArr = array_reverse($a);
	$tabs = 1;
	if(count($traceArr)) foreach($traceArr as $arr)
	{
		if($arr['function']=='myErrorHandler') continue;
		$Line = (isset($arr['line'])? $arr['line'] : "unknown");
		$File = (isset($arr['file'])? str_replace($_GLOBALS['tmpdir'],'',$arr['file']) : "unknown");
		$s.= "\n<br>";
		$s2.= "\n";
		for ($i=0; $i < $tabs; $i++) 
		{
		  $s .= '#';
		  $s2.= '#';
		}
		$s.= ' <b>'.$Line.'</b>, <font color=blue>'.$File."</font>\n<br>";
		$s2.= ' '.$Line.', '.$File."\n";
		for ($i=0; $i < $tabs; $i++) 
		{
		  $s .= ' ';
		  $s2.= ' ';
		}
		$tabs ++;
		$s .= ' ';
		$s2.= ' ';
		if (isset($arr['class'])) 
		{
		  $s .= $arr['class'].'.';
		  $s2.= $arr['class'].'.';
		}
		$args = array();
		if(!empty($arr['args'])) foreach($arr['args'] as $v)
		{
			if (is_null($v)) $args[] = 'NULL';
			elseif (is_array($v)) $args[] = 'Array['.sizeof($v).']'.(sizeof($v)<=5 ? serialize($v) : '');
			elseif (is_object($v)) $args[] = 'Object:'.get_class($v);
			elseif (is_bool($v)) $args[] = $v ? 'true' : 'false';
			else
			{ 
				$v = (string) @$v;
				//$str = htmlspecialchars(substr($v,0,$MAXSTRLEN));
				$str = htmlspecialchars($v);
				//if (strlen($v) > $MAXSTRLEN) $str .= '...';
				$args[] = "\"".$str."\"";
			}
		}
		if(isset($arr['function'])) 
		{
		  $s .= $arr['function'].'('.implode(', ',$args).')';
		  $s2.= $arr['function'].'('.implode(', ',$args).')';
		}
		else 
		{
		  $s .= '[PHP Kernel] ('.implode(', ',$args).')';
		  $s2.= '[PHP Kernel] ('.implode(', ',$args).')';
		}
	}
	$m = mysql_errno();
	if($m) 
	{
	  $s.= chr(13).'<br> MySQL error: '.$m;
	  $s2.= chr(13).' MySQL error: '.$m;
	}
	$s2.= chr(13).' Referrer: '.$_SERVER['HTTP_REFERRER'];
	$s.= '</pre>';
	if($m==2006) return true; // server gone away
	echo $s;
  die;
}

// set to the user defined error handler
$old_error_handler = set_error_handler('myErrorHandler');

function loger($x)
{
global $tmpdir;

	error_log(date('[d-m-Y] (H:i:s) {'.$_SERVER['REMOTE_ADDR'].($_SERVER["HTTP_X_FORWARDED_FOR"]!='' ? ','.$_SERVER["HTTP_X_FORWARDED_FOR"] : '').'} -> ').$x.chr(13).chr(10),3,$tmpdir.PLOG);
}

function ivo_str($z)
{
	return preg_replace('/[ \t]+/',' ',trim(strtr(strip_tags($z),"'".chr(160).chr(92),"`".chr(32).chr(32))));
}

function ivo_str2($z)
{
	return preg_replace('/[ \t]+/',' ',trim(strtr(strip_tags($z,'<b><p><i><font><table><tr><td><th><ul><ol><li><a><hr><h1><h2><h3><h4><h5><h6><pre><small><strike><strong><u><caption><br><center><span><img><sup><sub>'),/*chr(160).*/chr(92).chr(10).chr(13),'   ')));
} 

function fnum($n)
{
	return (double)str_replace(',','.',$n);
}

// Remove URL parameters
function recode($a)
{
	$z = strpos($a,'?');
	if($z>1) return substr($a,0,$z);
		else return $a;
}

// Replace & with &amp; if not followed by #
function re_amp($a)
{
	return preg_replace('/\&[^#]/','&amp;',$a);
}

// Show "Not enough permissions" message
function noperm()
{
global $tmpdir;

	$b = @file_get_contents($tmpdir.'/temp/no_perm.htm');
	$b = str_replace('{PREP}',WEBDIR,$b);
	echo $b;
}

// Enumerates array by key/value in order to prepare a string, suitable for SQL INSERT statement
function IVO_insert($a,$c)
{
  $t = Array();
  if(!is_array($c)) $c = Array($c);
  $a = array_diff_key($a,array_flip($c));
	foreach($a as $k=>$v)
  {
    if(is_null($v)) $t[] = 'NULL';
    else
    {
			if($v==='NULL') $t[] = 'NULL';
			elseif(is_string($v)) $t[] = '"'.mysql_real_escape_string($v).'"';
			else $t[] = $v;
		}
  }
	return '('.implode(',',array_keys($a)).') VALUES ('.implode(',',$t).')';
}

// Enumerates array by key/value in order to prepare a string, suitable for SQL UPDATE statement
function IVO_update($a,$c)
{
  $t = Array();
  if(!is_array($c)) $c = Array($c);
  $a = array_diff_key($a,array_flip($c));
	foreach($a as $k=>$v)
  {
    if(is_null($v)) $t[] = $k.'=NULL';
    else
    {
			if($v==='NULL') $t[] = $k.'=NULL';
			elseif(is_string($v)) $t[] = $k."='".mysql_real_escape_string($v)."'";
			else $t[] = $k.'='.$v;
		}
  }
	return implode(',',$t);
}

// returns list of records from $TABLE, sorted by column $SORT in ascending/descending $ORDER,
// using $END as optional WHERE clause
// $TABLE can be comma separated list - first element is used as primary key
// $FIELD can be comma separated list, too
function loadItems($table,$field,$current,$empty="",$end="",$sort="",$order="ASC")
{
	$a = substr($table,0,strpos($table,','));
	if($a != '') $a .= '.';
	$query = 'SELECT '.$a."ID ID0,$field FROM $table $end";
 	if($sort!='') $query.= " ORDER BY $sort $order";
	$result = mysql_unbuffered_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
	if ($empty!='') $z = '<option value="0">'.$empty.'</option>';
  while ($row = mysql_fetch_array($result,MYSQL_NUM))
	{	
  	if(is_array($current))
  	{
  		if (in_array($row[0],$current)) $a = 'selected'; else $a = '';
  	}
  	else
  	{
  		if ($row[0] != $current) $a=''; else $a='selected';
  	}
 		$z .= '<option '.$a.' value="'.$row[0].'">'.$row[1].'</option>';
 	}
 	mysql_free_result($result);
 	return $z;
}

// converts d-m-Y into the only possible for MySQL Y-m-d
function GDate($dft)
{
	if($dft>0)
	{
		list ($d, $m, $y) = preg_split ('#[:/\.-]#', str_replace(" ","",$dft)); 
  	if($y<10) $y='200'.(int)$y;
  	elseif($y<100) $y='20'.(int)$y;
  	elseif($y<1000) $y='2'.(int)$y;
		return $y.'-'.($m<10?"0".(int)$m:$m).'-'.($d<10?"0".(int)$d:$d);
	}
	else return '0000-00-00';
}

// converts from Y-m-d to the usual d-m-Y
function ADate($dft,$lim,$text=true)
{
	$mon = Array('N/A','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	if($dft>0) return substr($dft,8,2).$lim.($text ? $mon[(int)substr($dft,5,2)] : substr($dft,5,2)).$lim.substr($dft,0,4); 
		else return '&#nbsp;';
}

// returns a cell from table by column and row
function a_select($tbl,$clause,$field='',$where='')
{
	if ($clause==0) return '&#nbsp;';
	if($field=='') $field=$tbl;
	$query = "SELECT $field FROM $tbl WHERE ";
	if($where=='') $query.='ID='.$clause;
		else $query.=$where;
	$res = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
	if(mysql_num_rows($res)>0) $x = mysql_result($res,0,0);
		else $x = '&nbsp;';
	mysql_free_result($res);
	return $x;
}

// check date validity in d-m-Y
function ChekDate($a)
{
	list ($d, $m, $y) = preg_split ('#[:/\.-]#', str_replace(" ","",$a));
	list($d2,$m2,$y2) = preg_split('/-/',strftime('%d-%m-%Y', mktime(0,0,0,(int)$m,(int)$d,(int)$y)));
	if($d<1 OR $d>31) return false;
	if($m<1 OR $m>12) return false;
	if($y<10) return false;
	if($y<100) $y='20'.$y;
	if($y>2020) return false;
	if((int)$d!=(int)$d2 OR (int)$m!=(int)$m2 OR (int)$y!=(int)$y2) return false;
	return true;
}

//Compare dates - return -1,0,+1
function CompDate($a,$b)
{
	list ($d, $m, $y) = preg_split ('#[:/\.-]#', str_replace(" ","",$a));
	list ($d2, $m2, $y2) = preg_split ('#[:/\.-]#', str_replace(" ","",$b));
	$x = mktime(0,0,0,(int)$m,(int)$d,(int)$y);
	$z = mktime(0,0,0,(int)$m2,(int)$d2,(int)$y2);
	if($x < $z) return -1;
	elseif($x > $z) return 1;
	else return 0;
}

function preg_find($pattern, $start_dir='.', $args=NULL) 
{
  $files_matched = array();
  $fh = opendir($start_dir);
  while (($file = readdir($fh)) !== false) 
  {
    if (strcmp($file, '.')==0 || strcmp($file, '..')==0) continue;
    $filepath = $start_dir . '/' . $file;
    if (preg_match($pattern, ($args & PREG_FIND_FULLPATH) ? $filepath : $file)) 
    {
      $doadd =     is_file($filepath)
               || (is_dir($filepath) && ($args & PREG_FIND_DIRMATCH))
               || (is_dir($filepath) && ($args & PREG_FIND_DIRONLY));
      if ($args & PREG_FIND_DIRONLY && $doadd && !is_dir($filepath)) $doadd = false;
      if ($args & PREG_FIND_NEGATE) $doadd = !$doadd;
      if ($doadd) 
      {
        if ($args & PREG_FIND_RETURNASSOC) // return more than just the filenames
        {
          $fileres = array();
          if (function_exists('stat')) 
          {
            $fileres['stat'] = stat($filepath);
            $fileres['du'] = $fileres['stat']['blocks'] * 512;
          }
          //if (function_exists('fileowner')) $fileres['uid'] = fileowner($filepath);
          //if (function_exists('filegroup')) $fileres['gid'] = filegroup($filepath);
          //if (function_exists('filetype')) $fileres['filetype'] = filetype($filepath);
          //if (function_exists('mime_content_type')) $fileres['mimetype'] = mime_content_type($filepath);
          if (function_exists('dirname')) $fileres['dirname'] = dirname($filepath);
          if (function_exists('basename')) $fileres['basename'] = basename($filepath);
          //if (isset($fileres['uid']) && function_exists('posix_getpwuid ')) $fileres['owner'] = posix_getpwuid ($fileres['uid']);
          $files_matched[$filepath] = $fileres;
        } 
        else array_push($files_matched, $filepath);
      }
    }
    if ( is_dir($filepath) && ($args & PREG_FIND_RECURSIVE) ) $files_matched = array_merge($files_matched, preg_find($pattern, $filepath, $args));
  }
  closedir($fh); 
  return $files_matched;
}

function url_escape($url)
{
	$parts = parse_url($url);
  $path_parts = array_map('rawurldecode', explode('/', $parts['path']));
	if($parts['scheme']=='') $parts['scheme'] = 'http';
  return
    $parts['scheme'] . '://' .
    $parts['host'] .
    implode('/', array_map('rawurlencode', $path_parts));
} 

function cid_email(&$b, &$mailer)
{
	$cid = 1;
  preg_match_all("/ (src|background)=[\"'](.*)[\"']/Ui", $b, $images);
  if (isset($images[2])) 
  	foreach ($images[2] as $i => $url) 
  	{
			$nam = url_escape(trim($url));
			$content = @file_get_contents($nam);
			if($content != '')
			{   
				$fn = base_name($nam);
				$fn = substr($fn,0,strpos($fn,'?'));
				$mailer->AddStringAttachment($content,$fn);
				$cnt = count($mailer->attachment)-1;
				$mailer->attachment[$cnt][6] = 'inline';
				$mailer->attachment[$cnt][7] = 'cid_'.$cid;
			}
			else loger('Email error - could not get content for: '.$url);
			$b = preg_replace('/'.$images[1][$i]."=[\"']" . preg_quote($url, '/') . "[\"']/Ui",
                        $images[1][$i] . '="cid:cid_'.$cid.'"', $b); 
			$cid++;
		}
}

// fight against session timeout - ask user to login using HTTP Basic authentication
function timeout()
{
	if(!is_a($_SESSION['user'],'Dealer') OR !$_SESSION['user']->login['ID'])
	{
		$u = ivo_str($_SERVER['PHP_AUTH_USER']);
		$p = ivo_str($_SERVER['PHP_AUTH_PW']);
		// if no Authorization provided - ask for one
		if($u=='' OR $p=='')
		{
			//header('Location:'.WEBDIR.'/CRM/signout.php');
			header('WWW-Authenticate: Basic realm="Your session timed out - login again"');
	    header('HTTP/1.0 401 Unauthorized');
	    echo 'Bad login - wrong username or password';
			die;
		}
		else
		{
			$_SESSION['user'] = new Dealer();
			$GLOBALS['user'] = &$_SESSION['user'];
			if($_SESSION['user']->LogDealer($u,$p))
			{
				//loger('OK login user ['.$_SESSION['user']->login['USER'].']');
			}
			else
			{
				//loger('Bad login - User = '.$u.', Pass = '.$p);
				// ask authorization again
				header('WWW-Authenticate: Basic realm="Your session timed out - login again"');
		    header('HTTP/1.0 401 Unauthorized');
		    echo 'Bad login - wrong username or password';
				die;
			}
		}
	}
	else $GLOBALS['user'] = &$_SESSION['user'];
}

function emailCheck($emailStr)
{
	if($emailStr=='') return '';
		else $emailStr = trim($emailStr);

  // The following is the list of known TLDs that an e-mail address must end with.
	$knownDomsPat= '/^(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum)$/';

  /* The following pattern is used to check if the entered e-mail address
  fits the user@domain format.  It also is used to separate the username
  from the domain. */

	$emailPat= '/^(.+)@(.+)$/';

  /* The following string represents the pattern for matching all special
  characters.  We don't want to allow special characters in the address.
  These characters include ( ) < > @ , ; : \ " . [ ] */

	$specialChars= '\\(\\)><@,;\/:\\\\\\\"\\.\\[\\]';

  /* The following string represents the range of characters allowed in a
  username or domainname.  It really states which chars aren't allowed.*/

	$validChars= '[^\\s' . $specialChars . ']';

  /* The following pattern applies if the "user" is a quoted string (in
  which case, there are no rules about which characters are allowed
  and which aren't; anything goes).  E.g. "jiminy cricket"@disney.com
  is a legal e-mail address. */

	$quotedUser= '(\"[^\"]*\")';

  /* The following pattern applies for domains that are IP addresses,
  rather than symbolic names.  E.g. joe@[123.124.233.4] is a legal
  e-mail address. NOTE: The square brackets are required. */

	$ipDomainPat= '/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/';

  // The following string represents an atom (basically a series of non-special characters.)

	$atom= $validChars . '+';

  /* The following string represents one word in the typical username.
  For example, in john.doe@somewhere.com, john and doe are words.
  Basically, a word is either an atom or quoted string. */

	$word= '(' . $atom . '|' . $quotedUser . ')';

  // The following pattern describes the structure of the user

	$userPat= '/^' . $word . '(\\.' . $word . ')*$/';

  /* The following pattern describes the structure of a normal symbolic
  domain, as opposed to ipDomainPat, shown above. */

	$domainPat= '/^' . $atom . '(\\.' . $atom . ')*$/';

  // Finally, let's start trying to figure out if the supplied address is valid.

  /* Begin with the coarse pattern to simply break up user@domain into
  different pieces that are easy to analyze. */

	if (!preg_match($emailPat,$emailStr,$matchArray))
	{

    /* Too many/few @'s or something; basically, this address doesn't
    even fit the general mould of a valid e-mail address. */

		return 'Email address "'.$emailStr.'" seems incorrect (check @ and .)';
	}
	$user=$matchArray[1];
	$domain=$matchArray[2];

  // Start by checking that only basic ASCII characters are in the strings (0-127).

	for ($i=0; $i<strlen($user); $i++) 
		if (ord(substr($user,$i,1))>127) return 'The username in email contains invalid character ('.substr($user,$i,1).')';
	for ($i=0; $i<strlen($domain); $i++) 
		if (ord(substr($domain,$i,1))>127) return 'This domain name contains invalid characters ('.substr($domain,$i,1).')';

  // user is not valid
	if (!preg_match($userPat,$user)) return 'The username in email does not seem to be valid ('.$user.').';

  // if the e-mail address is at an IP address (closed in [] ) make sure the IP address is valid.

	if (preg_match($ipDomainPat,$domain,$IPArray))
	{
		for ($i=1;$i<=4;$i++) 
			if ($IPArray[$i]>255 OR $IPArray[$i]=='') return 'Destination IP address ('.$domain.') is invalid!';
		return '';
	}

  // Domain is symbolic name.  Check if it's valid.

	$atomPat= '/^' . $atom . '$/';
	$domArr=preg_split('/\./',$domain);
	$len=count($domArr);
	for ($i=0;$i<$len;$i++) 
		if (!preg_match($atomPat,$domArr[$i])) return 'The domain name does not seem to be valid ('.$domArr[$i].').';

  /* domain name seems valid, but now make sure that it ends in a
  known top-level domain (like com, edu, gov) or a two-letter word,
  representing country (uk, nl), and that there's a hostname preceding
  the domain or country. */

	if (strlen($domArr[count($domArr)-1])!=2 && !preg_match($knownDomsPat,$domArr[count($domArr)-1]))
		return 'The email address ('.$domArr[count($domArr)-1].') must end in a well-known domain (COM, NET, ORG, ...) or two letter country (BG, RU, EU, ...)';

  // Make sure there's a host name preceding the domain.
	if ($len<2) return 'This email address ('.$emailStr.') is missing a hostname!';

	return '';
}

function doy2date( $tDay, $tFormat = 'd-m-Y' ) 
{
  $day = intval( $tDay );
  if($day > 0) $day--;
  $offset = $day * 86400;
  $str = date( $tFormat, mktime(0,0,0,1,1,date( 'Y' )) + $offset );
  return( $str );
} 
 
?>
