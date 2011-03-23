<?php

	global $dbObj;
	global $session;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{

		if($_REQUEST['imageId']){
		    		
    		$info['archived'] = $_REQUEST['archived'];
    		$info['approved'] = $_REQUEST['approved'];
    		$info['imageTitle'] = $_REQUEST['imageTitle'];
    		$info['imageAlternateText'] = $_REQUEST['imageAlternateText'];
    		
    		if($_REQUEST['approved']){
    			$info['approvedBy'] = $session->username;
    			$info['dateApproved'] = 'NOW()';
    		}
							
			$result = $stuff->update('images', 'imageId', $_REQUEST['imageId'], $info, $dbObj);
			
			if($result){
				$html .= 'Successfully updated image';
				
			}else{
				$html .= 'Sorry there has been an error';
			}
			
			
		}else{
			$html .= 'Sorry, no imageId given';
		}
	}


?>