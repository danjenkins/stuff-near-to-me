<?php
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['locationId']){
		
			//delete the row out of locations
			
			$sql = 'DELETE FROM locations WHERE locationId = '.mysql_real_escape_string($_GET['locationId'], $dbObj);
			
			$result = $stuff->delete($sql, $dbObj);
			
			if($result){
				$html .= 'Delete from locations successful, deleting from placeInformation....<br />';
				$sql2 = 'DELETE FROM placeInformation WHERE locationId = '.mysql_real_escape_string($_GET['locationId'], $dbObj);
				$result2 = $stuff->delete($sql2, $dbObj);
				if($result2 != 0){
					$html .= 'Delete from placeInformation successful, number of places affected: '.$result2.'<br />';
				}else{
					$html .= 'No information needed to be deleted from placeInformation<br />';
				}
			}else{
				$html .= 'Error deleting from locations, a super admin has been notified<br />';
				//mail event!
			}
			$html .= 'Go back to a list of <a href="/view/locations">all</a> locations<br />';
		}
	}
	



?>