<?php

	function smarty_function_categories($params, &$smarty){
		
		$params['id'] = ($params['id'] ? $params['id'] : 'category');
		$params['name'] = ($params['name'] ? $params['name'] : 'category');
		
		
		if(!method_exists($stuff, 'getIdAndName')){
			require_once(site_path.'/classes/stuffClass.php');
			$stuff = new stuffDB();
		}
		$result = $stuff->getIdAndName('category');
		
		asort($result);
		
		$html = '<select name="'.$params['name'].'" id="'.$params['id'].'">';
		$html .= '<option value="0">All</option>';
		foreach($result as $k=>$v){
			$html .= '<option value="'.$k.'">'.ucwords($stuff->splitCamelCase($v)).'</option>';
		}
		$html .= '</select>';
		
		return $html;
	}


?>