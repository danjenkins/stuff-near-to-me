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
 * $Id: test_usage.php,v 1.2 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: test_usage.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */
// Include the Tera-WURFL file
require_once("/home/jenkins2/public_html/stuffnearto.me/classes/mobile/TeraWurfl.php");
// Instantiate the Tera-WURFL object
$wurflObj = new TeraWurfl();
// Get the capabilities from the object
$matched = $wurflObj->GetDeviceCapabilitiesFromAgent(); //optionally pass the UA and HTTP_ACCEPT here
// Show whether there was a conclusive match
if($matched){echo "Match found";}else{echo "Match NOT found";}
// Print the capabilities array
echo "<pre>".htmlspecialchars(var_export($wurflObj->capabilities,true))."</pre>";
?>