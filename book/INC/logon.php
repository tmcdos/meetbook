<?php

class Dealer
{
	var $login; // User information from table CLIENT

	function Dealer()
	{
	}
	
	function LogDealer($u,$p)
	{	
		if($u=='' OR $p=='') return false;
		//$htp = file_get_contents('http://your_site.com/login.php?u='.urlencode($u).'&p='.urlencode($p));
		//$this->login = json_decode($htp,TRUE);
		//if($this->login['ID']!=0)	return true;
  		$query = "SELECT * FROM USER WHERE LOGIN='".$u."' AND PASS='".$p."' LIMIT 1";
  		$result = mysql_query($query) or trigger_error($query.'<br>'.mysql_error(),E_USER_ERROR);
  		if(mysql_num_rows($result))
  		{
  			$this->login = mysql_fetch_array($result,MYSQL_ASSOC);
  			return true;
  		}
		else
		{
			unset($this->login);
			return false;
		}
	}

}
?>