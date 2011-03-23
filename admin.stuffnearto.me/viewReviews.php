<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		$sql = 'SELECT R.*, P.placeName, L.locationName, C.categoryName FROM reviews R LEFT JOIN placeInformation P ON R.placeId = P.placeId LEFT JOIN locations L ON P.locationId = L.locationId LEFT JOIN categories C ON P.categoryId = C.categoryId WHERE';
		if($_REQUEST['placeId']){//we only want reviws back for a certain place
			//check if we want approved, non approved, archived
			$sql .= ' R.placeId = "'.$_REQUEST['placeId'].'"';
		}
		if($_REQUEST['placeId']){
			$sql .= ' AND';
		}
		if($_REQUEST['archived'] == '1'){//want archived, no matter if theyre approved or not
			
			$sql .= ' R.archived = "1"';
		}elseif($_REQUEST['approved'] == '1'){
			$sql .= ' R.approved = "1"';
		}else{
			$sql .= ' R.approved != 1 AND R.archived != 1';
		}
		
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
		
		$sql .= ' ORDER BY L.locationName ASC, C.categoryName ASC, P.placeName ASC';
		
		$result = $stuff->query($sql, false, $dbObj);
		
		if($result){
			$html .= '<table class="viewTables"><thead>';
			$html .= '<tr class="headerRow">
				<th>Review Score</th>
				<th>Review Comment</th>
				<th>Date submitted</th>
				<th>Added By</th>
				<th>Approved</th>
				<th>Archived</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr></thead><tbody>';
			$locationName = '';
			$placeName = '';
			$i = 0;
			foreach($result as $k=>$v){
				
				if($locationName != $v['locationName']){
					$html .= '<tr class="location" style="background-color:#3b3636;"><td colspan="8" style="color:white;">'.ucwords($v['locationName']).'</td><tr>';
					$i = 0;
				}
				if($placeName != $v['placeName']){
					$html .= '<tr class="place" style="background-color:#cd6543;"><td colspan="8" style="color:white;">'.ucwords($v['placeName']).'</td><tr>';
					$i = 0;
				}
				
				$i++;
				$locationName = $v['locationName'];
				$html .= '<tr class="'.($i%2 ? 'even' : 'odd').'">
					<td class="rating">'.$v['rating'].'/10</a></td>
					<td class="comment">'.$v['comment'].'</a></td>
					<td class="dateAmended">'.(date("d/m/y", strtotime($v['dateAdded']))).'</td>
					<td class="amendedBy">'.$v['addedBy'].'</td>
					<td>'.$v['approved'].'</td>
					<td>'.$v['archived'].'</td>
					<td class="edit"><a href="/edit/reviews/'.$v['reviewId'].'">Edit <img src="/images/icons/page_edit.png" /></a></td>
					<td class="delete"><a href="/delete/reviews/'.$v['reviewId'].'">Delete <img src="/images/icons/delete.png" /></a></td>
					</tr>';
			}
			$html .= '</tbody></table>';
		}else{
			$html .= 'No results';
		}
	}
		
?>
		