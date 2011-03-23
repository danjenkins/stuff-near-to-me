<?php

	function smarty_function_location($params, &$smarty){
		
		$params['id'] = ($params['id'] ? $params['id'] : 'location');
		$params['name'] = ($params['name'] ? $params['name'] : 'location');
		
		
		if(!method_exists($stuff, 'getIdAndName')){
			require_once(site_path.'/classes/stuffClass.php');
			$stuff = new stuffDB();
		}
		$result = $stuff->getIdAndName('location');
		
		asort($result);
		
		if(!isset($params['selected'])){
			$userInfo = $smarty->get_template_vars('userInfo');
			if($userInfo['location']){
				$params['selected'] = $userInfo['location'];
			}
		}
		
		$html = '<select name="'.$params['name'].'" id="'.$params['id'].'">';
		if($params['addBlankOption'] == true){
			$html .= '<option value="">None</option>';
		}
		foreach($result as $k=>$v){
			$html .= '<option value="'.$v.'"'.($params['selected'] == $v ? 'selected="selected"' : '').'>'.ucwords($v).'</option>';
		}
		$html .= '</select>';
		
		return $html;
	}
	
?>