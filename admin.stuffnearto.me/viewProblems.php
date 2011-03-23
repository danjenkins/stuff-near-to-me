<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		$sql = 'SELECT P.*, R.comment as revComment, R.rating, PI.placeName, PI.placeName as rPlaceName FROM problems P LEFT JOIN reviews R ON R.reviewId = P.reviewId LEFT JOIN placeInformation PI ON P.placeId = PI.placeId OR R.placeId = PI.placeId  WHERE';
		if($_REQUEST['placeId']){//we only want reviws back for a certain place
			//check if we want approved, non approved, archived
			$sql .= ' P.placeId = "'.$_REQUEST['placeId'].'"';
		}elseif($_REQUEST['reviewId']){
			$sql .= ' P.reviewId = "'.$_REQUEST['reviewId'].'"';
		}
		if($_REQUEST['placeId'] || $_REQUEST['reviewId']){
			$sql .= ' AND';
		}
		
		if($_REQUEST['actioned'] == '1'){
			$sql .= ' P.actioned = "1"';
		}else{
			$sql .= ' P.actioned != "1"';
		}
		
		if($session->allowedEditPlaces){
			$sql .= ' AND (';
			$jj= 0;
			foreach($session->allowedEditPlaces as $j=>$i){
				$jj++;
				if($jj != 1){
					$sql .= ' OR ';
				}
				$sql .= 'PI.placeId = "'.$j.'"';
			}
			$sql .= ')';
		}
		
		$sql .= ' ORDER BY P.dateAdded ASC';
		
		//$stuff->debug($sql, true);
		
		$result = $stuff->query($sql, false, $dbObj);
		
		if($result){
			$html .= '<table class="viewTables"><thead>';
			$html .= '<tr class="headerRow">
				<th>Place Name</th>
				<th>Review?</th>
				<th>Review Score/Comment</th>
				<th>Problem Comment</th>
				<th>Added By</th>
				<th>Date Added</th>
				<th>Actioned</th>
				<th>Actioned By</th>
				<th>Actioned On</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr></thead><tbody>';
			$locationName = '';
			$placeName = '';
			$i = 0;
			foreach($result as $k=>$v){				
				$i++;
				$html .= '<tr class="'.($i%2 ? 'even' : 'odd').'">
					<td class="name">'.($v['placeName'] ? $v['placeName'] : $v['rPlaceName']).'</a></td>
					<td class=review">'.($v['reviewId'] ? 'Yes' : '').'</a></td>
					<td class="reviewInfo">'.($v['revComment'] ? $v['rating'].'/10 - '.$v['revComment'] : '').'</td>
					<td class="comment">'.$v['comment'].'</td>
					<td class="addedBy">'.$v['addedBy'].'</td>
					<td class="addedDate">'.(date("d/m/y", strtotime($v['dateAdded']))).'</td>
					<td class="actioned">'.$v['actioned'].'</td>
					<td>'.$v['lastActionedBy'].'</td>
					<td>'.($v['dateLastActioned'] ? (date("d/m/y", strtotime($v['dateLastActioned']))) : '').'</td>
					<td class="edit"><a href="/edit/problems/'.$v['problemId'].'">Edit <img src="/images/icons/page_edit.png" /></a></td>
					<td class="delete"><a href="/delete/problems/'.$v['problemId'].'">Delete <img src="/images/icons/delete.png" /></a></td>
					</tr>';
			}
			$html .= '</tbody></table>';
			//$html .= '<pre>'.print_r($result, true).'</pre>';
		}else{
			$html .= 'No results';
		}
	}
		
?>
		