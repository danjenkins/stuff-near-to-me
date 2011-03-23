<?php
	function smarty_function_isUrlImage($params, &$smarty){
		
		
		
		
		/*
$result['image'] = false;
		
		$urlParams = array('http' => array(
		'method' => 'HEAD'
		));
     
		$ctx = stream_context_create($urlParams);
     	
     	
     	if(strpos($params['url'], '/') == 0){
     		$params['url'] = 'http://'.$_SERVER['HTTP_HOST'].$params['url'];
     	}
     	
     	
		$fp = @fopen($params['url'], 'rb', false, $ctx);
     	
		if (!$fp){
			$result['image'] = false;  // Problem with url
		}

		$meta = stream_get_meta_data($fp);
		
		if ($meta === false){
			fclose($fp);
			return false;  // Problem reading data from url
		}
		
		$wrapper_data = $meta["wrapper_data"];
		if(is_array($wrapper_data)){
			foreach(array_keys($wrapper_data) as $hh){
				if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image"){ // strlen("Content-Type: image") == 19
					fclose($fp);
					$result['image'] = true;
				}
			}
		}

		fclose($fp);
*/
		
		preg_match('/^.*\.(jpg|jpeg|png|gif)$/i', $params['url'], $matches);
		
		if($matches){
			$result['image'] = true;
			list($width, $height, $type, $attr) = getimagesize($params['url']);
			$result['width'] = $width;
			$result['height'] = $height;
		}else{
			$result['image'] = false;
		}
		
		$smarty->assign($params['var'], $result);
		return false;
	}
?>