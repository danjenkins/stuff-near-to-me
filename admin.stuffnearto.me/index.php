<?php
	//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	error_reporting(0);
	include("../classes/users/session.php");
	
	define('IN_5TUFF_N3AR_2_ME_ADM1N', true);//so that people cant go directly to the files, they have to go through index.php
	
	$html = '';
	$title = 'Stuff Near To Me Admin';
	$css = array();
	$js = array();
			
	
	$css[] = 'http://stuffnearto.me/css/main.css';
	$css[] = '/css/admin.css';
	//$js[] = '/js/jquery-1.3.2.min.js';
	$js[] = 'http://stuffnearto.me/js/jquery-1.4.2.min.js';
	
	function ae_detect_ie(){
		if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)){
			return true;
		}else{
			return false;
		}
	}
	
	if(ae_detect_ie()){
	$css[] = 'http://stuffnearto.me/css/ie.css';
	}
	
	if($session->logged_in){
		if($session->isAdmin()){
		
			if($session->userinfo['userlevel'] >= 8){
				$loginEditDelete = true;
			}else{
				$loginEditDelete = false;
			}
				
			require_once('../classes/stuffClass.php');
		
			$stuff = new stuffDB();
				
			$dbObj = $stuff->init();
			
			if(!isset($_GET['task'])){$_GET['task'] = 'viewLocation';}
			
			switch($_GET['task']){
				case 'viewReviews':
					$string = 'viewReviews.php';
				break;
				case 'editReviews':
					$string = 'editReviews.php';
				break;
				case 'deleteReviews':
					$string = 'deleteReviews.php';
				break;
				case 'updateReviews':
					$string = 'updateReviews.php';
				break;	
				case 'viewMenus':
					$string = 'viewImages.php';
				break;		
				case 'editUser':
					$string = 'editUser.php';
				break;
				case 'editLocation':
					$string = 'editLocations.php';
				break;
				case 'deleteLocation':
					$string = 'deleteLocations.php';
				break;
				case 'updateLocation':
					$string = 'updateLocations.php';
				break;
				case 'viewCategoryFields':
					$string = 'viewCategoryFields.php';
				break;
				case 'editCategoryFields':
					$string = 'editCategoryFields.php';
				break;
				case 'updateCategoryFields':
					$string = 'updateCategoryFields.php';
				break;
				case 'viewPlace':
					$string = 'viewPlaces.php';
				break;
				case 'editPlace':
					$string = 'editPlace.php';
				break;
				case 'updatePlace':
					$string = 'updatePlace.php';
				break;
				case 'viewCategories':
					$string = 'viewCategories.php';
				break;
				case 'editCategories':
					$string = 'editCategories.php';
				break;
				case 'updateCategories':
					$string = 'updateCategories.php';
				break;
				case 'deleteCategories':
					$string = 'deleteCategories.php';
				break;
				case 'deletePlace':
					$string = 'deletePlace.php';
				break;
				case 'deleteCategoryFields':
					$string = 'deleteCategoryFields.php';
				break;
				case 'viewUsers':
					$string = 'userAdmin.php';
				break;
				case 'viewLocation':
					$string = 'viewLocations.php';
				break;
				case 'mobileIndex':
					$string = 'mobile_wurfl/main.php';
				break;
				case 'mobileCheck':
					$string = 'mobile_wurfl/check_wurfl.php';
				break;
				case 'mobileGeneratePatch':
					$string = 'mobile_wurfl/generatePatch.php';
				break;
				case 'mobileStats':
					$string = 'mobile_wurfl/stats.php';
				break;
				case 'mobileTestFallback':
					$string = 'mobile_wurfl/test_fallback.php';
				break;
				case 'mobileTestUsage':
					$string = 'mobile_wurfl/test_usage.php';
				break;
				case 'mobileUpdateDb':
					$string = 'mobile_wurfl/updatedb.php';
				break;
				case 'mobileWebservice':
					$string = 'mobile_wurfl/webservice.php';
				break;
				case 'mobileCacheCapabilities':
					$string = 'mobile_wurfl/cache_browser/show_capabilities.php';
				break;
				case 'mobileCacheClassic':
					$string = 'mobile_wurfl/cache_browser/browse_classic.php';
				break;
				case 'error':
					$string = 'error.php';
				break;			
				default:
					$string = $_REQUEST['task'].'.php';
			}
			//echo $_GET['task'].'<br />';
			//echo $string;
			
			//$js[] = 'http://maps.google.co.uk/maps?file=api&amp;v=2&amp;sensor=true&amp;key=key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBQm8-GFD7iUKcN0wzgs7EmXLPGwwBT2URCyZgQ79QFfnqFZOfUZvVm3kw&region=GB';
			$js[] = 'http://maps.google.co.uk/maps?file=api&amp;v=2&amp;sensor=true&amp;key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBQHOBgHb0dPNDpUHJTTFgsPwKrfVBQSClVYUSL81teciONwJi469CBAdA&region=GB';
			//$js[] = 'http://www.google.com/jsapi?key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBTt6S5Gtb7j9cF68dX4lN_B0fIWCRTUHkoXuR_7TkFyqYZlpDb927BsQg';
			$js[] = '/js/googleMaps.js';

			$header .= '<span class="logout">Hi '.($session->username).' <a href="/process.php">[Logout]</a></span><div class="clearFloat"></div>
				<div id="navContainer">
				<ul id="nav1">
					<li><a href="/view/location" title"View Products">Locations & Places</a></li>
					'.($loginEditDelete ? '<li><a href="/view/categories">Categories</a></li>
					<li><a href="/view/categoryFields">Category Fields</a></li>
					<li><a href="/users">Users</a></li>' : '').'
					<li><a href="/view/reviews">Reviews</a></li>
					<li><a href="/view/images">Place Images</a></li>
					<li><a href="/view/menus">Menus</a></li>
					<li><a href="/view/problems">Problems</a></li>
					'.($loginEditDelete ? '<li><a href="/mobile/main">Mobile Data</a></li>' : '').'
				</ul></div>';			
			
			if(isset($string) && $string != '' && file_exists($string)){
				include($string);
			}else{
				$html .= 'ERROR, '.$string.' doesnt exist';
			}
			
			$stuff->finish($dbObj);
		}else{
			$session->logout();
			$html .= 'Sorry, but you are not an admin. Are you sure you didnt want to go to <a href="http://admin.stuffnearto.me">Stuff Near To Me</a><br />
			You will be redirected back to the login screen in 5 seconds';
			$html .= '<script>
				$(function(){
					setTimeout(\'window.location = "http://admin.stuffnearto.me"\', 5000);
				})
			</script>';
		}
	}else{
		if($form->num_errors > 0){
   			$html .= ($form->num_errors).' error(s) found';
		}
		
		$html .= '<div id="adminLogin"><div class="loginHeader">Login to Stuff Near To Me Administration</div><form action="/process.php" method="POST"><fieldset>';
		$html .= '<label for="">Username:</label><input id="i_user" type="text" name="user" maxlength="30" value="'.($form->value("user")).'"><span>'.($form->error("user")).'</span><br />';
		$html .= '<label>Password:</label><input id="i_pass" type="password" name="pass" maxlength="30" value="'.($form->value("pass")).'"><span>'.($form->error("pass")).'</span><br />';
		$html .= '<fieldset><div>Remember me next time</div><label class="rememberLabel" for="i_remember"><input type="checkbox" id="i_remember" name="remember" '.(($form->value("remember") != "") ? 'checked="checked"' : '').' />Yes</label></fieldset>';
		$html .= '<input type="hidden" name="sublogin" value="1" />';
		$html .= '<label>&nbsp;</label><button type="submit">Login</button>';
		$html .= '</fieldset></form><br />';
		$html .= '<div class="forgotPass"><a href="http://www.stuffnearto.me/forgotPassword.html">[Forgot Password?]</a></div></div>';
	}

?>

<html>
	<head>
		<title><?php echo $title;?></title>
		<?php
		//css and js foreach
		foreach($css as $k){
			echo '<link href="'.$k.'" type="text/css" rel="stylesheet"/>';
		}
		foreach($js as $v){
			echo '<script src="'.$v.'" type="text/javascript"></script>';
		}
		?>
	</head>
	<body>
		<div class="pageContainer">
			<div id="header">
				<div id="widthContainer">
				<a id="logoContainer" href="/" title="Home"><h1><span>Stuff Near To.Me Admin</span></h1></a>
				<?php
					echo $header;
				?>
				</div>
				
			</div>
			<div class="clearFloat"></div>
			<?php if(in_array($_GET['task'], array('viewReviews', 'editReviews', 'deleteReviews', 'updateReviews'))){
				echo '<div id="navContainer2">
					<ul id="nav2">
						<li><a href="/view/reviews">Un-approved</a></li>
						<li><a href="/view/reviews?approved=1">Approved</a></li>
						<li><a href="/view/reviews?archived=1">Archived</a></li>
					</ul>
				</div><div class="clearFloat"></div>';
			}elseif(in_array($_GET['task'], array('viewProblems', 'editProblems', 'deleteProblems', 'updateProblems'))){
				echo '<div id="navContainer2">
					<ul id="nav2">
						<li><a href="/view/problems">Un-actioned</a></li>
						<li><a href="/view/problems?actioned=1">Actioned</a></li>
					</ul>
				</div><div class="clearFloat"></div>';
			}elseif(in_array($_GET['task'], array('viewMenus', 'editMenus', 'deleteMenus', 'updateMenus'))){
				echo '<div id="navContainer2">
					<ul id="nav2">
						<li><a href="/view/menus">Un-approved</a></li>
						<li><a href="/view/menus?approved=1">Approved</a></li>
						<li><a href="/view/menus?archived=1">Archived</a></li>
					</ul>
				</div><div class="clearFloat"></div>';
			}
			elseif(in_array($_GET['task'], array('viewImages', 'editImages', 'deleteImages', 'updateImages'))){
				echo '<div id="navContainer2">
					<ul id="nav2">
						<li><a href="/view/images">Un-approved</a></li>
						<li><a href="/view/images?approved=1">Approved</a></li>
						<li><a href="/view/images?archived=1">Archived</a></li>
					</ul>
				</div><div class="clearFloat"></div>';
			}
			?>
			
			<?php
			
			?>
			<div id="contentContainer">
				<?php echo $html; ?>
			</div>
			<div id="footer">
			</div>
		</div>
		<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-15593420-1");
pageTracker._setDomainName(".stuffnearto.me");
pageTracker._trackPageview();
} catch(err) {}</script>
	</body>
</html>