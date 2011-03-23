<?php

	function smarty_function_stuffImage($params, &$smarty){
		
		global $stuff;
		
		$params['noToReturn'] = isset($params['noToReturn']) ? $params['noToReturn'] : '2';
		
		if(!$stuff){
			require_once(site_path.'/classes/stuffClass.php');
			$stuff = new stuffDB();
		}
		
		//decide what we're looking for
		if($params['place'] || $params['category'] || $params['location']){
			$sql = 'SELECT * FROM images I LEFT JOIN imageTags T ON I.imageId = T.imageId ';
			
			
			$sql .= 'WHERE ';
			if($params['place']){
				$sql .= 'I.placeId = "'.$params['place'].'"';
			}elseif($params['category']){
				$sql .= 'I.categoryId = "'.$params['category'].'"';
			}elseif($params['location']){
				$sql .= 'I.locationId = "'.$params['location'].'"';
			}
			
			$sql .= ' AND I.menu != "1" AND I.approved = 1 AND I.archived != 1';
			
			if($params['tags']){
				$arr = explode(',', $params['tags']);
				$sql .= ' AND (';
				$i = 0;
				foreach($arr as $k){
					$i++;
					if($i != 1){
						$sql .= ' OR ';
					}
					$sql .= ' T.tagText LIKE "%'.$k.'%" ';
				}
				$sql .= ')';
			}
			
			$sql .= ' GROUP BY I.imageId';
		}
		$result = $stuff->query($sql);
		$noInArray = count($result) + 1;
		
		if(!isset($params['noHtml'])){
			if(($noInArray < $params['noToReturn'])){
				$params['noToReturn'] = $noInArray;
			}
			
			
			$html = '';
			
			shuffle($result);
	
			for($g = 0; $g <= ($params['noToReturn'] - 1 );$g++){
				//randomise the array
				//then check if file exists, if it does, add some html, if it doesnt then dont
				list($width, $height, $type, $attr) = getimagesize(site_path.$result[$g]['imageLocation']);
				
				//do some checking here to see if the image is smaller than the width passed in, if it is then use the actual width and height
				$imgWidth = $width;
				$imgHeight = $height;
				
				
				$filePath = site_path.$result[$g]['imageLocation'];
				$html .= '<div class="polaroid '.$g.'">
					<span class="tape tapeTL"></span>
					<img src="'.$result[$g]['imageLocation'].'" width="'.$imgWidth.'" height="'.$imgHeight.'" alt="'.$result[$g]['imageAlternateText'].'" class="'.$params['imgClass'].' auto" />
					<span class="tape tapeBR"></span>
				</div>';
				//unset the element from the array
				unset($result[$g]);			
			}
				
			return $html;
		}else{
			if($result){
				if(!isset($params['var'])){ $params['var'] = 'imageList';}
				$smarty->assign($params['var'], $result);
			}
		}
		
	
	
	}

?>