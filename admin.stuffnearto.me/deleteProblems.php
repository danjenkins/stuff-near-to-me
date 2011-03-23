<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_REQUEST['problemId']){
			$sql = 'DELETE FROM problems WHERE problemId ="'.$_REQUEST['problemId'].'"';
			
			$result = $stuff->alter($sql);
			if($result){
				$html .= 'Problem has been deleted, go back and view <a href="/view/problems">all un-actioned</a> problems?<br />';
			}else{
				$html .= 'Sorry, theres been an error, a super admin has been notified<br />';
				//mail a super admin
			}
		}else{
			$html .= 'No problemId';
		}
	}


?>