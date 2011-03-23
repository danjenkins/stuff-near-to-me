<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['categoryId']){
		
			//delete the row out of categories
			
			$sql = 'DELETE FROM categories WHERE categoryId = '.mysql_real_escape_string($_GET['categoryId'], $dbObj);
			
			$result = $stuff->delete($sql, $dbObj);
			
			if($result){
				$html .=  'Delete from categories successful, deleteing from placeInformation....<br />';
				$sql2 = 'DELETE FROM placeInformation WHERE categoryId = '.mysql_real_escape_string($_GET['categoryId'], $dbObj);
				$result2 = $stuff->delete($sql2, $dbObj);
				if($result2 != 0){
					$html .=  'Delete from placeInformation successful, number of rows affected: '.$result2.'<br />';
				}else{
					$html .=  'No information needed to be deleted from placeInformation<br />';
				}
				
				//need to delete the columns in cmColumns
				
				$sql3 = 'SELECT fieldName FROM cmColumns WHERE relatedCategory = '.mysql_real_escape_string($_GET['categoryId'], $dbObj);
				$result3 = $stuff->query($sql3,false, $dbObj);
				
				
				$i = 0;
				$str = '';
				foreach($result3 as $k=>$v){
					$i++;
					if($i != 1){
						$str .= ', ';
					}
					$str .= 'DROP COLUMN '.$v['fieldName'];
				}
				
				
				//need to remove the columns from placeInformation too
				$sql4 = 'ALTER TABLE placeInformation '.$str;
				$result4 = $stuff->alter($sql4, $dbObj);
				if($result4){
					$html .=  'Removed columns '.$str.' from table placeInformation<br />';
				}else{
					$html .=  'Error removing columns from placeInformation<br />';
				}
				
				$sql5 = 'DELETE FROM cmColumns WHERE relatedCategory = '.mysql_real_escape_string($_GET['categoryId'], $dbObj);
				$result5 = $stuff->delete($sql5, $dbObj);
				if($result5){
					$html .=  'Removed rows from cmColumns<br />';
				}else{
					$html .=  'Error removing rows from cmColumns<br />';
				}
				
			}else{
				$html .=  'Error deleting from categories<br />';
			}
			$html .= 'Back to view <a href="/view/categories">all</a> categories?<br />';
			
			
		
		}
	}
	 

?>