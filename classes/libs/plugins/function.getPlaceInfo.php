<?php

	function smarty_function_getPlaceInfo($params, &$smarty){
		
		global $stuff;
		
		$params['locationId'] = $params['locationId']/1;
		$params['categoryId'] = $params['categoryId']/1;
		$params['placeId'] = $params['placeId']/1;
		
		$result = $stuff->getPlaceInfo($params['locationId'], $params['categoryId'], $params['placeId']);
		if($result){
			$smarty->assign($params['var'], $result);
		}
		
	}
	
?>