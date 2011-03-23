<?php
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
		
		if($_GET['username']){			
			
			global $database;
			global $stuff;
		
			$q = 'SELECT username,email,userlevel,adminPlaces FROM '.TBL_USERS.' WHERE username = "'.$_GET['username'].'"';
			$result = $database->query($q);
			$sql .= 'SELECT P.placeId,P.placeName,L.locationName,C.categoryName FROM placeInformation P LEFT JOIN categories C ON P.categoryId = C.categoryId LEFT JOIN locations L ON P.locationId = L.locationId ORDER BY L.locationName ASC, C.categoryName ASC, P.placeName ASC';
			$result2 = $stuff->query($sql);
			
			foreach($result2 as $m=>$n){
				$newArr[$n['locationName']][$n['categoryName']][] = $n;
			}
			
			if($form->num_errors > 0){
				$html .= 'Error with request, please fix';
			}
			
			$html .= '<form action="/update/user/'.$_GET['username'].'" method="get">';
			while($row = mysql_fetch_assoc($result)){
				foreach($row as $k=>$v){
					if($k == 'adminPlaces'){
						if($row['userlevel'] >= '5'){
							$html .= '<label>User Admin Places:</label><br />';
							$html .= '<ul id="placeAdministration">';
							foreach($newArr as $j=>$g){
								$html .= '<li class="locationName">'.(ucwords($j)).'</li>';
								$html .= '<ul>';
								foreach($g as $h=>$i){
									$html .= '<li>'.(ucwords($stuff->splitCamelCase($h))).'</li>';
									$html .= '<ul>';
									foreach($i as $d=>$a){
										$html .= '<li><label><input type="checkbox" name="place['.$a['placeId'].']" value="1" />'.ucwords($a['placeName']).'</label></li>';
									}
									$html .= '</ul>';
								}
								$html .= '</ul>';
							}
							$html .= '</ul>';						
						}
					}else{
						$html .= '<label for="input_'.$k.'">'.$k.'</label>';
						if($k == 'userlevel'){
							$html .= '<select name="'.$k.'"><option value="1" '.($v == '1' ? 'selected="selected"' : '').'>Basic User</option><option value="5" '.($v == '5' ? 'selected="selected"' : '').'>Place Admin</option><option value="9" '.($v == '9' ? 'selected="selected"' : '').'>Super Admin</option></select><br />';
						}else{
							$html .= '<input id="input_'.$k.'" type="text" name="'.$k.'" value="'.$v.'"/><br />';
						}
					}
				}
			}
			$html .= '<label>&nbsp;</label><button>Edit User</button></form>';
		}else{
			$html .= 'no username passed in';
		}
	}



?>