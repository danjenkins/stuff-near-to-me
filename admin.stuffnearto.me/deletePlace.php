<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['placeId']){
		
			$sql = 'DELETE FROM placeInformation WHERE placeId = '.mysql_real_escape_string($_GET['placeId'], $dbObj);
			$result = $stuff->delete($sql, $dbObj);
			if($result){
				$html .= 'Removed rows from placeInformation<br />';
			}else{
				$html .= 'Error removing rows from placeInformation<br />';
			}
	
		
		}
	}

?>