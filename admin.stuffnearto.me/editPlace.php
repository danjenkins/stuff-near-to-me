<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['placeId']){
			
			if($_GET['placeId'] != 'new'){
				$sql2 = 'SELECT I.* FROM placeInformation I WHERE placeId = '.$_GET['placeId'];
				$result2 = $stuff->query($sql2, true, $dbObj);
				$placeId = $result2['placeId'];
			}
			
			
			$sql1 = 'SELECT fieldName,fieldOrder,fieldDesc,columnTypeName FROM cmColumns C LEFT JOIN columnTypes T ON C.fieldType = T.columnTypeId WHERE C.relatedCategory = "0"';
			
			if($result2['categoryId'] || $_GET['categoryId']){
				$sql1 .= ' OR C.relatedCategory = "'.($result2['categoryId'] ? $result2['categoryId'] : $_GET['categoryId']).'"';
			}
			$result = $stuff->query($sql1, true, $dbObj);
		
			$stuff->debug($sql1);
			foreach($result as $k=>$v){
				$temp[$v['fieldName']] = $v['fieldOrder'];
			}
			arsort($temp);
			
			//used in removing restaurant from all the names of columns for labels
			$categoryList = $stuff->getCategories();
			if($_GET['categoryId']){
				$categoryId = $_GET['categoryId'];
			}elseif($result2['categoryId']){
				$categoryId = $result2['categoryId'];
			}
			$currentCatName = $categoryList[$categoryId];

			
			
			foreach($temp as $k=>$v){
				$key[$k]['value'] = $result2[$k];
				foreach($result as $j=>$l){
					if($l['fieldName'] == $k){
						foreach($l as $m=>$b){
							if($b != $k){
								$key[$k][$m] = $b;
							} 
						}
					}
				}
			}
			unset($result, $result2, $temp);
			
			
			
			function outputInputs($name, $val){
				global $stuff;
				global $currentCatName;
				$var = '';
				if(in_array($name,array('categoryId', 'locationId'))){
					if($name == 'categoryId'){
						$select = $stuff->getCategories();
					}elseif($name == 'locationId'){
						$select = $stuff->getLocations();
					}
					if($val['value']){
						$hiddenVal = $val['value'];
					}elseif(!$val['value'] && $name == 'locationId' && $_GET['locationId']){
						$hiddenVal = $_GET['locationId'];
					}elseif(!$val['value'] && $name == 'categoryId' && $_GET['categoryId']){
						$hiddenVal = $_GET['categoryId'];
					}
					$var .= '<input type="hidden" name="'.$name.'" value="'.$hiddenVal.'" />';
					$var .= '<label for="i_'.$name.'">'.(ucwords(str_replace('Id','',$stuff->splitCamelCase($name)))).'</label>';//tidy up if its an Id			
					$var .= '<select id="i_'.$name.'" name="'.$name.'" disabled="disabled">';
					foreach($select as $key => $value){
						if(($_GET['locationId'] == $key && $name == 'locationId') || ($name == 'categoryId' && $key == $_GET['categoryId'])){
							$selected = 'selected="selected"';
						}else{
							$selected = '';
						}
						$var .= '<option value="'.$key.'" '.$selected.'>'.ucwords($value).'</option>';
					}
					$var .= '</select><br />';
				}else{
					if($val['columnTypeName'] == 'dropDown'){
						//get the values out of description and form an array with them
						preg_match('/\[(\S+)\]/', $val['fieldDesc'], $matches);
						$selectVals = explode(',',$matches[1]);
						$var .= '<label for="i_'.$name.'">'.(ucwords($stuff->splitCamelCase(str_ireplace($currentCatName,'',$name)))).'</label>';//tidy up if its an Id
						$var .= '<select id="i_'.$name.'" name="'.$name.'">';
						foreach($selectVals as $k){
							$var .= '<option value="'.$k.'" '.($k == $val['value'] ? 'selected="selected"' : '').'>'.$k.'</option>';
						}
						$var .= '</select><br />';
					}else if($val['columnTypeName'] == 'boolean'){
						$var .= '<fieldset class="booleanContainer"><div>'.(ucwords($stuff->splitCamelCase(str_ireplace($currentCatName,'',$name)))).'</div>';//tidy up if its an Id
						$var .= '<label><input type="radio" name="'.$name.'" '.(($val['value'] == '' || $val['value'] == '0') ? 'checked="checked"' : '').' value="0" />No</label><label><input type="radio" name="'.$name.'" '.($val['value'] == '1' ? 'checked="checked"' : '').' value="1" />Yes</label></fieldset>';
					}else{
						$var .= '<label for="i_'.$name.'">'.(ucwords($stuff->splitCamelCase(str_ireplace($currentCatName,'',$name)))).'</label>';//tidy up if its an Id
						$var .= '<input id="i_'.$name.'" '.($name == "latitude" || $name == 'longitude' || $name == "postCode" ? 'class="'.$name.'Admin"' : '').' type="text" name="'.$name.'" value="'.$val['value'].'" autocomplete="off" /><br />';
					}
				}
				if($matches[0]){
					$val['fieldDesc'] = str_replace($matches[0], ' ', $va['fieldDesc']);
				}
				$var .= '<span class="description">'.$val['fieldDesc'].'</span><br />';
				
				return $var;
			}
			//list of fields that are always going to be there and always at the top of this page. Ignore the order key
			
			$html .= '<form id="edit" method="GET" action="/update/place/'.$_GET['placeId'].'">';
			$html .= '<fieldset><legend>General Information</legend>';
			foreach(array('placeName', 'locationId', 'categoryId', 'meta', 'approved', 'archived') as $e){
				if(is_array($key[$e])){
					$html .= outputInputs($e, $key[$e]);
					unset($key[$e]);
				}	
			}
			$html .= '</fieldset><fieldset><legend>Geocoding</legend>';
			foreach(array('buildingNo','postCode','latitude', 'longitude') as $e){
				if(is_array($key[$e])){
					$html .= outputInputs($e, $key[$e]);
					unset($key[$e]);
				}	
			}
			
			//output a map after those key fields!
			$html .= '<div id="gMap"></div>';
			$html .= '<script>$(function(){
				googleMaps.init();
			})</script>';
			
			$html .= '</fieldset><fieldset><legend>Contact</legend>';
			foreach(array('phoneNumber','faxNumber','email','webAddress') as $e){
				if(is_array($key[$e])){
					$html .= outputInputs($e, $key[$e]);
					unset($key[$e]);
				}	
			}
			
			$html .= '</fieldset<fieldset><legend>Category Specific Information</legend>';
			foreach($key as $k=>$v){
				$html .= outputInputs($k, $v);
			}
			$html .= '</fieldset>';
			$html .= '<label>&nbsp;</label><button>Submit</button>';
			$html .= '</form>';
			
			
		}else{
			$html .= 'no PlaceId passed in';
		}
	}



?>