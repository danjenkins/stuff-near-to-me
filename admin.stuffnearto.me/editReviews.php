<?php
	
	global $dbObj;
	
	if(!defined('IN_5TUFF_N3AR_2_ME_ADM1N')){
		die('Page cannot be displayed');
	}else{
	
		if($_REQUEST['reviewId']){
			
			$sql = 'SELECT R.*, P.placeName FROM reviews R LEFT JOIN placeInformation P ON R.placeId = P.placeId WHERE reviewId = '.$_REQUEST['reviewId'];
			
			$result = $stuff->query($sql, true, $dbObj);
			
			$html .= '<script src="http://stuffnearto.me/js/jquery.MetaData.js" type="text/javascript" language="javascript"></script>
			<script src="http://stuffnearto.me/js/jquery.rating.js" type="text/javascript" language="javascript"></script>
			<link href="http://stuffnearto.me/css/jquery.rating.css" type="text/css" rel="stylesheet"/>';
			
			$html .= '<h3>'.$result['placeName'].' - Review on '.date( 'd-m-y' , strtotime($result['dateAdded'])).'</h3>';
			
			$html .='<form action="/update/reviews/'.$result['reviewId'].'" method="get"><fieldset>';
			$i = 1;
			$html .= '<label>Rating</label>';
			while ($i <= 10) {
    			$html .= '<input class="star" type="radio" name="rating" value="'.$i.'" '.($result['rating'] == $i ? 'checked="checked"' : '').'/>';
    			$i++;
			}

			$html .= '<br /><div><label for="comment">Comment</label><textarea name="comment" id="comment">'.$result['comment'].'</textarea></div>';
			$html .= '<fieldset><div>Archived</div><label><input type="radio" name="archived" value="0" '.($result['archived'] == '0' ? 'checked="checked"' : '').'/>No</label><label><input type="radio" name="archived" value="1" '.($result['archived'] == '1' ? 'checked="checked"' : '').'/>Yes</label><br /></fieldset>';
			$html .= '<fieldset><div>Approved</div><label><input type="radio" name="approved" value="0" '.($result['approved'] == '0' ? 'checked="checked"' : '').'/>No</label><label><input type="radio" name="approved" value="1" '.($result['approved'] == '1' ? 'checked="checked"' : '').'/>Yes</label><br /></fieldset>';
			$html .= '<label>&nbsp;</label><button>Submit</button>';
			
			$html .= '</fieldset></form>';
			
			
		}else{
			$html .= 'no review id passed';
		}
	}


?>