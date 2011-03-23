<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_REQUEST['reviewId']){
			$sql = 'DELETE FROM reviews WHERE reviewId ="'.$_REQUEST['reviewId'].'"';
			
			$result = $stuff->alter($sql);
			if($result){
				$html .= 'Review has been deleted, go back and view <a href="/view/reviews">all un-approved</a> reviews?<br />';
			}else{
				$html .= 'Sorry, theres been an error, a super admin has been notified<br />';
				//mail a super admin
			}
		}else{
			$html .= 'No review Id';
		}
	}


?>