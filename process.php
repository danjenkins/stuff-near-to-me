<?
require_once('classes/stuffClass.php');
$stuff = new stuffDB();
require_once('classes/users/session.php');
error_reporting(0);


switch($_REQUEST['task']){
case 'userFavs':
	
	if($session->logged_in  && $_REQUEST['placeId']){
		//go get the favourites for this user from the session
		$sql = 'SELECT favouritePlaces FROM users WHERE username = "'.$session->username.'"';
		$result = $stuff->query($sql, true);
		$favs = unserialize($result['favouritePlaces']);
		//add this place or remove it
		//$stuff->debug($favs, true);
		if($_REQUEST['favourite'] == '1'){
			$favs[$_REQUEST['placeId']] = $_REQUEST['favourite'];
		}else{
			unset($favs[$_REQUEST['placeId']]);
		}	
		//serialize the array
		//$stuff->debug($favs, true);
		$serialized = serialize($favs);
		//$stuff->debug($serialized, true);
		$session->userFavouritePlaces = $_SESSION['userFavouritePlaces'] = $favs;
		$sql = 'UPDATE users SET favouritePlaces = "'.mysql_real_escape_string($serialized).'" WHERE username = "'.$session->username.'"';
		//$data['favouritePlaces'] = $serialized;
		//$result = $stuff->update('users', 'username', $session->username, $data);
		$result = $stuff->alter($sql);
		if($result){
			$arr['status'] = 'success';
		}else{
			$arr['status'] = 'error';
		}
	}else{
		$arr['status'] = 'error';
	}
	echo json_encode($arr);
	//update table and the session
break;
case 'report':
	$arr = array();
	if($_REQUEST['type'] && $_REQUEST['id'] && $_REQUEST['comment']){
		
		//now add it to the db.
		if($_REQUEST['type'] == 'place'){
			$type = 'placeId';
		}elseif($_REQUEST['type'] == 'review'){
			$type = 'reviewId';
		}elseif($_REQUEST['type'] == 'general'){
			$type = 'general';
		}
		
		$sql = 'INSERT INTO problems ('.$type.', dateAdded, addedBy, emailAddress, comment) VALUES ("'.$_REQUEST['id'].'", NOW() , "'.(mysql_real_escape_string($_REQUEST['name'])).'", "'.(mysql_real_escape_string($_REQUEST['email'])).'", "'.(mysql_real_escape_string($_REQUEST['comment'])).'")';
		
		$result = $stuff->alter($sql);
		$arr['sql'] = $sql;
		if($result){
			$arr['status'] = 'success';
		}else{
			$arr['status'] = 'error';
		}
	}else {
		$arr['status'] = 'error';
	}
	echo json_encode($arr);
break;

case 'submitReview':
	$arr = array();
	if($_REQUEST['type'] && $_REQUEST['id'] && $_REQUEST['comment'] && $_REQUEST['rating']){
		
		
		//now add it to the db.
			
		$sql = 'INSERT INTO reviews (placeId, dateAdded, addedBy, rating, comment) VALUES ("'.$_REQUEST['id'].'", NOW() , "'.($session->logged_in ? $session->username : mysql_real_escape_string($_REQUEST['user'])).'", "'.(mysql_real_escape_string($_REQUEST['rating'])).'", "'.(mysql_real_escape_string($_REQUEST['comment'])).'")';
		
		$result = $stuff->alter($sql);
		
		if($result){
			$arr['status'] = 'success';
			//RUN A MAIL event for the email address for the admin for that place and then also to all super admins
		}else{
			$arr['status'] = 'error';
		}
	}else {
		$arr['status'] = 'error';
	}
	echo json_encode($arr);
break;
case 'newPlace':
	
	$query = $_POST;
	unset($query['recaptcha_challenge_field'], $query['recaptcha_response_field'], $query['page']);
	if(!$session->logged_in){
	
		require_once('classes/recaptchalib.php');
		$privatekey = '6LfrwAoAAAAAAJlSEZhVxNzOgBudB_RLuRCDOc-M';
		$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
		if (!$resp->is_valid) {
			//redirect back with all the data in hidden fields, just show them the captcha again
			
			$query['redoCaptcha'] = 'true';
			$query['captchaErr'] = $resp->error;
			$string = http_build_query($query);
			header('Location: /submitStuff.html?'.$string);
		}
	}
	$query['approved'] = 0;
	$query['archived'] = 0;
	//amendedBy
	//dateAmended
	$i = 0;
	foreach($query as $k=>$v){
		$i++;
		if($i != 1){
			$columns .= ', ';
			$values .= ', ';
		}
		$columns .= $k;
		$values .= '"'.mysql_real_escape_string($v).'"';
	}
	//now add to columns and values, dateAdded = 'NOW()';
	
	if($session->logged_in){
		$by = $session->username;
	}else{
		$by = 'webGuest';
	}
	$sql = 'INSERT INTO placeInformation ('.$columns.', amendedBy, dateAmended) VALUES ('.$values.', "'.$by.'" ,NOW())';
	//die($sql);
	$result = $stuff->alter($sql);
	if(!$result){
		$status = '&error=insertFailed';
	}else{
		$status = '&success=insertSuccessful';
		//RUN A MAIL event for the email address for the admin for that place and then also to all super admins
	}
	header('Location: /thanks.html?task=newPlace&placeId='.mysql_insert_id().$status);
	
	//now run an sql insert whilst setting approved to 0 so that it cant appear on site.
	//if its a user run a secondary sql on another table saying that they have given us info

break;
case 'location':	
	header('Location: /'.strtolower($_GET['location']));
break;
case 'newImage':
	
	//figure out the file structure.
	
	//we can have a p , c or l
	mkdir("/home/jenkins2/public_html/stuffnearto.me/images/".$_REQUEST['type']."/" . $_REQUEST['id'], 0777, true);//make the directory, if it exists already it ignores it!
	
	$uploaddir = '/images/'.$_REQUEST['type'].'/'. $_REQUEST['id'];
	
	$path = $uploaddir .'/'. basename(substr($_FILES['uploadfile']['name'],0,strrpos($_FILES['uploadfile']['name'], ".")).'_'.strtotime('now').'.'.(end(explode('.', $_FILES['uploadfile']['name']))));
	$uploadfile = '/home/jenkins2/public_html/stuffnearto.me'.$path;
	$arr = array();
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile) && $_REQUEST['type'] && $_REQUEST['id']) {
		$arr['status'] = 'success';
		
		//now add it to the db.
		
		switch($_REQUEST['type']){
			case 'place':
				$column = 'placeId';
			break;
			case 'category':
				$column = 'categoryId';
			break;
			case 'location':
				$column = 'locatonId';
			break;
		}
		
		$sql = 'INSERT INTO images 
			('.$column.', imageLocation, approved, archived, addedBy, dateAdded, menu) 
			VALUES 
			("'.$_REQUEST['id'].'", "'.$path.'", "0", "0", "'.$_REQUEST['addedBy'].'", NOW(), "0")';
		
		$result = $stuff->alter($sql);
		
		$id = mysql_insert_id();
		
		$arr['imageId'] = $id;
		$arr['imageLocation'] = $path;
		//RUN A MAIL event for the email address for the admin for that place and then also to all super admins
		
		
	}else {
		// WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
		// Otherwise onSubmit event will not be fired
		$arr['status'] = 'error';
	}
	
	echo json_encode($arr);
break;
case 'menuUpload':
	
	//figure out the file structure.
	
	//we can have a p , c or l
	mkdir("/home/jenkins2/public_html/stuffnearto.me/images/menu/" . $_REQUEST['placeId'], 0777, true);//make the directory, if it exists already it ignores it!
	
	$uploaddir = '/images/menu/'. $_REQUEST['placeId'];
	
	$path = $uploaddir .'/'. basename(substr($_FILES['uploadfile']['name'],0,strrpos($_FILES['uploadfile']['name'], ".")).'_'.strtotime('now').'.'.(end(explode('.', $_FILES['uploadfile']['name']))));
	$uploadfile = '/home/jenkins2/public_html/stuffnearto.me'.$path;
	$arr = array();
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile) && $_REQUEST['placeId']) {
		$arr['status'] = 'success';
		
		//now add it to the db.
		
		$sql = 'INSERT INTO images 
			(placeId, imageLocation, approved, archived, addedBy, dateAdded, menu) 
			VALUES 
			("'.$_REQUEST['placeId'].'", "'.$path.'", "0", "0", "'.$_REQUEST['addedBy'].'", NOW(), "1")';
		
		$result = $stuff->alter($sql);
		
		$id = mysql_insert_id();
		
		$arr['imageId'] = $id;
		$arr['imageLocation'] = $path;
		//RUN A MAIL event for the email address for the admin for that place and then also to all super admins
		
		
	}else {
		// WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
		// Otherwise onSubmit event will not be fired
		$arr['status'] = 'error';
	}
	
	echo json_encode($arr);
break;
case 'menuUrl':

	$arr = array();
	if ($_REQUEST['placeId'] && $_REQUEST['url']){
		$arr['status'] = 'success';
		
		//now add it to the db.
		
		$sql = 'INSERT INTO externalMenus (placeId, menuUrl, addedBy, dateAdded) VALUES ("'.$_REQUEST['placeId'].'","'. (mysql_real_escape_string($_REQUEST['url'])).'", "'.($session->logged_in ? $session->username : 'webGuest').'", NOW())';
		
		$result = $stuff->alter($sql);		
		//$arr['sql'] = $sql;
		//RUN A MAIL event for the email address for the admin for that place and then also to all super admins
	}else {
		$arr['status'] = 'error';
	}
	
	echo json_encode($arr);
break;
case 'imageInfo':
	$sql = 'UPDATE images SET imageTitle = "'.$_REQUEST['imageTitle'].'",imageAlternateText = "'.$_REQUEST['imageAlternateText'].'" WHERE imageId = "'.$_REQUEST['imageId'].'"';
	
	$result = $stuff->alter($sql);
	
	if($_REQUEST['tags']){
		//need to insert a row for each tag thats come out of a comma seperated list
		$tags = explode(',', $_REQUEST['tags']);
		$str = '';
		$i = 0;
		foreach($tags as $k){
			$i++;
			if($i != 1){
				$str .= ',';
			}
			$str .= '("'.$_REQUEST['imageId'].'", "'.trim($k).'")';
		}
		
		$sql2 = "INSERT INTO imageTags (imageId, tagText) VALUES ".$str;
		
		$result2 = $stuff->alter($sql2);

	}
	
	if($result){
		$arr['status'] = 'success';
	}else{
		$arr['status'] = 'error';
	}	
	echo json_encode($arr);
break;
case 'user':
default:

/**
 * Process.php
 * 
 * The Process class is meant to simplify the task of processing
 * user submitted forms, redirecting the user to the correct
 * pages if errors are found, or if form is successful, either
 * way. Also handles the logout procedure.
 *
 */

class Process {
	/* Class constructor */
	function Process(){
		global $session;
		/* User submitted login form */
		if(isset($_POST['sublogin'])){
			$this->procLogin();
		}else if(isset($_POST['subjoin'])){
			/* User submitted registration form */
			$this->procRegister();
		}else if(isset($_POST['subforgot'])){
			/* User submitted forgot password form */
			$this->procForgotPass();
		}else if(isset($_POST['subedit'])){
			/* User submitted edit account form */
			$this->procEditAccount();
		}
		else if($session->logged_in){
			/**
			* The only other reason user should be directed here
			* is if he wants to logout, which means user is
			* logged in currently.
			*/
			$this->procLogout();
		}else{
			/**
			* Should not get here, which means user is viewing this page
			* by mistake and therefore is redirected.
			*/
			if($_REQUEST['redirectTo']){
				header("Location: ".$_REQUEST['redirectTo']);
			}elseif($session->referrer){
				header("Location: ".$session->referrer);
			}else{
				header('Location: /');//this needs to change! to referrer maybe
			}        
		}
	}

   /**
    * procLogin - Processes the user submitted login form, if errors
    * are found, the user is redirected to correct the information,
    * if not, the user is effectively logged in to the system.
    */
   function procLogin(){
      global $session, $form;
      /* Login attempt */
      $retval = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));
      /* Login successful */
      if($retval){
		if($session->referrer){
			header("Location: ".$session->referrer);
		}else{
			header('Location: /');//this needs to change! to referrer maybe
		}    
      }
      /* Login failed */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
        if($_REQUEST['redirectTo']){
				header("Location: ".$_REQUEST['redirectTo']);
			}elseif($session->referrer){
				header("Location: ".$session->referrer);
			}else{
				header('Location: /');//this needs to change! to referrer maybe
			}    
      }
   }
   
   /**
    * procLogout - Simply attempts to log the user out of the system
    * given that there is no logout form to process.
    */
   function procLogout(){
      global $session;
      $retval = $session->logout();
      if($_REQUEST['redirectTo']){
				header("Location: ".$_REQUEST['redirectTo']);
			}elseif($session->referrer){
				header("Location: ".$session->referrer);
			}else{
				header('Location: /');//this needs to change! to referrer maybe
			}    
   }
   
   /**
    * procRegister - Processes the user submitted registration form,
    * if errors are found, the user is redirected to correct the
    * information, if not, the user is effectively registered with
    * the system and an email is (optionally) sent to the newly
    * created user.
    */
   function procRegister(){
      global $session, $form;
      /* Convert username to all lowercase (by option) */
      if(ALL_LOWERCASE){
         $_POST['user'] = strtolower($_POST['user']);
      }
      /* Registration attempt */
      $retval = $session->register($_POST['user'], $_POST['pass'], $_POST['email'], $_POST['pass2']);
      
    //  var_dump($retval);
     // exit();
      
      /* Registration Successful */
      if($retval == 0){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = true;
    	if($_REQUEST['redirectTo']){
			header("Location: ".$_REQUEST['redirectTo']);
		}elseif($session->referrer){
			header("Location: ".$session->referrer);
		}else{
			header('Location: /');//this needs to change! to referrer maybe
		}    
      }
      /* Error found with form */
      else if($retval == 1){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         if($_REQUEST['redirectTo']){
				header("Location: ".$_REQUEST['redirectTo']);
			}elseif($session->referrer){
				header("Location: ".$session->referrer);
			}else{
				header('Location: /');//this needs to change! to referrer maybe
			}    
      }
      /* Registration attempt failed */
      else if($retval == 2){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = false;
         if($_REQUEST['redirectTo']){
				header("Location: ".$_REQUEST['redirectTo']);
			}elseif($session->referrer){
				header("Location: ".$session->referrer);
			}else{
				header('Location: /');//this needs to change! to referrer maybe
			}    
      }
   }
   
   /**
    * procForgotPass - Validates the given username then if
    * everything is fine, a new password is generated and
    * emailed to the address the user gave on sign up.
    */
   function procForgotPass(){
      global $database, $session, $mailer, $form;
      /* Username error checking */
      $subuser = $_POST['user'];
      $field = "user";  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, "Username not entered");
      }
      else{
         /* Make sure username is in database */
         $subuser = stripslashes($subuser);
         if(strlen($subuser) < 5 || strlen($subuser) > 30 ||
            !eregi("^([0-9a-z])+$", $subuser) ||
            (!$database->usernameTaken($subuser))){
            $form->setError($field, "Username does not exist");
         }
      }
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
      }
      /* Generate new password and email it to user */
      else{
         /* Generate new password */
         $newpass = $session->generateRandStr(8);
         
         /* Get email of user */
         $usrinf = $database->getUserInfo($subuser);
         $email  = $usrinf['email'];
         
         /* Attempt to send the email with new password */
         if($mailer->sendNewPass($subuser,$email,$newpass)){
            /* Email sent, update database */
            $database->updateUserField($subuser, "password", md5($newpass));
            $_SESSION['forgotpass'] = true;
         }
         /* Email failure, do not change password */
         else{
            $_SESSION['forgotpass'] = false;
         }
      }
      
		if($_REQUEST['redirectTo']){
			header("Location: ".$_REQUEST['redirectTo']);
		}elseif($session->referrer){
			header("Location: ".$session->referrer);
		}else{
			header('Location: /');//this needs to change! to referrer maybe
		}    
   }
   
   /**
    * procEditAccount - Attempts to edit the user's account
    * information, including the password, which must be verified
    * before a change is made.
    */
   function procEditAccount(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['email'], $_POST['location']);
      /* Account edit successful */
      if($retval){
         $_SESSION['useredit'] = true;
         if($_REQUEST['redirectTo']){
				header("Location: ".$_REQUEST['redirectTo'].'?success=true');
			}elseif($session->referrer){
				header("Location: ".$session->referrer);
			}else{
				header('Location: /');//this needs to change! to referrer maybe
			}    
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         if($_REQUEST['redirectTo']){
				header("Location: ".$_REQUEST['redirectTo']);
			}elseif($session->referrer){
				header("Location: ".$session->referrer);
			}else{
				header('Location: /');//this needs to change! to referrer maybe
			}    
      }
   }
};

/* Initialize process */
$process = new Process;

break;
}

?>
