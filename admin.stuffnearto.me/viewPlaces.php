<?php	
	global $dbObj;
	global $session;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_GET['locationId']){		
			$sql = 'SELECT I.placeName, I.placeId,UNIX_TIMESTAMP(I.dateAmended) as dateAmended,I.amendedBy, C.categoryName, C.categoryId, L.locationName FROM placeInformation I LEFT JOIN categories C ON C.categoryId = I.categoryId LEFT JOIN locations L ON I.locationId = L.locationId WHERE I.locationId = '.$_GET['locationId'].' AND I.categoryId != 1' ;//1 is location, dont need those agian
			
			if($session->allowedEditPlaces){
				$sql .= ' AND (';
				$jj= 0;
				foreach($session->allowedEditPlaces as $j=>$i){
					$jj++;
					if($jj != 1){
						$sql .= ' OR ';
					}
					$sql .= 'I.placeId = "'.$j.'"';
				}
				$sql .= ')';
			}
			
			$sql .= ' ORDER BY I.placeName ASC';
			
			//$stuff->debug($sql,true);
	
			
			$result = $stuff->query($sql, false,$dbObj);
			
			foreach($result as $k=>$v){
				if( stristr($v['placeName'], 'the')){
					$placeName = str_ireplace('the', '', $v['placeName']).', the';
				}else{
					$placeName = $v['placeName'];
				}
				$placeName = ucwords($placeName);
				foreach($v as $key=>$value){
					if(!in_array($key, array('categoryId', 'placeName'))){
						$arr[$v['categoryId']][$placeName][$key] = $value;
					}
				}
			}
			//get all of the categories
			$categories = $stuff->getCategories();
			ksort($arr);
			$html .= '<table class="viewTables" cellspacing="0">';
			foreach($categories as $c=>$t){
				$html .= '<tr style="background-color:#cd6534;"><td colspan="6" class="addRow">'.ucwords($stuff->splitCamelCase($t
				)).' - <a href="/edit/place/new?locationId='.$_GET['locationId'].'&categoryId='.$c.'">New <img src="/images/icons/add.png" /></a></td></tr>';
				if(is_array($arr[$c])){
					$html .= '<tr><th>Place Name</th><th>date Amended</th><th>Amended By</th><th>Edit</th><th>Delete</th><th>View Online</th></tr>';
					$i = 0;
					foreach($arr[$c] as $j=>$h){
					//	$stuff->debug($h);
						$i++;
						$html .= '<tr class="'.($i%2 ? 'even' : 'odd').'">
							<td class="placeName"><a href="/edit/place/'.$h['placeId'].'">'.$j.'</a></td>
							<td class="dateAmended">'.date('d/m/y', $h['dateAmended']).'</td>
							<td class="amendedBy">'.$h['amendedBy'].'</td>
							<td class="edit"><a href="/edit/place/'.$h['placeId'].'">Edit <img src="/images/icons/page_edit.png" /></a></td>
							<td class="delete"><a href="/delete/place/'.$h['placeId'].'">Delete <img src="/images/icons/delete.png" /></a></td>
							<td><a target="_blank" href="http://www.stuffnearto.me/'.$h['locationName'].'/'.$h['categoryName'].'/'.$h['placeId'].'/'.urlencode($j).'">View Online <img src="/images/icons/world.png" /></a></td>
						</tr>';
					}
				}else{
					$html .= '<tr><td colspan="5" class="noPlaces">No Places in this category</td></tr>';
				}
			}
			$html .= '</table>';		
			unset($result);		
		}else{
			$html .=  'no LocationId';
		}
	}
	
?>