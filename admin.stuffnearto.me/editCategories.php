<?php

	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['categoryId'] == 'new'){
			$sql = 'SHOW COLUMNS FROM categories';
			$result = $stuff->query($sql,true,$dbObj);
			
			foreach($result as $u=>$f){
				$arr[$f['Field']] = '';
			}
		}else{	
			$sql = 'SELECT * FROM categories WHERE categoryId = "'.mysql_escape_string($_GET['categoryId']).'"';
			
			$result = $stuff->query($sql,true,$dbObj);
			$arr = $result;
		}
		
		$html .= '<form action="/update/categories/'.($_GET['categoryId'] == 'new' ? 'new' : $result['categoryId'] ).'">';
		
		foreach($arr as $k=>$v){
			if(!in_array($k, array('categoryId', 'dateAdded', 'addedBy', 'dateAmended', 'amendedBy'))){
				$html .= '<label for="i_'.$k.'">'.ucwords($stuff->splitCamelCase($k)).'</label><input type="text" id="i_'.$k.'" name="'.$k.'" value="'.$v.'"/><br />';
			}
		}
		$html .= '<label>&nbsp;</label><button>submit</button>';
		$html .= '</form>';
	}

?>