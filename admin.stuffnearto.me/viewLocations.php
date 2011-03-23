<?php
	global $dbObj;
	global $session;
	global $loginEditDelete;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		$sql = 'SELECT *, UNIX_TIMESTAMP(lastAmendedDate) as lastAmendedDate FROM locations';
		
		$result = $stuff->query($sql, false, $dbObj);
		
		$html .= '<table class="viewTables"><thead>';
		if($loginEditDelete){
			$html .= '<tr><th colspan="5" class="addRow"><a href="/edit/location/new">Add <img src="/images/icons/add.png" /></a></th></tr>';
		}
		$html .= '<tr class="headerRow">
			<th class="name">Location Name</th>
			<th>Last Amended</th>
			<th>Amended By</th>
			'.($loginEditDelete ? '<th></th>
			<th></th>' : '').'
		</tr></thead><tbody>';
		foreach($result as $k=>$v){
			$v['locationName'] = ucwords($v['locationName']);
			$i++;
			$html .= '<tr class="'.($i%2 ? 'even' : 'odd').'">
				<td class="name"><a href="/view/place/'.$v['locationId'].'" title="View places in '.$v['locationName'].'">'.$v['locationName'].'</a></td>
				<td class="dateAmended">'.(date("d/m/y", $v['lastAmendedDate'])).'</td>
				<td class="amendedBy">'.$v['lastAmendedBy'].'</td>
				'.($loginEditDelete ? '<td class="edit"><a href="/edit/location/'.$v['locationId'].'">Edit <img src="/images/icons/page_edit.png" /></a></td>
				<td class="delete"><a href="/delete/location/'.$v['locationId'].'">Delete <img src="/images/icons/delete.png" /></a></td>' : '').'
				</tr>';
		}
		$html .= '</tbody></table>';
	}
		
?>
		