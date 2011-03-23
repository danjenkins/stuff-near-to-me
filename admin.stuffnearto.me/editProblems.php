<?php
	
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_REQUEST['problemId']){
			
			$sql = 'SELECT P.*, PI.placeName, R.comment as revComment, R.rating as revRating, PLACE.placeName as revPlaceName FROM problems P LEFT JOIN placeInformation PI ON P.placeId = PI.placeId LEFT JOIN reviews R ON R.reviewId = P.reviewId  LEFT JOIN placeInformation PLACE ON R.placeId = PLACE.placeId WHERE P.problemId = '.$_REQUEST['problemId'];
			
			//$stuff->debug($sql,true);
			
			$result = $stuff->query($sql, true, $dbObj);
			
			$html .= '<h3>Problem added on '.date( 'd-m-y' , strtotime($result['dateAdded'])).'</h3>';
			
			if($result['reviewId']){
				$html .= '<h3>Problem with review "'.$result['revComment'].'" for place "'.$result['revPlaceName'].'"</h3>';
			}else{
				$html .= '<h3>Problem with "'.$result['placeName'].'" place information</h3>';
			}
			
			$html .='<form action="/update/problems/'.$result['problemId'].'" method="get">';

			$html .= '<div>Comment:'.$result['comment'].'</div>';
			
			$html .= '<label for="note">Note</label><textarea name="note" id="note">'.$result['note'].'</textarea><br />';
			$html .= '<fieldset><label>Actioned</label><label><input type="checkbox" name="actioned" '.($result['actioned'] == '1' ? 'checked="checked"' : '').'/>Yes</label><br /></fieldset>';
			
			$html .= '<fieldset><label>Send person email?</label><label><input type="checkbox" name="sendEmail" />Yes</label><br /></fieldset>';
			$html .= '<input type="hidden" name="emailAddress" value="'.$result['emailAddress'].'" />';
			$html .= '<label for="emailContent">Email content</label><textarea name="emailContent" id="emailContent"></textarea><br />';
			
			
			
			$html .= '<label>&nbsp;</label><button>Submit</button>';
			
			
			
			$html .= '</form>';
			
			
		}else{
			$html .= 'no review id passed';
		}
	}


?>