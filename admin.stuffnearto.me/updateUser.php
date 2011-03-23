<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['username']){			
			
			global $database;
			global $stuff;
			
			//$q = 'SELECT username,email,userlevel,adminPlaces FROM '.TBL_USERS.' WHERE username = "'.$_GET['username'].'"';
			//$result = $database->query($q);
			
			//$stuff->debug($_GET,true);
			
			$places = serialize($_GET['place']);
			
			$info = $_GET;
			unset($info['task']);
			
			$sql = 'UPDATE `'.TBL_USERS.'` SET username = "'.(mysql_real_escape_string($_GET['username'])).'",email = "'.(mysql_real_escape_string($_GET['email'])).'", userlevel = "'.(mysql_real_escape_string($_GET['userlevel'])).'", adminPlaces = "'.(mysql_real_escape_string($places)).'" WHERE username = "'.$_GET['username'].'"';
			$result = $stuff->alter($sql);
			if($result){
				$html .= 'Thanks for the update on user '.$_GET['username'].', would you like to update <a href="/edit/user/'.$_GET['username'].'">'.$_GET['username'].'</a> again or go back and view <a href="/users">all</a> users again?';
			}else{
			
			}
		}else{
			$html .= 'No username passed in, go back to view <a href="/view/users">all</a> users<br />';
		}
	}

?>