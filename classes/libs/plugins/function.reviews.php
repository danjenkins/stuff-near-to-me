<?php

	function smarty_function_reviews($params, &$smarty){
		
		if(!isset($params['limit'])){ $params['limit'] = '5'; }
		
		global $stuff;
		
		$sql = 'SELECT R.reviewId, R.dateAdded, R.addedBy, R.rating, R.comment FROM reviews R WHERE R.approved = "1" AND R.archived = "0" AND R.placeId = "'.$params['placeId'].'" LIMIT '.$params['limit'];
		
		$result = $stuff->query($sql);
		
		$sql1 = 'SELECT ROUND(SUM(R.rating)/COUNT(R.reviewId),1) as average, COUNT(R.reviewId) as count FROM reviews R WHERE R.approved = "1" AND R.archived = "0" AND R.placeId = '.$params['placeId'];
		
		$result1 = $stuff->query($sql1,true);
		$return['reviews'] = $result;
		
		
		$return['reviewInfo'] = $result1;		
		
		if($result){
			$smarty->assign($params['var'], $return);
		}
		
	}
?>