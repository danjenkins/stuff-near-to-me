<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
		
		$info = $_GET;//move what we want to change over to info, so now remove what we def dont want to bew updated.
		if(!$_GET['placeId']){
			$info['dateAdded'] = 'NOW()';
			$info['addedBy'] = $session->username;
		}
		
		$info['dateAmended'] = 'NOW()';
		$info['amendedBy'] = 'admin';
		unset($info['task']);
		//need to add in amended by etc etc
		//$stuff->debug($info);
		$result = $stuff->replace('placeInformation', $info, $dbObj);
		
		if($result){
			$sql .= 'SELECT P.placeName, C.categoryName, L.locationName, C.categoryId, L.locationId FROM placeInformation P LEFT JOIN locations L ON P.locationId = L.locationId LEFT JOIN categories C ON P.categoryId = C.categoryId WHERE P.placeId = "'.$_GET['placeId'].'"';
			$result = $stuff->query($sql, true);
			$html .= 'Success, place information for '.$result['placeName'].' has been updated<br />';
			$html .= 'Would you like to update <a href="/edit/place/'.$_GET['placeId'].'">'.$result['placeName'].'</a> again? Or go back to view all the places in <a href="/view/place/'.$result['locationId'].'">in '.$result['locationName'].'</a> or look at <a href="/view/location">all</a> locations again';
			
			if($_GET['approved'] == '1' && $_GET['archived'] != '1'){				
				require_once('../classes/stuffTwitter.php');
				$twitter = new stuffTwitter();
				
				$status = ($_GET['placeId'] == 'new' ? 'Just added ' : 'Just updated ').ucwords($stuff->splitCamelCase($result['placeName'])) .' in '.ucwords($result['locationName']).'.';
				$url = 'http://www.stuffnearto.me/'.$result['locationName'].'/'.$result['categoryName'].'/'.$_GET['placeId'].'/'.urlencode($result['placeName']);
				$result = $twitter->update($status, $url);
			
				//var_dump($result);//$result has loads of information about our twitter account
			}
			
		}else{
			$html .= 'There has been an error, a super administrator has been notified';
			//mail event to all super admins
		}
	}


?>