<?php

	global $dbObj;
	global $session;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{

		if($_REQUEST['menuId']){
		    		
    		$info['archived'] = $_REQUEST['archived'];
    		$info['approved'] = $_REQUEST['approved'];
    		$info['menuUrl'] = $_REQUEST['menuUrl'];
    		
 
    		$info['amendedBy'] = $session->username;
    		$info['dateAmended'] = 'NOW()';
							
			$result = $stuff->update('externalMenus', 'menuId', $_REQUEST['menuId'], $info, $dbObj);
			
			if($result){
				$html .= 'Successfully updated menu';
			}else{
				$html .= 'Sorry there has been an error';
			}
			
			
		}else{
			$html .= 'Sorry, no menuId given';
		}
	}


?>