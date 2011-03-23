<?php

	global $dbObj;
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
		
		if($_GET['task'] == 'viewImages' || $_GET['task'] == 'viewMenus'){
		
			$sql = 'SELECT I.*, P.placeName, C.categoryName, L.locationName FROM images I LEFT JOIN placeInformation P ON I.placeId = P.placeId LEFT JOIN categories C ON I.categoryId = C.categoryId  OR P.categoryId = C.categoryId LEFT JOIN locations L ON I.locationId = L.locationId OR P.locationId = L.locationId WHERE I.approved ';
			
			if($_GET['approved'] == '1'){
				$sql .= '= ';
			}else{
				$sql .= '!= ';
			}
			
			$sql .= '"1" AND I.archived';
			
			if($_GET['archived'] == '1'){
				$sql .= '= ';
			}else{
				$sql .= '!= ';
			}
			
			$sql .= '"1" AND I.menu ';
			
			
			if($_GET['task'] == 'viewImages'){
				$sql .= '!= ';
			}elseif($_GET['task'] == 'viewMenus'){
				$sql .= '=';
			}
			
			$sql .= ' "1"';
			
			if($session->allowedEditPlaces){
				$sql .= ' AND (';
				$jj= 0;
				foreach($session->allowedEditPlaces as $j=>$i){
					$jj++;
					if($jj != 1){
						$sql .= ' OR ';
					}
					$sql .= 'P.placeId = "'.$j.'"';
				}
				$sql .= ')';
			}
			
			$sql .= ' ORDER BY L.locationName ASC, C.categoryName ASC, P.placeName ASC, I.dateAdded DESC';
			
			$result = $stuff->query($sql, false, $dbObj);
			
			$images = array();
			foreach($result as $k=>$v){
				if($v['placeName']){
					$images['places']['images'][] = $v;
				}elseif($v['categoryName']){
					$images['categories']['images'][] = $v;
				}elseif($v['locationName']){
					$images['locations']['images'][] = $v;
				}
			}
			
			if($_GET['task'] == 'viewMenus'){
				$sql1 = 'SELECT E.*, P.placeName, C.categoryName, L.locationName FROM externalMenus E LEFT JOIN placeInformation P ON E.placeId = P.placeId LEFT JOIN categories C ON C.categoryId = P.categoryId LEFT JOIN locations L ON L.locationId = P.locationId WHERE E.approved ';
			
				if($_GET['approved'] == '1'){
					$sql1 .= '= ';
				}else{
					$sql1 .= '!= ';
				}
				
				$sql1 .= '"1" AND E.archived';
				
				if($_GET['archived'] == '1'){
					$sql1 .= '= ';
				}else{
					$sql1 .= '!= ';
				}
				
				$sql1 .= '"1"';
				
				if($session->allowedEditPlaces){
					$sql1 .= ' AND (';
					$jj= 0;
					foreach($session->allowedEditPlaces as $j=>$i){
						$jj++;
						if($jj != 1){
							$sql1 .= ' OR ';
						}
						$sql1 .= 'P.placeId = "'.$j.'"';
					}
					$sql1 .= ')';
				}
				
				
				$sql1 .= ' ORDER BY P.placeName ASC, E.dateAdded DESC ';
				
				//$stuff->debug($sql1, true);
				
				$result1 = $stuff->query($sql1, false, $dbObj);
				
				foreach($result1 as $t=>$u){
					$images['places']['external'][] = $u;
				}			
			}
			
			
			if(count($images)){
				$html .= '<table class="viewTables">';
				$html .= '<tr>
					<th>Image</th>
					<th>Date Added</th>
					<th>Added By</th>
					<th>Approved</th>
					<th>Archived</th>
					<th>Approved By</th>
					<th>Date Approved</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>';
				
				$place = '';
				$location = '';
				function outputRows($values, $type){
					global $stuff;
					//$stuff->debug($values, true);
					
					global $place;
					global $location;
					$varvar = '';
					
					if($location != $values['locationName']){
						$varvar .= '<tr style="background-color:#3b3636;"><td colspan="9" style="color:white;">'.ucwords($values['locationName']).'</td></tr>';
					}
					if($place != $values['placeName']){
						$varvar .= '<tr style="background-color:#cd6543;"><td colspan="9" style="color:white;">'.ucwords($values['placeName']).'</td></tr>';
					}
					
					
					$place = $values['placeName'];
					$location = $values['locationName'];
					
					$varvar .= '<tr>
						<td>'.($type == 'images' ? '<a href="http://www.stuffnearto.me'.$values['imageLocation'].'" target="_blank"><img src="http://www.stuffnearto.me'.$values['imageLocation'].'" alt="'.$values['imageAlternateText'].'" title="'.$values['imageTitle'].'" width="200px" class="imageFancy" /><a>' : '<a href="'.$values['menuUrl'].'" target="_blank">'.$values['menuUrl'].'</a>').'</td>
						<td>'.(date('d/m/y',strtotime($values['dateAdded']))).'</td>
						<td>'.$values['addedBy'].'</td>
						<td>'.$values['approved'].'</td>
						<td>'.$values['archived'].'</td>
						<td>'.$values['approvedBy'].'</td>
						<td>'.($values['dateApproved'] ? (date('d/m/y', strtotime($values['dateApproved']))) : '').'</td>
						<td><a href="/edit/'.$type.'/'.($type == 'images' ? $values['imageId'] : $values['menuId']).'">Edit <img src="/images/icons/page_edit.png" /></a></td>
						<td><a href="/delete/'.$type.'/'.($type == 'images' ? $values['imageId'] : $values['menuId']).'">Delete <img src="/images/icons/delete.png" /></a></td>
					</tr>';
					
					return $varvar;
				}
			
				foreach($images as $j=>$i){
					if(is_array($i['images'])){
						foreach($i['images'] as $e=>$f){
							$html .= outputRows($f, 'images');
						}
					}
					if(is_array($i['external'])){
						foreach($i['external'] as $e=>$f){
							$html .= outputRows($f, 'menus');
						}
					}
				}
				$html .= '</table>';
			}else{
				$html .= 'No results';
			}
		
		
		}else{
			$html .= 'no imageId passed in';
		
			
			
		
		
		}
	}


?>