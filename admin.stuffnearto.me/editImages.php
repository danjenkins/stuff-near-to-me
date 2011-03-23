<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_REQUEST['imageId']){
			
			$sql = 'SELECT I.*, P.placeName, C.categoryName, L.locationName FROM images I LEFT JOIN placeInformation P ON I.placeId = P.placeId LEFT JOIN categories C ON I.categoryId = C.categoryId  OR P.categoryId = C.categoryId LEFT JOIN locations L ON I.locationId = L.locationId OR P.locationId = L.locationId WHERE I.imageId = '.$_REQUEST['imageId'];
			
			$result = $stuff->query($sql, true, $dbObj);
			
			$html .= '<h3>Image for ';
			if(!$result['placeName'] && !$result['categoryName'] && $result['locationName']){
				//got a location review
				$html .= $result['locationName'];
			}elseif(!$result['placeName'] && $result['categoryName'] && $result['locationName']){
				//got a category review
				$html .= $result['categoryName'] .' category, '. $result['locationName'];
			}elseif($result['placeName'] && $result['categoryName'] && $result['locationName']){
				//got a place review
				$html .= $result['placeName'] .', '. $result['categoryName'] .', '. $result['locationName'];
			}
			$html .= '</h3>';
			
			$html .= '<form method="get" action="/update/images/'.$_REQUEST['imageId'].'">';
			
			$html .= '<img src="http://stuffnearto.me'.$result['imageLocation'].'" title="'.$result['imageTitle'].'" alt="'.$result['imageAlternateText'].'"/><br />';
			
			$html .= '<fieldset><div>Approved</div><label><input type="radio" name="approved" value="0" '.($result['approved'] == '0' ? 'checked="checked"' : '').'/>No</label><label><input type="radio" name="approved" value="1" '.($result['approved'] == '1' ? 'checked="checked"' : '').'/>Yes</label></fieldset>';
			
			$html .= '<fieldset><div>Archived</div><label><input type="radio" name="archived" value="0" '.($result['archived'] == '0' ? 'checked="checked"' : '').'/>No</label><label><input type="radio" name="archived" value="1" '.($result['archived'] == '1' ? 'checked="checked"' : '').'/>Yes</label></fieldset>';
			
			$html .= '<label for="input_alt">Alternate Text</label><input type="text" id="input_alt" name="imageAlternateText" value="'.$result['imageAlternateText'].'"/><br />';
			
			$html .= '<label for="input_title">Image Title</label><input type="text" id="input_title" name="imageTitle" value="'.$result['imageTitle'].'"/><br />';
			
			$html .= '<label>&nbsp;</label><button>Submit</button>';
			$html .= '</form>';
			
		}
	}


?>