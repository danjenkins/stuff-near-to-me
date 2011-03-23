<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['fieldId']){
			//need to delete the columns in cmColumns
				
			$sql = 'SELECT fieldName FROM cmColumns WHERE fieldId = '.mysql_real_escape_string($_GET['fieldId'], $dbObj);
			$result = $stuff->query($sql,true, $dbObj);
			
			$stuff->debug($result);
			//need to remove the columns from placeInformation too
			if($result){
				$sql2 = 'ALTER TABLE placeInformation DROP COLUMN '.$result['fieldName'];
				$result2 = $stuff->alter($sql2, $dbObj);
				if($result2){
					$html .=  'Removed column '.$result['fieldName'].' from table placeInformation<br />';
				}else{
					$html .=  'Error removing columns from placeInformation<br />';
				}
				
				$sql3 = 'DELETE FROM cmColumns WHERE fieldId = '.mysql_real_escape_string($_GET['fieldId'], $dbObj);
				$result3 = $stuff->delete($sql3, $dbObj);
				if($result3){
					$html .=  'Removed rows from cmColumns<br />';
				}else{
					$html .=  'Error removing rows from cmColumns<br />';
				}
				$html .= 'Would you like to go back to <a href="/view/categoryFields">all</a> the category fields?<br />';
			}else{
				$html .= 'Error, a super admin has been notified<br />';
				//mail error
			}
		}
	}

?>