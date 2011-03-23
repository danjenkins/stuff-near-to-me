<?php

	function smarty_function_twitter($params, &$smarty){
		
		require_once('/home/jenkins2/public_html/stuffnearto.me/classes/stuffTwitter.php');
		$twitter = new stuffTwitter();
		$result = $twitter->userTimeline(false, 4);
		//var_dump($result);//$result has loads of information about our twitter account
		
		global $stuff;
		
		foreach($result as $k=>$v){
			$arr[] = get_object_vars($v);
		}
		$result = $arr;
		if($params['var']){
			$smarty->assign($params['var'],$result);
		}else{
			return $result;
		}
	
	}


?>