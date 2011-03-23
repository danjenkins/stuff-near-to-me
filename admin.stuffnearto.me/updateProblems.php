<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{

		if($_REQUEST['problemId']){
			
			//we've got the review Id, now update the row with the info given, plus some extra info such as updatedBy and the date
			
			$info['note'] = $_REQUEST['note'];
			
			if($_REQUEST['actioned'] == 'on'){
				$info['actioned'] = '1';
			}else{
				$info['actioned'] = '0';
			}
			
			$info['lastActionedBy'] = $session->username;
			$info['dateLastActioned'] = 'NOW()';
			
			if($_REQUEST['sendEmail'] == 'on'){
				mail($_REQUEST['emailAddress'], 'Information regarding your problem', $_REQUEST['emailContent'],'From: Stuff Near To Me <donotreply@stuffnearto.me>');
			}
				
			$result = $stuff->update('problems', 'problemId', $_REQUEST['problemId'], $info, $dbObj);
			
			if($result){
				$html .= 'Success updating problem, want to go back to <a href="/view/problems">all un-actioned</a> problems?';
			}else{
				$html .= 'Error updating problem, a super admin has been notified. Want to go back to <a href="/view/problems">all un-actioned</a> problems?';
				//email super admin
			}
			
			
		}else{
			$html .= 'Sorry, no problemId given';
		}
	}

?>