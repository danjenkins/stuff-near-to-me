<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_REQUEST['imageId']){
			
			$sql1 = 'SELECT imageLocation FROM images I WHERE I.imageId = "'.$_REQUEST['imageId'].'"';
			
			$result1 = $stuff->query($sql1, true, $dbObj);
		
			$sql = 'DELETE FROM images WHERE imageId = "'.$_REQUEST['imageId'].'"';
			
			$result = $stuff->alter($sql);
			
			if($result){
				unlink('/home/jenkins2/public_html/stuffnearto.me'.$result1['imageLocation']);
				$html .= 'success, deleted!';
			}else{
				$html .= 'error!';
			}
		}else{
			$html .= 'No imageId';
		}
	}


?>