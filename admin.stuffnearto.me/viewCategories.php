<?php
	
	global $dbObj;
	global $session;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
		
	
		if($loginEditDelete){
			$sql = 'SELECT * FROM categories ORDER BY categoryName ASC';
			$result = $stuff->query($sql);
			
			$html = '<table class="viewTables"><thead>';
			$html .= '<tr>
					<th colspan="5" class="addRow"><a href="/edit/categories/new">Add <img src="/images/icons/add.png" /></a></th>
				</tr>
				<tr>
					<th class="categoryName">Category Name</th><th class="dateAmended">Date Amended</th><th class="amendedBy">Amended By</th><th class="edit">Edit</th><th class="delete">Delete</th>
				</tr>
				</thead><tbody>';
			$i = 0;
			foreach($result as $k=>$v){
				$i++;
				$html .= '<tr class="'.($i%2 ? 'even' : 'odd').'">
					<td class="categoryName"><a href="/edit/categories/'.$v['categoryId'].'">'.ucwords($v['categoryName']).'</a></td>
					<td class="dateAmended">'.date('d/m/y',strtotime($v['dateAmended'])).'</td>
					<td class="amendedBy">'.$v['amendedBy'].'</td>
					<td class="edit"><a href="/edit/categories/'.$v['categoryId'].'">Edit <img src="/images/icons/page_edit.png" /></a></td>
					<td class="delete"><a href="/delete/categories/'.$v['categoryId'].'">Delete <img src="/images/icons/delete.png" /></a></td>
				</tr>';
			}
			
			$html .= '</tbody></table>';
		}else{
			$html .= 'You shouldnt get here!';
		}
	}

?>