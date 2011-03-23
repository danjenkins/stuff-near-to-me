<?php
/*
 * Tera_WURFL - PHP MySQL driven WURFL
 * 
 * Tera-WURFL was written by Steve Kamerman, and is based on the
 * Java WURFL Evolution package by Luca Passani and WURFL PHP Tools by Andrea Trassati.
 * This version uses a MySQL database to store the entire WURFL file, multiple patch
 * files, and a persistent caching mechanism to provide extreme performance increases.
 * 
 * @package TeraWurfl
 * @author Steve Kamerman, stevekamerman AT gmail.com
 * @version Stable 2.1.0 $Date: 2009/02/10 16:01:47
 * @license http://www.mozilla.org/MPL/ MPL Vesion 1.1
 * $Id: check_wurfl.php,v 1.3 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: check_wurfl.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */
if (!isset($_GET['force_ua'])) {
	$_GET['force_ua'] = 'SonyEricssonK700i/R2AC SEMC-Browser/4.0.2 Profile/MIDP-2.0 Configuration/CLDC-1.1';
}

$html .= '<form action="/mobile/checkWurfl" method="GET">
Mobile device user agent:<br />
<input type="text" name="force_ua" size="100" value="'.$_GET['force_ua'].'">
<button>Submit</button>
</form>
Try some of these:
<ul>
  <li><pre>SonyEricssonK700i/R2AC SEMC-Browser/4.0.2 Profile/MIDP-2.0 Configuration/CLDC-1.1</pre></li>
  <li><pre>MOT-T720/S_G_05.30.0CR MIB/2.0 Profile/MIDP-1.0 Configuration/CLDC-1.0</pre></li>
  <li><pre>SAGEM-myX5-2/1.0 Profile/MIDP-2.0 Configuration/CLDC-1.0 UP.Browser/6.2.2.6.d.2 (GUI) MMP/1.0</pre></li>
  <li><pre>NokiaN90-1/3.0541.5.2 Series60/2.8 Profile/MIDP-2.0 Configuration/CLDC-1.1</pre></li>
</ul>
<hr size="1" />';

set_time_limit(600);

list($usec, $sec) = explode(" ", microtime());
$start = ((float)$usec + (float)$sec); 

// Include the Tera-WURFL file
require_once("/home/jenkins2/public_html/stuffnearto.me/classes/mobile/TeraWurfl.php");

list($usec, $sec) = explode(" ", microtime());
$load_class = ((float)$usec + (float)$sec); 

// instantiate the Tera-WURFL object
$wurflObj = new TeraWurfl();

list($usec, $sec) = explode(" ", microtime());
$init_class = ((float)$usec + (float)$sec); 

// Get the capabilities from the object
$matched = $wurflObj->GetDeviceCapabilitiesFromAgent($_GET['force_ua']);

list($usec, $sec) = explode(" ", microtime());
$end = ((float)$usec + (float)$sec); 

$html .=  "Time to load TeraWurfl.php:".($load_class-$start)."<br>\n";
$html .=  "Time to initialize class:".($init_class-$load_class)."<br>\n";
$html .=  "Time to find the user agent:".($end-$init_class)."<br>\n";
$html .=  "Total:".($end-$start)."<br>\n";
$cached = ($wurflObj->foundInCache)? "<strong>(Found in cache)</strong>": "";
$html .=  "<br>Total Queries: ".$wurflObj->db->numQueries." $cached<br>\n";

$text = ($matched)?"a conlusive":"an <font color=\"#990000\">inconclusive</font>";
$html .=  "<h3>Test resulted in $text match</h3>";
$html .=  "<pre>";
//$html .= var_export($wurflObj->capabilities);
$html .=  "</pre>";

$html .= '
<form action="/mobile/checkWurfl" method="GET">
Mobile device user agent:<br />
<input type="text" name="force_ua" size="100" value="'.$_GET['force_ua'].'">
<button>Submit</button>
</form>';

?>
