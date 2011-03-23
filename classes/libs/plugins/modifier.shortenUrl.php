<?php

function smarty_modifier_shortenUrl($string, $chars)
{
    if(strlen($string) > $chars){
    	$result = substr($string, 0 ,$chars).'...';
    }else{
    	$result = $string;
    }
    return $result;
}


?>