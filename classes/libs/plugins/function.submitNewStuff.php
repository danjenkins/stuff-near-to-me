<?php

function smarty_function_submitNewStuff($params, &$smarty){

	global $stuff;

	if(!$params['type']){
		$html = '<p>No type entered, no form output</p>';
	}else{
		switch($params['type']){
			case 'place':
			$publickey = '6LfrwAoAAAAAAA1DcJULepomncw0MexWZfz8NYn5'; // you got this from the signup page
				if($_REQUEST['redoCaptcha']){
					//ie theres been an error!
					$html .= '<span class="error">Sorry there has been an error, the error was ';

					switch($_GET['captchaErr']){
						case 'invalid-site-public-key':
							$html .= 'We weren\'t able to verify the public key, we\'ve been notified, sorry for the inconvenience';
							break;
						case 'invalid-request-cookie':
							$html .= 'The challenge parameter of the verify script was incorrect';
							break;
						case 'incorrect-captcha-sol':
							$html .= 'The CAPTCHA solution was incorrect';
						break;
						case 'verify-params-incorrect':
							$html .= 'The parameters to /verify were incorrect, make sure you are passing all the required parameters';
						break;
						case 'invalid-referrer':
							$html .= 'reCAPTCHA API keys are tied to a specific domain name for security reasons, we\'ve been notified of the error';
						break;
						case 'recaptcha-not-reachable':
							$html .= 'Unable to contact the reCAPTCHA verify server';
						break;
						case 'unknown':	
						default:
							$html .= 'Unknown error';
						break;
					}
					
					$html .= ', please fill in the field below</span>';
					$html .= '<form action="'.$params['action'].'" method="post">';
					
					$hidden = $_GET;
					unset($hidden['redoCaptcha'], $hidden['captchaErr']);
					foreach($hidden as $k=>$v){
						$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
					}				
					require_once(site_path.'/classes/recaptchalib.php');
					$html .= recaptcha_get_html($publickey);
					$html .= '<label>&nbsp;</label><input type="submit" value="Submit the stuff, again" />';
					$html .= '</form>';
				}else{
					$html = '<script src="/js/submitStuff.js"></script>';
					$html .= '<form method="post" action="'.$params['action'].'"><fieldset class="formContainer">';
					
					if(!$stuff){
						require_once(site_path.'/classes/stuffClass.php');
						$stuff = new stuffDB();
					}
					$sql = 'SELECT C.*, CA.categoryName, T.columnTypeName as fieldType FROM cmColumns C  LEFT JOIN categories CA ON C.relatedCategory = CA.categoryId LEFT JOIN columnTypes T ON C.fieldType = T.columnTypeId ORDER BY C.relatedCategory, C.fieldId ASC';
					$result = $stuff->query($sql, true);
					
					//$stuff->debug($result, true);
					
					foreach($result as $k=>$v){
						//don't show particular inputs!
						if(!in_array($v['fieldName'], array('latitude', 'longitude', 'meta', 'approved', 'archived'))){
							$v['labelName'] = str_replace($v['categoryName'], '', $v['fieldName']);
							$label = ucwords($stuff->splitCamelCase($v['labelName']));
							if($v['relatedCategory'] == 0){
								$v['categoryName'] = 'none';
							}
							if($v['relatedCategory'] != 0 && $v['relatedCategory'] != '2'){
								$display = 'display:none;';
							}
							switch($v['fieldType']){
								//need to do a special one for select drop downs
								case 'boolean':
									$html .= '<fieldset style="'.$display.'" class="'.strtolower($v['categoryName']).' stuffContainer">';
									$html .= '<div>'.$label.'</div>';
									$html .= '<label><input type="radio" value="0" name="'.$v['fieldName'].'"/>No</label><label><input type="radio" value="1" name="'.$v['fieldName'].'"/>Yes</label></fieldset>';
								break;
								default:
									$html .= '<div style="'.$display.'" class="'.strtolower($v['categoryName']).' stuffContainer"><label for="i_'.$v['fieldName'].'">'.(str_ireplace('id', '',$label)).'</label>';				
									if($v['fieldName'] == 'locationId' || $v['fieldName'] == 'categoryId' || strpos($v['fieldDesc'], '[') !== false){
										if($v['fieldDesc']){
											$useFieldDesc = true;
											preg_match('/\[(\S+)\]/', $v['fieldDesc'], $matches);
											$result1 = explode(',',$matches[1]);
										}elseif($v['fieldName'] == 'locationId'){
											$result1 = $stuff->getLocations();
										}elseif($v['fieldName'] == 'categoryId'){
											$result1 = $stuff->getCategories();
										}
										asort($result);
										$html .= '<select name="'.$v['fieldName'].'" class="'.$v['categoryName'].'">';
										foreach($result1 as $h=>$i){
											$html .= '<option value="'.($useFieldDesc ? $i : $h).'">'.ucwords($stuff->splitCamelCase($i)).'</option>';
										}
										$html .= '</select>';
										
									}else{
										$html .= '<input type="text" class="'.$v['categoryName'].'" id="i_'.$v['fieldName'].'" name="'.$v['fieldName'].'"  />';
										
									}
									$html .= '</div>';
								break;
							}
						}
					}
					//make a select for placeType
					
					//then output all the fields available.
					
					//allow them yo upload some pictures
					
					//add classes to all of the inputs that are specific. Add a js file which hides and shows and disables and undisables inputs.
					
					if(!$smarty->get_template_vars('loggedIn')){
						//$html .= 'You are not logged in so going to have to fill out captcha';
						require_once(site_path.'/classes/recaptchalib.php');
						$html .= recaptcha_get_html($publickey);
	
					}
					
					$html .= '</fieldset>
					<label>&nbsp;</label><button>Submit the stuff</button></form>';
				}
			break;
		}
	}
	
	
	return $html;
}

?>