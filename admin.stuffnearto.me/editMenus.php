<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_REQUEST['menuId']){
			
			$sql = 'SELECT E.*, P.placeName, C.categoryName, L.locationName FROM externalMenus E LEFT JOIN placeInformation P ON E.placeId = P.placeId LEFT JOIN categories C ON C.categoryId = P.categoryId LEFT JOIN locations L ON L.locationId = P.locationId WHERE E.menuId = '.$_REQUEST['menuId'];
			
			$result = $stuff->query($sql, true, $dbObj);
			
			$html .= '<h3>Menu for ';
			$html .= $result['placeName'] .', '. $result['categoryName'] .', '. $result['locationName'];
			
			$html .= '</h3>';
			
			$html .= '<form method="get" action="/update/menus/'.$_REQUEST['menuId'].'">';
			
			$html .= '<fieldset><div>Approved</div><label><input type="radio" name="approved" value="0" '.($result['approved'] == '0' ? 'checked="checked"' : '').'/>No</label><label><input type="radio" name="approved" value="1" '.($result['approved'] == '1' ? 'checked="checked"' : '').'/>Yes</label></fieldset>';
			
			$html .= '<fieldset><div>Archived</div><label><input type="radio" name="archived" value="0" '.($result['archived'] == '0' ? 'checked="checked"' : '').'/>No</label><label><input type="radio" name="archived" value="1" '.($result['archived'] == '1' ? 'checked="checked"' : '').'/>Yes</label></fieldset>';
			
			$html .= '<label for="input_menu">Menu Url</label><input type="text" id="input_menu" name="menuUrl" value="'.$result['menuUrl'].'"/><br />';
			
			$html .= '<label>&nbsp;</label><button>Submit</button>';
			$html .= '</form>';
			
		}else{
			$html .= 'no menuId';
		}
	}


?>