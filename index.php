<?php
	
	DEFINE('site_path', '/home/jenkins2/public_html/stuffnearto.me/');
	DEFINE('MOBILE_DOMAIN', 'm.stuffnearto.me');
	
	//just in there for testing
	//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	
	error_reporting(0);
	date_default_timezone_set('Europe/London');
	
	//get user information before we do anything else
	include("classes/users/session.php");
	
	require('classes/libs/Smarty.class.php');
	
	//include stuffClass.php so that we can connect to the db.
	require_once('classes/stuffClass.php');
	
	$stuff = new stuffDB();
	
	//make a new smarty instance
	$smarty = new Smarty;
	$smarty->template_dir = 'templates/';
	$smarty->plugin_dir = 'templates/';
	
	//include teraWurfl for mobile detection
	require_once('classes/mobile/TeraWurfl.php');

	// make a new TeraWurfl instance
	$wurfl = new TeraWurfl();
	
	if($_GET['permType']){
		if($_GET['permType'] == 'mobile'){
			$_SESSION['type'] = $session->type = 'mobile';
		}else{
			$_SESSION['type'] = $session->type = 'standard';
		}
	}
	
	// Get the capabilities of the current client. $matched will be true if Tera-WURFL
	// found a match for the device and false if not.
	//this is outputting stuff somehwere!!!
	$matched = $wurfl->getDeviceCapabilitiesFromAgent($_SERVER['HTTP_USER_AGENT']);
	if($matched && $wurfl->getDeviceCapability('is_wireless_device') && (!$session->type || $session->type == 'mobile') && $session->type != 'standard'){
		//check if its a mobile device, if it is then redirect them to m.stuffnearto.me
		if($_SERVER['HTTP_HOST'] != MOBILE_DOMAIN){
			header('Location: http://'.MOBILE_DOMAIN.$_SERVER['REQUEST_URI']);
		}
		$mobileVersion = true;
		$smarty->assign('browserCapabilities', $wurfl->capabilities);
		$smarty->assign('mobileSite',true);
	}else{
		if($wurfl->capabilities){
			$smarty->assign('browserCapabilities', $wurfl->capabilities);
		}else{
			$smarty->assign('browserCapabilities', false);
		}
		
	}
	
	if($_SERVER['HTTP_HOST'] == MOBILE_DOMAIN){
		$mobileVersion = true;
	}
	
	
	//$stuff->debug($_GET);
	
	$dbObj = $stuff->init();//initialise the db connection
	
	if($session->logged_in){
		$smarty->assign('loggedIn', 1);
		$smarty->assign('username', $session->username);
		if($session->isAdmin()){
			$smarty->assign('admin', 1);
		}
	}else{
		$smarty->assign('loggedIn', 0);
	}
	
	$smarty->assign('totalMembers', $database->getNumMembers());
	$smarty->assign('membersOnline', $database->num_active_users);
	$smarty->assign('guestsOnline', $database->num_active_guests);
	$smarty->assign('totalBrowsing', ($database->num_active_guests + $database->num_active_users));
	$smarty->assign('latestMember', $database->latest_member);
	
	if($form->errors){
		$smarty->assign('formErr', $form->errors);
		$smarty->assign('formNumErr', $form->num_errors);
		$smarty->assign('formValues', $form->values);
	}
	if($session->userinfo){
		$smarty->assign('userInfo',$session->userinfo);
	}
	
	if($_GET['user'] || ($session->username && !$_GET['username'])){
		$reqUser = trim($_GET['user']);
		if($reqUser == ''){
			$reqUser = $session->username;
		}
		if ( !$reqUser || strlen($reqUser) == 0 || !eregi("^([0-9a-z])+$", $reqUser) || !$database->usernameTaken($reqUser) ){
   			$smarty->assign('userAskedFor', false);
		}else{
			$smarty->assign('userAskedFor', $reqUser);
			$smarty->assign('userAskedForInfo', $database->getUserInfo($reqUser));
		}
	}
	//get how many places we have in placeInformation
	$totResult = $stuff->query('SELECT COUNT(placeId) as total FROM placeInformation', true);
	
	$smarty->assign('totalPlaces', $totResult['total']);
	unset($totResult);
	
	//if someone registers then lets make it easy for template
	if($_SESSION['regsuccess']){
		$smarty->assign('regUsername', $_SESSION['reguname']);
		if($_SESSION['regsuccess'] != ''){
			//success
			$smarty->assign('registered', 'yes');
		}else{
			//catestrophic failure
			$smarty->assign('registered', 'no');
		}
		unset($_SESSION['regsuccess']);
		unset($_SESSION['reguname']);
	}
	

	
	//take $_GET and get our 4 variables we want the most and put them into an array
	$pageRequire = array('location'=>$_GET['location'], 'category'=>$_GET['category'], 'place'=>$_GET['place'], 'page'=>$_GET['page']);	
	
	if(isset($_GET['task']) && $_GET['task'] == 'search'){
		//do a search and then add the results back to smarty 
		$pageRequire['page'] = 'search';
		
		//do a search in places and in placeInformation
		
		if($_GET['search']){
			$search = split(' ', trim($_GET['search']));
			$searchString = '';
			foreach($search as $k){
				$searchString .= '+'.$k.'* ';
			}
			
			
			$sql = 'SELECT P.placeId, P.placeName, L.locationName, L.locationId, P.categoryId, C.categoryName, P.latitude, P.longitude  FROM placeInformation P LEFT JOIN locations L ON P.locationId = L.locationId LEFT JOIN categories C ON C.categoryId = P.categoryId WHERE MATCH (P.placeName, P.meta, P.postCode) AGAINST ("'.$searchString.'" IN BOOLEAN MODE) ORDER BY P.placeName ASC';
			
			$result = $stuff->query($sql);
			if($result == false){
				$result = array();
			}
			
			$sql2 = 'SELECT L.locationName FROM locations L WHERE MATCH(locationName) AGAINST ("'.$searchString.'" IN BOOLEAN MODE)';
			
			$result2 = $stuff->query($sql2);
			if($result2 == false){
				$result2 = array();
			}
			
		//	$stuff->debug(var_dump((count($result) + count($result2))),true);
			
			if((count($result) + count($result2)) === 1){
				
				//theres only one, dont show them the search page, just send them through to details page
				if(count($result)){
				//$stuff->debug('got in here',true);
					header('Location: /'.(strtolower($result[0]['locationName'])).'/'.(strtolower($result[0]['categoryName'])).'/'.($result[0]['placeId']).'/'.(urlencode($result[0]['placeName'])));
					exit();
				}elseif(count($result2)){
					header('Location: /'.strtolower($result2[0]['locationName']));
					exit();
				}
			}
			
			foreach($result as $k=>$v){
				$tmp[$v['locationName']][$v['categoryName']][] = $v;
			}
			
			//once i have my meta table set up i can query that too
			if($tmp){
				$arr['places'] = $tmp;
			}
			if($result2 && count($result2)){
				$arr['locations'] = $result2;
			}
		}elseif($_GET['lat'] && $_GET['lon']){
			if(!isset($_REQUEST['limit'])){$_REQUEST['limit'] = 10;}
			$arr['geo'] = $stuff->closestByLatLon($_GET['lat'], $_GET['lon'], $_REQUEST['limit'], $_GET['category']);
		}
		
		
		
		$smarty->assign('searchResults', $arr);
		
		
	}else{
		//we want to check what was asked for is available, then pass onto the templates to do the work
		if(!empty($pageRequire['place']) && !empty($pageRequire['location']) && !empty($pageRequire['category'])){
			$stuff->debug('running getPlaceInfo()');
			$urlResult = $stuff->getPlaceInfo($pageRequire['location'], $pageRequire['category'], $pageRequire['place'], true);
			if($urlResult != false){
				$smarty->assign('menuCategories', array('3', '2', '4', '13'));//need to make this db!
				$smarty->assign('placeInformation', $urlResult);
				$smarty->assign('placeId', $pageRequire['place']);

				if(empty($pageRequire['page'])){
					$pageRequire['page'] = 'place';
				}
			}
			
		}elseif(empty($pageRequire['place']) && !empty($pageRequire['category']) && !empty($pageRequire['location'])){
			$stuff->debug('running getPlacesForCategory()');
			$urlResult = $stuff->getPlacesForCategory($pageRequire['location'], $pageRequire['category'], true);
			if($urlResult != false){
				$smarty->assign('locationCategories', $urlResult);
				$smarty->assign('locationName', $pageRequire['location']);
				$smarty->assign('categoryName', $pageRequire['category']);
				if(empty($pageRequire['page'])){
					$pageRequire['page'] = 'category';
				}
			}
		}elseif(!empty($pageRequire['location']) && empty($pageRequire['category']) && !empty($pageRequire['location'])){
			$stuff->debug('getting location info');
			$urlResult = $stuff->getLocationInfo($pageRequire['location']);
			if($urlResult != false){
				$smarty->assign('locationInfo', $urlResult);
				if(empty($pageRequire['page'])){
					$pageRequire['page'] = 'location';
				}
				$urlResult2 = $stuff->getPlaceListAtLocation($urlResult['locationId'], true);
				if($urlResult2 != false){
				
					foreach($urlResult2  as $k=>$v){
						$newArr[$v['categoryName']][] = $v;
					}
					$smarty->assign('placeList', $newArr);
				}
			}
			
		}else{
			$stuff->debug('else');
		}
		if((!isset($urlResult) || $urlResult == false) && empty($pageRequire['page'])){
			if(empty($pageRequire['location']) && empty($pageRequire['category']) && empty($pageRequire['place'])){
				$pageRequire['page'] = 'homepage';
			}else{
				$pageRequire['page'] = '404';//return the error template
				$smarty->assign('error', 'error string for 404, no product found, maybe give a list of maybe\'s with a like select');
			}
		}		
	}
	
	//define default templates and get the ones that have been passed in.
	$templateExistsOk = false;
	$mainTpl = 'main';
	$contentTpl = '';
	$defaultContentTpl = 'welcome';
	$tpl404 = '404';
	if(empty($pageRequire['page'])){
		$stuff->debug('usingDefaultTemplate');
		$pageRequire['page'] = $defaultContentTpl;//use template asked for, if no page passed in then use dafault which is welcome
	}
	$a_matches = array();

	if (isset($pageRequire['page']) && (preg_match("/^([a-zA-Z0-9_-]+)(,([a-zA-Z0-9_-]+))?$/", $pageRequire['page'], $a_matches))) {
		if (!isset($a_matches[3])) {//if 3 isnt there then we're not passing in a new main template.
			$contentTpl = $a_matches[1];//this is the content tpl and default main tpl
		}else{
			$contentTpl = $a_matches[3];//3 is our content tpl 
			$mainTpl = $a_matches[1];//1 is our new main template, was passed in before the comma
		}
		if($mobileVersion){
			$contentTpl = $contentTpl.'_mobile';
		}
	}

	if (!$smarty->template_exists($contentTpl.'.tpl')) {
		$contentTpl = $tpl404;
		if($mobileVersion){
			$contentTpl = $contentTpl.'_mobile';
		}
		$smarty->assign('error', 'Page '.$pageRequire['page'].'.html doesnt exist');
	}
	
	if($mobileVersion){
		$mainTpl = $mainTpl.'_mobile';
	}
	//now check is the main template exists, it must exist, if it doesnt then die
	if (!$smarty->template_exists($mainTpl.'.tpl')) {
		die('ERROR: Main Template not found <br /><pre>'.htmlentities($mainTpl).'</pre>');
	}	
			
	$smarty->assign('mainTpl', $mainTpl.'.tpl');
	$smarty->assign('contentTpl', $contentTpl.'.tpl');
	

	
	
	$smarty->compile_check = true;//what does compile_check do?
	
	//if we pass through smarty_debug_console then allow debugging console!
	//$debugArray = array('yes', 'yup', 'true', '1');
	//if(
	//	(isset($_GET['smarty_debug']) && in_array(strtolower($_GET['smarty_debug']),$debugArray)) || 
	//	(isset($_GET['SMARTY_DEBUG']) && in_array(strtolower($_GET['SMARTY_DEBUG']), $debugArray))
	//	){
		//$smarty->debugging = true;//if we have SMARTY_DEBUG or smarty_debug passed in via get then output debugged script!
	//}
	
	//$_SESSION['previousPages'][] = $_SERVER['REQUEST_URI'];
	
    if(substr($_SERVER['REQUEST_URI'], 0, 8 ) != '/process' && $_SERVER['REQUEST_URI'] != '/favicon.ico' && $mainTpl != 'blank' && !in_array($contentTpl, array('404', '500', '401', '403', '500')) ){
		$_SESSION['previousPages'] = array_merge($_SESSION['previousPages'],array($_SERVER['REQUEST_URI'] =>  time()) );
	}
	
	if($_SERVER['REQUEST_URI'] == '/'){
		 $urlToShow = 'Home';
	}elseif($pageRequire['page'] == 'place'){
		 $urlToShow = ucwords($urlResult['placeName']).', '.ucwords($pageRequire['location']);
	}elseif($pageRequire['page'] == 'category'){
		 $urlToShow = ucwords($pageRequire['category'].', '.ucwords($pageRequire['location']));
	}elseif($pageRequire['page'] == 'location'){
		 $urlToShow = ucwords($pageRequire['location']);
	}elseif($pageRequire['page'] == 'search' && $_GET['search']){
		$urlToShow = 'Search - "'.$_GET['search'].'"';
	}elseif($pageRequire['page'] == 'search' && $_GET['lat']){
		$urlToShow = 'Search by current location';
	}
	else{
		 $urlToShow = $pageRequire['page'];
	}
	
	$niceUrls = $_SESSION['niceUrls'];
	
	$niceUrls[$_SERVER['REQUEST_URI']] = $urlToShow;
	
	$session->niceUrls =  $_SESSION['niceUrls'] = $niceUrls;
	
	asort($_SESSION['previousPages'], SORT_NUMERIC);
      
	$session->visitedPages = $_SESSION['previousPages'];
	
	$smarty->assign('niceUrls', $niceUrls);
	$smarty->assign('visitedPages',array_reverse($session->visitedPages, TRUE));
	
	function ae_detect_ie(){
		if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
			return true;
		}else{
			return false;
		}
	}
	if(ae_detect_ie()){
		$smarty->assign('ie', true);
	}
	//if we pass through smarty_debug_console then allow debugging console!
	//$debugArray = array('yes', 'yup', 'true', '1');
	//if(
	//	(isset($_GET['smarty_debug']) && in_array(strtolower($_GET['smarty_debug']),$debugArray)) || 
	//	(isset($_GET['SMARTY_DEBUG']) && in_array(strtolower($_GET['SMARTY_DEBUG']), $debugArray))
	//	){
		//$smarty->debugging = true;//if we have SMARTY_DEBUG or smarty_debug passed in via get then output debugged script!
	//}
	
	$smarty->display($mainTpl.'.tpl');
	
	$stuff->finish($dbObj);
	
	
?>
