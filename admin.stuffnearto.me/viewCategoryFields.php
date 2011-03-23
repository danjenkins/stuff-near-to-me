<?php
	
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($loginEditDelete){
			$sql = 'SELECT *, UNIX_TIMESTAMP(C.fieldAmendedDate) as catFieldAmendedDate FROM cmColumns C LEFT JOIN columnTypes T ON C.fieldType = T.columnTypeId LEFT JOIN categories CA ON CA.categoryId = C.relatedCategory';
			$result = $stuff->query($sql);
			
			foreach($result as $k=>$v){
				if($v['categoryName']){
					$handler = $v['categoryId'];
				}else{
					$handler = 'core';
				}
				$newArr[$handler][] = $v;
			}
			
			$categories = $stuff->getCategories();
		
			$html .= '<table class="viewTables" id="categoryFields">';
			foreach($categories as $k=>$v){
				if($k != 1){
					$html .= '<tr class="categoryName"><td colspan="8">'. (ucwords($stuff->splitCamelCase($v))) .' - <a href="/edit/categoryFields/new?categoryId='.$k.'">Add <img src="/images/icons/add.png" /></a></td></tr>';
					$i = 0;
					foreach($newArr[$k] as $j=>$n){
						$i++;
						$html .= '<tr class="'.($i%2 ? 'even' : 'odd').'"><td><a href="/edit/categoryFields/'.$n['fieldId'].'">'.(ucwords($stuff->splitCamelCase($n['fieldName']))).'</a></td><td>'.$n['fieldOrder'].'</td><td>'.(ucwords($stuff->splitCamelCase($n['columnTypeName']))).'</td><td>'.$n['fieldDesc'].'</td><td>'.(date('d/m/y',$n['catFieldAmendedDate'])).'</td><td>'.$n['fieldAmendedBy'].'</td><td><a href="/edit/categoryFields/'.$n['fieldId'].'">Edit<img src="/images/icons/page_edit.png" /></a></td><td><a href="/delete/categoryFields/'.$n['fieldId'].'">Delete <img src="/images/icons/delete.png" /></a></td></tr>';
					}
				}
			}
			$html .= '</table>';
		}else{
			$html .= 'You\'re not allowed here!';
		}
	}
?>