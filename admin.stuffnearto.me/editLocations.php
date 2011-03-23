<?php
	
	global $dbObj;

	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['locationId'] == 'new'){
			//do a describe of the locations table to get all the column names
			$sql = 'DESCRIBE locations';
			$result = $stuff->query($sql, true, $dbObj);
			
			foreach($result as $k=>$v){
				$resultTmp[$v['Field']] = '';			
			}
			$result = $resultTmp;
			unset($resultTmp);
			
		}else{
			//not sew so get column names from whats in there.
			$sql = 'SELECT * FROM locations WHERE locationId = '. mysql_real_escape_string($_GET['locationId'], $dbObj);
			$result = $stuff->query($sql, true, $dbObj);
		}
		
		//output form using info from db and send through new if new
		
		$html .= '<form id="edit" action="/update/location/'.$_GET['locationId'].'">';
		$html .='<fieldset><legend>Location Information</legend>';
		foreach($result as $k=>$v){
			//output each label and inputs
			
			if(!in_array($k, array('locationId', 'dateAdded', 'addedBy', 'lastAmendedDate', 'lastAmendedBy'))){
				$html .= '<label for="i_'.$k.'">'.ucwords($stuff->splitCamelCase($k)).'</label><input type="text" autocomplete="off" id="i_'.$k.'" name="'.$k.'" value="'.$v.'" '.(($k == 'latitude' || $k == 'longitude') ? 'class="'.$k.'Admin"' : '').'/><br />';
			}
		}
		$html .= '<label for="locationLookup">Geo-Location</label><input type="text" id="locationLookup" name="lookupName" class="postCodeAdmin" /><br /><span class="description">Only used for lookup, not saved anywhere</span><br />';
		//output a map after those key fields!
		$html .= '<div id="gMap"></div>';
		$html .= '<script>$(function(){
			googleMaps.init();
		})</script>';
		$html .= '</fieldset>';
		$html .= '<label>&nbsp;</label><button>Submit</button>';
		$html .= '</form>';
	}

?>