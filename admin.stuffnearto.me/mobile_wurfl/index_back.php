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
 * @version Stable 2.0.0 $Date: 2009/11/13 23:59:59
 * @license http://www.mozilla.org/MPL/ MPL Vesion 1.1
 * $Id: test_usage.php,v 1.2 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: test_usage.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */
require_once("../../classes/mobile/TeraWurfl.php");

$tw = new TeraWurfl();

$db = $tw->db;
$wurflfile = $tw->rootdir.TeraWurflConfig::$DATADIR.TeraWurflConfig::$WURFL_FILE;

$missing_tables = false;
if($db->connected === true){
	$required_tables = array(TeraWurflConfig::$CACHE,TeraWurflConfig::$INDEX,TeraWurflConfig::$MERGE);
	$tables = $db->getTableList();
	foreach($required_tables as $req_table){
		if(!in_array($req_table,$tables)){
			$missing_tables = true;
		}
	}
}

$html .= '<h2>Tera-WURFL Administration<br /><span class="version">Version '.$tw->release_branch.' '.$tw->release_version.'</span>';

if(isset($_GET['msg']) && $_GET['severity']){
	$severity = ($_GET['severity']=='notice')? 'noticediv': 'errordiv';

$html .= '<div align="center" class="'.$severity.'">'.urldecode($_GET['msg']).'</div>';
}

$html .= '<h2>Administration</h2>';
$html .= '<ul>
			<li><a href="updatedb.php?source=local">Update database from local file</a></li>
			<li><strong>Location</strong>:'.$wurflfile.'</li>
			<li>Updates your WURFL database from a local file. The location of this file is defined in <strong>TeraWurflConfig.php</strong></li>
		</ul>';
$html .= '<ul>
			<li><a href="updatedb.php?source=remote">Update database from wurfl.sourceforge.net</a></li>
			<li><strong>Location</strong>:'.(TeraWurflConfig::$WURFL_DL_URL).'</li>
			<li>Updates your WURFL database with the <strong>current stable release</strong> from the <a href="http://sourceforge.net/projects/wurfl/files/WURFL/">official WURFL download site</a></li>
			<li><span class="error"><strong>WARNING: </strong>This will replace your existing wurfl.xml</span></li>
		</ul>';
$html .= '<ul>
			<li><a href="updatedb.php?action=rebuildCache">Rebuild the device cache</a></li>
			<li>Rebuilds the cache table by running through all the devices in the existing cache table and redetecting them using the current WURFL data and re-caching them. This is automatically done when you update the WURFL, but you can manually rebuild it with this link.</li>
		</ul>';
$html .= '<ul>
			<li><a href="updatedb.php?action=clearCache">Clear the device cache</a><br/>Clears (truncates) the device cache.</li>
			<li><span class="error"><strong>WARNING:</strong> This will DELETE the device cache, so all devices will need to be redetected.</span></li>
		</ul>';
$html .= '<ul>
			<li><a href="generatePatch.php">Generate Patch File</a></li>
			<li>Allows you to add non-mobile user agents to the the custom patch file from the web interface.  Once you save the changes you can return to this page and reload the WURFL database to apply your changes.  All the user agents you add will be given a fallback id of <strong>generic_web_browser</strong> so as to be detected as a non-mobile device.</li>
		</ul>';

$html .= '<h2>Diagnostics</h2>';

$html .= '<ul>
			<li><a href="http://fyp/check_wurfl.php">Tera-WURFL test script</a></li>
			<li>This is a good way to test your installation of Tera-WURFL and see how the class handles different user agents.</li>
		</ul>';

$html .= '<ul>
			<li><a href="cache_browser/browse_classic.php">Browse the device cache</a></li>
			<li>Displays the contents of your cache and allows you to see the entire capabilities listing for each device as it appears in the cache.</li>
		</ul>';

$html .= '<ul>
			<li><a href="stats.php">Statistics, Settings, Log File </a></li>
			<li>See statistics about your database tables with detailed descriptions,your current settings and the last errors in your log file.</li>
		</ul>';
		
		echo $html;


