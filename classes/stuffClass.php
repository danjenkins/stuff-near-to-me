<?php

	/*
	 *	@author Daniel Jenkins
	 *	@title	stuffClass.php
	 *	@description - heart of stuffnearto.me
	 *	@description - where all the db connections get made and closed.
	*/

	class stuffDB{
		
		function init(&$param){
			//$this->debug('init()');
			$dbHost = (isset($param['dbhost']) ? $param['dbhost'] : 'localhost');
			$dbUser = (isset($param['dbuser']) ? $param['dbuser'] : 'jenkins2_stuff');
			$dbPass = (isset($param['dbpass']) ? $param['dbpass'] : 'Stuffn3ar2me');
			$dbName = (isset($param['dbname']) ? $param['dbname'] : 'jenkins2_stuff');
			
			$db = mysql_connect($dbHost, $dbUser, $dbPass);
			if (!$db) {
   				 die('Could not connect: ' . mysql_error());
			}
			mysql_select_db ($dbName, $db);
			return $db;
		}
		function query($query, $shiftIfOne = false){
			//$this->debug($query);
			$this->init();
			$queryResult = mysql_query($query);
			if(!mysql_error()){
				if(mysql_affected_rows() > 0){
					while($row = mysql_fetch_assoc($queryResult)){
						//$this->debug($row);
						$tmpResult[] = $row;
					}
				}else{
					$tmpResult = false;
				}
				if($shiftIfOne == true && $tmpResult && is_array($tmpResult) && count($tmpResult) == 1){
					$tmpResult = array_shift($tmpResult);
				}
			}else{
				$tmpResult = false;
				die('Error is: '.mysql_error());
			}
			return $tmpResult;
		}
		function alter($query, $db = false){
			$queryResult = mysql_query($query);
			if(!mysql_error()){
				$tmpResult = true;
			}else{
				$tmpResult = false;
				die('Error is: '.mysql_error());
			}
			return $tmpResult;		
		}
		function delete($query, $db){
			$queryResult = mysql_query($query);
			if(!mysql_error()){
				//$this->debug(mysql_affected_rows());
				if(mysql_affected_rows()){
					$tmpResult = mysql_affected_rows();
				}else{
					$tmpResult = 0;
				}
			}else{
				//$this->debug('error!');
				$tmpResult = false;
				die('Error is: '.mysql_error());
			}
			return $tmpResult;
		}
		function replace($tableName, $data, $db = ''){
			
			$columns = $values = '';
			$i = 0;
	
			foreach($data as $k=>$v){
				$i++;
				if($i != 1){
					$columns .= ',';
					$values .= ',';
				}
				$columns .= $k;
				if($v == 'NULL'){
					$values .= 'NULL';
				}elseif($v == 'NOW()'){
					$values .= 'NOW()';
				}else{
					$values .= '"'.mysql_real_escape_string( stripslashes($v),$db).'"';
				}
				
			}
			
			$query = "REPLACE INTO `$tableName` ($columns) VALUES ($values)";
			//$this->debug($query);
			$queryResult = mysql_query($query, $db);
			if(!mysql_error($db)){
				$tmpResult = true;
			}else{
				$tmpResult = false;
			}
			return $tmpResult;
		}
		
		function update($tableName, $whereColumn, $whereValue, $data, $db = ''){
			
			$setValues = '';
			$i = 0;
			unset($data[$whereColumn]);
			foreach($data as $k=>$v){
				$i++;
				if($i != 1){
					$setValues .= ', ';
				}
				$setValues .= $k.' = ';
				if($v == 'NULL'){
					$setValues .= 'NULL';
				}elseif($v == 'NOW()'){
					$setValues .= 'NOW()';
				}else{
					$setValues .= '"'.mysql_real_escape_string($v,$db).'"';
				}
				
			}
			
			$query = 'UPDATE `'.$tableName.'` SET '.$setValues.' WHERE '.$whereColumn.' = "'.$whereValue.'"';
			//$this->debug($query,true);
			$queryResult = mysql_query($query, $db);
			if(!mysql_error($db)){
				$tmpResult = true;
			}else{
				$tmpResult = false;
			}
			return $tmpResult;
		}
		
		function finish($db){
			mysql_close($db);
		}
		function getIdAndName($searchFor,&$key){
			//key can be id or name
			if(!isset($key)){
				$key = 'id';
			}
			if($searchFor == 'category'){
				$searchTable = 'categories';
			}elseif($searchFor == 'location'){
				$searchTable = 'locations';
			}elseif($searchFor == 'columnType'){
				$searchTable = 'columnTypes';
			}
			
			if($searchFor == 'category' || $searchFor == 'location' || $searchFor == 'columnType'){
				$this->init();
				$sql = 'SELECT '.$searchFor.'Id, '.$searchFor.'Name FROM '.$searchTable;
				
				$tmpArray = $this->query($sql);

				foreach($tmpArray as $k=>$v){
					if($key == 'id'){
						$categories[$v[$searchFor.'Id']] = $v[$searchFor.'Name'];
					}elseif($key == 'name'){
						$categories[$v[$searchFor.'Name']] = $v[$searchFor.'Id'];
					}
				}
				
				unset($tmpArray);
			}else{
				$categories = false;
			}
			return $categories;
		}
		function getCategories(&$key){
			$cats = $this->getIdAndName('category', $key);
			unset($cats[1], $cats['location']);
			return $cats;
		}
		function getLocations(&$key){
			return $this->getIdAndName('location', $key);
		}
		function getColumnTypes(&$key){
			return $this->getIdAndName('columnType', $key);
		}
		
		
		function getPlaceInfo(&$location, &$category, &$place,$dontGetArchivedOrUnapproved = false){
			//$this->debug('getPlaceInfo()');
			$this->init();//if it hasnt been initialised then initialise it, if it has already it wont open another by default
			$by = 'name';
			if($category){
				$categories = $this->getCategories($by);
			}
			
			if($location){
				$locations = $this->getLocations($by);
			}
			//$this->debug($categories);
			
			
			//get rid of the two look ups above and do 2 left joins
			if(isset($place)){
			
				$sq = 'SELECT F.fieldName FROM cmColumns F LEFT JOIN categories C ON F.relatedCategory = C.categoryId WHERE C.categoryName = "'.$category.'" OR F.relatedCategory = 0';
				
				$re = $this->query($sq);
				$sql = 'SELECT ';
				$i = 0;
				foreach($re as $k=>$v){
					$i++;
					if($i != 1){
						$sql .= ', ';
					}
					$sql .= 'P.'.$v['fieldName'];
				}
			
				$sql .= ', L.locationName, C.categoryName FROM placeInformation P LEFT JOIN locations L ON P.locationId = L.locationId LEFT JOIN categories C ON P.categoryId = C.categoryId  WHERE placeId = "'.$place.'"';
				if($category){
					if(is_string($category) && $categories[$category]){
						$sql .= ' AND P.categoryId = "'.$categories[$category].'"';
					}elseif(!is_string($category)){
						$sql .= ' AND P.categoryId = "'.$category.'"';
					}
				}
				if($location){
					if(is_string($location) && $locations[$location]){
						$sql .= ' AND P.locationId = "'.$locations[$location].'"';
					}elseif(!is_string($location)){
						$sql .= ' AND P.locationId = "'.$location.'"';
					}
				}
				if($dontGetArchivedOrUnapproved){
					$sql .= ' AND P.approved = "1" AND P.archived != "1"';
				}
			}
			
			$importantInfo = $this->query($sql, true);
			
			$menuSql = 'SELECT imageLocation FROM images WHERE approved = "1" AND menu = "1" AND archived != "1" AND placeId = "'.$place.'"';
			$aa = $this->query($menuSql);
			
			$extMenSql = 'SELECT menuUrl FROM externalMenus WHERE approved = "1" AND archived != "1" AND placeId = "'.$place.'"';
			$ab = $this->query($extMenSql);
			
			
			foreach($importantInfo as $j=>$n){
				$arr[str_replace($category, '', $j)] = $n;
			}
			foreach($aa as $s=>$a){
				$arr['menuLocation'][] = $a['imageLocation'];
			}
			
			foreach($ab as $l=>$s){
				$arr['menuLocation'][] = $s['menuUrl'];
			}
			
			unset($importantInfo);
			return $arr;
		}
		
		function getPlacesForCategory(&$location, &$category, $archivedAndApproved = false){
			
			$this->init();//if it hasnt been initialised then initialise it, if it has already it wont open another by default
			
			$sql = 'SELECT I.placeId, I.placeName, I.latitude, I.longitude FROM placeInformation I LEFT JOIN locations L ON L.locationName = "'.$location.'" LEFT JOIN categories C ON categoryName = "'.$category.'"  WHERE I.categoryId = C.categoryId';//1 is a place category so we dont want that
			if($archivedAndApproved){
				$sql .= ' AND I.archived != "1" AND I.approved = "1"';
			}
			$info = $this->query($sql);
			return $info;
		}
		
		function getLocationInfo(&$location){
			//$this->debug('getPlaceInfo()');
			$this->init();//if it hasnt been initialised then initialise it, if it has already it wont open another by default
			if(isset($location)){
				$sql = 'SELECT * FROM locations WHERE locationName = "'.$location.'"';
			}
			return $this->query($sql,true);
		}
		function getPlaceListAtLocation(&$location, $archivedAndApproved = false){
			//$this->debug('getPlaceInfo()');
			$this->init();//if it hasnt been initialised then initialise it, if it has already it wont open another by default
			if(isset($location)){
				$sql = 'SELECT P.placeName,P.placeId,P.latitude,P.longitude,C.categoryName FROM placeInformation P LEFT JOIN categories C ON P.categoryId = C.categoryId WHERE locationId = "'.$location.'"';
				if($archivedAndApproved){
					$sql .= ' AND P.archived != "1" AND P.approved = "1"';
				}
				$sql .= ' ORDER BY P.placeName ASC';
			}
			return $this->query($sql,true);
		}
		
		function debug($debugThing, $outputNow){
			if($_REQUEST['debug'] || $outputNow){
				echo '<pre style="text-align:left;">'.print_r($debugThing,true).'</pre>';	
			}		
		}
		
		function splitCamelCase($str) {
			//echo '<pre>'.print_r(preg_split('/(?<=\\w)(?=[A-Z])/', $str),true).'</pre>';
			foreach(preg_split('/(?<=\\w)(?=[A-Z])/', $str) as $k){
				$string .= $k .' ';
			}
			return trim($string);
		}
		function distanceOfTimeInWords($fromTime, $showLessThanAMinute = false) {
		    $distanceInSeconds = round(abs(time() - $fromTime));
		    $distanceInMinutes = round($distanceInSeconds / 60);
		       
		        if ( $distanceInMinutes <= 1 ) {
		            if ( !$showLessThanAMinute ) {
		                return ($distanceInMinutes == 0) ? 'less than a minute' : '1 minute';
		            } else {
		                if ( $distanceInSeconds < 5 ) {
		                    return 'less than 5 seconds';
		                }
		                if ( $distanceInSeconds < 10 ) {
		                    return 'less than 10 seconds';
		                }
		                if ( $distanceInSeconds < 20 ) {
		                    return 'less than 20 seconds';
		                }
		                if ( $distanceInSeconds < 40 ) {
		                    return 'about half a minute';
		                }
		                if ( $distanceInSeconds < 60 ) {
		                    return 'less than a minute';
		                }
		               
		                return '1 minute';
		            }
		        }
		        if ( $distanceInMinutes < 45 ) {
		            return $distanceInMinutes . ' minutes';
		        }
		        if ( $distanceInMinutes < 90 ) {
		            return 'about 1 hour';
		        }
		        if ( $distanceInMinutes < 1440 ) {
		            return 'about ' . round(floatval($distanceInMinutes) / 60.0) . ' hours';
		        }
		        if ( $distanceInMinutes < 2880 ) {
		            return '1 day';
		        }
		        if ( $distanceInMinutes < 43200 ) {
		            return 'about ' . round(floatval($distanceInMinutes) / 1440) . ' days';
		        }
		        if ( $distanceInMinutes < 86400 ) {
		            return 'about 1 month';
		        }
		        if ( $distanceInMinutes < 525600 ) {
		            return round(floatval($distanceInMinutes) / 43200) . ' months';
		        }
		        if ( $distanceInMinutes < 1051199 ) {
		            return 'about 1 year';
		        }
		       
		        return 'over ' . round(floatval($distanceInMinutes) / 525600) . ' years';
		}
		function closestByLatLon($lat,$lon, $return = 5, $category = false){
			//$sql = 'SELECT *, SQRT(POW((69.1 * (locations.latitude - '.$lat.')) , 2 ) + POW((53 * (locations.longitude - '.$lon.')), 2)) AS distance FROM locations ORDER BY distance ASC LIMIT '.$return;
			//echo $sql;
			$sql = 'SELECT P.placeName,P.placeId,L.locationName, C.categoryName, P.latitude, P.longitude, TRUNCATE(SQRT(POW((69.1 * (P.latitude - '.$lat.')) , 2 ) + POW((53 * (P.longitude - '.$lon.')), 2)),1) AS distance FROM placeInformation P LEFT JOIN categories C ON P.categoryId = C.categoryId LEFT JOIN locations L ON P.locationId = L.locationId';
			
			if($category && $category != '0'){
				$sql .= ' WHERE P.categoryId = "'.$category.'" AND';
			}else{
				$sql .= ' WHERE';
			}
			$sql .= ' P.approved = "1" AND P.archived != "1"';		
			
			$sql .= ' ORDER BY distance ASC LIMIT '.$return;
			$this->init();
			return $this->query($sql);
		}
		
		function replace_uri($str) {
  			$pattern = '#(^|[^\"=]{1})(http://|ftp://|mailto:|news:)([^\s<>]+)([\s\n<>]|$)#sm';
			return preg_replace($pattern,"\\1<a href=\"\\2\\3\"><u>\\2\\3</u></a>\\4",$str);

		} 
	}

?>