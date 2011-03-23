<?php

	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{

		$info = $_GET;
		unset($info['task'], $info['lookupName']);
		$info['lastAmendedDate'] = 'NOW()';
		$info['lastAmendedBy'] = $session->username;
		
		if($info['locationName'] != ''){
			$info['locationName'] = strtolower($info['locationName']);
			if($info['locationId'] == 'new'){
				$info['dateAdded'] = 'NOW()';
				$info['addedBy'] = 'admin';
				$info['locationId'] = 'NULL';
				$result = $stuff->replace('locations', $info, $dbObj);
			}else{
				$result = $stuff->update('locations', 'locationId', $info['locationId'], $info, $dbObj);
			}
		}else{
			$html .=  'No Location Name given, please fill out a Location Name';
		}
		
		if($result){
			
			if($info['locationId'] == 'NULL'){
				$html .= 'Location '.$info['locationName'].' added to the database<br />';
			}else{
				$html .= 'Changed details on location '.$info['locationName'].'<br />';
			}
			$html .= 'Would you like to edit <a href="/edit/location/'.($info['locationId'] == 'NULL' ? mysql_insert_id() : $info['locationId']).'">'.$info['locationName'].'</a> again? Or go back to the list of <a href="/view/locations">locations</a><br />';
							
				require_once('../classes/stuffTwitter.php');
				$twitter = new stuffTwitter();
				
				$status = ($_GET['locationId'] == 'new' ? 'Just added ' : 'Just updated ').ucwords($stuff->splitCamelCase($info['locationName'])).'.';
				$url = 'http://www.stuffnearto.me/'.$result['locationName'];
				$result = $twitter->update($status, $url);
			
				//var_dump($result);//$result has loads of information about our twitter account
		}else{
			$html .=  'Error updating location '.$info['locationName'].', a super admin has been notified<br />';
			//mail event for error on location
		}
	}

?>