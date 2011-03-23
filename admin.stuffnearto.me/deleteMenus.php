<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_REQUEST['menuId']){
		
			$sql = 'DELETE FROM externalMenus WHERE menuId = "'.$_REQUEST['menuId'].'"';
			
			$result = $stuff->alter($sql);
			
			if($result){
				$html .= 'success, deleted!';
			}else{
				$html .= 'error!';
			}
		}else{
			$html .= 'No menuId';
		}
	}


?>