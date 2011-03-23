<?php

	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
		$info = $_GET;
		unset($info['task']);
		$info['dateAmended'] = 'NOW()';
		$info['amendedBy'] = $session->username;
		
		if($info['categoryId'] == 'new'){
			$info['dateAdded'] = 'NOW()';
			$info['addedBy'] = $session->username;
			$info['categoryId'] = 'NULL';
			$result = $stuff->replace('categories', $info, $dbObj);
		}else{
			$result = $stuff->update('categories', 'categoryId', $info['categoryId'], $info, $dbObj);
		}
		
		if($result){
			if($info['categoryId'] == 'NULL'){
				$html .= 'Category '.$_GET['categoryName'].' added to table';
			}else{
				$html .= 'Category '.$_GET['categoryName'].' changed in categories table';
			}
			$html .= ', would you like to edit <a href="/edit/category/'.($info['categoryId'] == 'NULL' ? mysql_insert_id() : $info['categoryId']).'">'.$_GET['categoryName'].'</a> or view <a href="/view/categories">all</a> categories</a>';
			
			require_once('../classes/stuffTwitter.php');
			$twitter = new stuffTwitter();
				
			$status = ($_GET['categoryId'] == 'new' ? 'Just added ' : 'Just updated ').' the category '.ucwords($stuff->splitCamelCase($info['categoryName'])).' for all locations.';
			$result = $twitter->update($status);
			
			//var_dump($result);//$result has loads of information about our twitter account
		}else{
			$html .=  'Error updating category '.$_GET['categoryName'].', a super admin has been notified<br />';
			//mail event!
		}
	}

?>