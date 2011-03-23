<?php

//need to update cmColumns and also alter placeInformation to add that column
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
		
		$info = $_GET;
		if(($info['oldFieldName'] != $info['fieldName']) || ($info['oldFieldType'] != $info['fieldType'])){
			$needsAlter = true;
			$oldFieldName = $info['oldFieldName'];
		}
		
		unset($info['task']);
		unset($info['oldFieldName'], $info['oldFieldType']);
		
		$info['fieldAmendedBy'] = $session->username;
		$info['fieldAmendedDate'] = 'NOW()';
		
		$typeSql = 'SELECT mysqlColumnType, mysqlColumnLength FROM columnTypes WHERE columnTypeId = '.$info['fieldType'];
		$typeResult = $stuff->query($typeSql, true, $dbObj);
		
		if($info['fieldId'] != 'new'){
			$updateResult = $stuff->update('cmColumns', 'fieldId', $info['fieldId'] ,$info, $dbObj);
			if($updateResult){
				$html .=  'Successfully updated cmColumns<br />';
			}else{
				$html .=  'Not successfully updated, issue!';
			}
			
			//alter column name
			//if the column name needs changing then change it so do a describe first on table and also a select to get what it was
			if($needsAlter == true && $updateResult){
						
				//do a select on columnTypes to find out what length the new type should be
				$sql = 'ALTER TABLE placeInformation CHANGE '.$oldFieldName.' '.$info['fieldName'].' '.$typeResult['mysqlColumnType'];
				if($typeResult['mysqlColumnLength']){
					$sql .= '('.$typeResult['mysqlColumnLength'].')';
				}
				$alterResult = $stuff->alter($sql, $dbObj);
				
				if($alterResult != false){
					$html .=  'Successfully altered table<br />';
				}
				
			}
			
		}else{
			$info['fieldAddedDate'] = 'NOW()';
			$info['fieldAddedBy'] = 'admin';
			$info['fieldId'] = 'NULL';//null as its new so it'll get updated when updated.
			
			$result = $stuff->replace('cmColumns',$info, $dbObj);
			if($result){
				$html .=  'Insert was successful into cmColumns<br />';
			}else{
				$html .=  'Error inserting into cmColumns<br />';
			}
			
			$sql = 'ALTER TABLE placeInformation ADD '.$info['fieldName'].' '.$typeResult['mysqlColumnType'];
			if($typeResult['mysqlColumnLength']){
				$sql .= '('.$typeResult['mysqlColumnLength'].')';
			}
			if($typeResult['mysqlColumnDefault']){
				$sql .= ' DEFAULT '.$typeResult['mysqlColumnDefault'];
			}
			$alterResult = $stuff->alter($sql, $dbObj);
			if($alterResult != false){
				$html .=  'Successfully altered table<br />';
			}else{
				$html .=  'Error altering table<br />';
			}
		}
	}
	

?>