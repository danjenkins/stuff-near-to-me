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
require_once("/home/jenkins2/public_html/stuffnearto.me/classes/mobile/TeraWurfl.php");

try {
	$tw = new TeraWurfl();
}catch(Exception $e){
	
}

$db = $tw->db;
$wurflfile = $tw->rootdir.TeraWurflConfig::$DATADIR.TeraWurflConfig::$WURFL_FILE;

$missing_tables = false;

if($db->connected === true){
	$required_tables = array(TeraWurflConfig::$CACHE,TeraWurflConfig::$INDEX,TeraWurflConfig::$MERGE);
	$tables = $db->getTableList();
// See what tables are in the DB
//die(var_export($tables,true));
	foreach($required_tables as $req_table){
		if(!in_array($req_table,$tables)){
			$missing_tables = true;
		}
	}
}


	$html .= '<h1>Mobile</h1>';

	if(isset($_GET['msg']) && $_GET['severity']){
		$html .= '<div id="mobileMessage" class="'.(($_GET['severity']=='notice') ? 'noticediv': 'errordiv' ).'>'.urldecode($_GET['msg']).'</div>';
	}


	$html .= '<div>
		<h2>Administration</h2>
		<a href="/mobile/updateDb?source=local">Update database from local file</a><br />
		<a href="/mobile/updateDb?source=remote">Update database from wurfl.sourceforge.net</a> - This will replace your existing wurfl.xml<br />
		<a href="/mobile/updateDb?action=rebuildCache">Rebuild the device cache</a><br />
		<a href="/mobile/updateDb.php?action=clearCache">Clear the device cache</a> - This will DELETE the device cache, so all devices will need to be redetected.<br />
		<a href="/mobile/generatePatch">Generate Patch File</a> - Allows you to add non-mobile user agents to the the custom patch file from the web interface.  Once you save the changes you can return to this page and reload the WURFL database to apply your changes.  All the user agents you add will be given a fallback id of <strong>generic_web_browser</strong> so as to be detected as a non-mobile device.<br />
		</div>';
		

	$html .= '<div>
		<h2>Diagnostics</h2>
		<a href="/mobile/checkWurfl">Tera-WURFL test script</a><br />
		<a href="/mobile/cache/browseClassic">Browse the device cache</a><br />
		<a href="/mobile/stats">Statistics, Settings, Log File </a><br />
	</div>';
