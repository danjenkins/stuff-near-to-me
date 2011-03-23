<?php

	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
		
		if($_GET['fieldId'] == 'new'){
			$sql = 'SHOW COLUMNS FROM cmColumns';
			$result = $stuff->query($sql,$dbObj);
			
			foreach($result as $u=>$f){
				$arr[$f['Field']] = '';
			}
			
		}else{	
			$sql = 'SELECT * FROM cmColumns C LEFT JOIN columnTypes T ON C.fieldType = T.columnTypeId WHERE C.fieldId = "'.mysql_escape_string($_GET['fieldId']).'"';
			
			$result = $stuff->query($sql,true,$dbObj);
			$arr = $result;
		}
		
		$html .= '<form id="edit" action="/update/categoryFields/'.($_GET['fieldId'] == 'new' ? 'new' : $result['fieldId'] ).'">';
		
		foreach($arr as $k=>$v){
			//$stuff->debug($k);
			if(in_array($k, array('fieldName', 'fieldOrder', 'fieldDesc', 'relatedCategory', 'fieldType'))){
				if( $k == 'relatedCategory' || $k == 'fieldType'){
					$html .= '<label>'.(ucwords($stuff->splitCamelCase($k))).'</label><select name="'.$k.'" '.(($_GET['fieldId'] != 'new' && $k == 'relatedCategory') ? 'disabled="disabled"' : '').'>';
					if($k == 'relatedCategory'){
						$cat = $stuff->getCategories();
					}else{
						$cat = $stuff->getColumnTypes();
					}
						foreach($cat as $c=>$t){
							$html .= '<option value="'.$c.'" '.($v == $c ? 'selected="selected"' : '').'>'.(ucwords($stuff->splitCamelCase($t))).'</option>';
						}
					
					
					$html .= '</select><br />';
				}else{
					$html .= '<label>'.(ucwords($stuff->splitCamelCase($k))).'</label><input type="text" name="'.$k.'" value="'.(($k == 'fieldOrder' && $_GET['fieldId'] == 'new')? '100' : $v).'"/><br />';
				}
				
				if($_GET['fieldId'] != 'new' && $k == 'relatedCategory'){
					$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
				}
			}
		}
		$html .= '<input type="hidden" name="oldFieldName" value="'.$arr['fieldName'].'" />';
		$html .= '<input type="hidden" name="oldFieldType" value="'.$arr['fieldType'].'"';
		$html .= '<label>&nbsp;</label><button>submit</button>';
		$html .= '</form>';
	}
	
?>