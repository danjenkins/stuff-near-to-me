<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{

		if($_REQUEST['reviewId']){
			
			//we've got the review Id, now update the row with the info given, plus some extra info such as updatedBy and the date
			
			$info['rating'] = $_REQUEST['rating'];
			$info['comment'] = $_REQUEST['comment'];
			$info['approved'] = $_REQUEST['approved'];
			$info['archived'] = $_REQUEST['archived'];
			$info['updatedBy'] = $session->username;
			$info['dateUpdated'] = 'NOW()';
			
			
			$result = $stuff->update('reviews', 'reviewId', $_REQUEST['reviewId'], $info, $dbObj);
			
			if($result){
				$html .= 'Success, would you like to edit that <a href="/edit/reviews/'.$_GET['reviewId'].'">review</a> again?<br />';
				$html .= 'Or go back to <a href="/view/reviews">all un-approved</a> reviews?<br />';
				
			}else{
				$html .= 'Error, a super admin has been told<br />';
				//mail a notification
			}
		}else{
			$html .= 'Sorry, no reviewId given';
		}
	}

?>