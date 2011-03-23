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

$tw = new TeraWurfl();
$db = $tw->db;

$missing_tables = false;
if($db->connected === true){
	$required_tables = array(TeraWurflConfig::$CACHE);
	$tables = $db->getTableList();
// See what tables are in the DB
//die(var_export($tables,true));
	foreach($required_tables as $req_table){
		if(!in_array($req_table,$tables)){
			$missing_tables = true;
		}
	}
}

	$html .= '<h2>Cached Browsers</h2>';
	$html .= '<table>
<tr><th colspan="2">Cached User Agents</th></tr>';

$cached_uas = $db->getCachedUserAgents();
$i = 0;
foreach($cached_uas as $ua){
	$class = ($i++ % 2 == 0)? 'lightrow': 'darkrow';
	$html .= "<tr><td>$i)</td><td class=\"$class\"><pre style=\"padding: 0px; margin: 0px;\"><a target=\"_blank\" href=\"/mobile/cache/capabilities?ua=".base64_encode($ua)."\" title=\"Click to see details\">".htmlspecialchars($ua)."</a></pre></td></tr>";
}
$html .= '</table>';