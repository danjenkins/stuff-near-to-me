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
 * $Id: test_fallback.php,v 1.3 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: test_fallback.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */
require_once("/home/jenkins2/public_html/stuffnearto.me/classes/mobile/TeraWurfl.php");

error_reporting(E_ALL);

$base = new TeraWurfl();
$device = $base->db->getDeviceFromID($_GET['id']);
echo "<h3>{$device['id']}</h3><pre>".var_export($device,true)."</pre>";
while(isset($device['fall_back']) && strlen($device['fall_back'])>1 && $device['fall_back']!='root'){
	$device = $base->db->getDeviceFromID($device['fall_back']);
	echo "<h3>{$device['id']}</h3><pre>".var_export($device,true)."</pre>";
}
?>