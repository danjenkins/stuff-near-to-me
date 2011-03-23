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


$mergestats = $db->getTableStats(TeraWurflConfig::$MERGE);
$indexstats = $db->getTableStats(TeraWurflConfig::$INDEX);
$cachestats = $db->getTableStats(TeraWurflConfig::$CACHE);
$matcherList = $db->getMatcherTableList();
$matchers = array();
foreach($matcherList as $name){
	$matchers[] = array('name'=>$name,'stats'=>$db->getTableStats($name));
}

$logfile = $tw->rootdir.TeraWurflConfig::$DATADIR.TeraWurflConfig::$LOG_FILE;

if(!is_readable($logfile) || filesize($logfile) < 5){
	$lastloglines = "Empty";
}else{
	$logarr = file($logfile);
	$loglines = 30;
	if(count($logarr)<$loglines)$loglines=count($logarr);
	$end = count($logarr)-1;
	$lastloglines = '';
	for($i=$end;$i>=($end-$loglines);$i--){
		$lastloglines .= @htmlspecialchars($logarr[$i])."<br />";
	}
}


		$html .= '<table>
		<tr>
			<th scope="col">Database Table </th>
			<th scope="col">Statistics</th>
		</tr>
		<tr>
			<td class="darkrow">MERGE<br />
					<span class="setting">'.(TeraWurflConfig::$MERGE).'</span></td>
			<td class="darkrow">Rows: <span class="setting">'.$mergestats['rows'].'</span><br />
				Actual Devices: <span class="setting">'.$mergestats['actual_devices'].'</span> <br />
				Table Size: <span class="setting">'.(WurflSupport::formatBytes($mergestats['bytesize'])).'</span><br />
				Purpose:<br />
				<span class="setting">The MERGE table holds all the data from the WURFL file, whether it be local, remote or remote CVS,  whenever a new WURFL is loaded, it is loaded into this table first, then it is filtered through all the UserAgentMatchers and split into many different tables specific to each matching technique. This MERGE table is retained for a last chance lookup if the UserAgentMatchers and INDEX table are unable to provide a conclusive match.</span></td>
		</tr>
		<tr>
			<td class="lightrow">INDEX		<br />
				<span class="setting">'.(TeraWurflConfig::$INDEX).'</span></td>
		  <td class="lightrow">Rows: <span class="setting">'.$indexstats['rows'].'</span><br />
				Table Size: <span class="setting">'.(WurflSupport::formatBytes($indexstats['bytesize'])).'</span><br />
				Purpose:<br />
				<span class="setting">The INDEX table acts as a lookup table for WURFL IDs and their respective UserAgentMatchers. </span></td>
		</tr>
		
		<tr>
			<td class="darkrow">CACHE		<br />
				<span class="setting">'.(TeraWurflConfig::$CACHE).'</span></td>
<td class="darkrow">Rows: <span class="setting">'.($cachestats['rows']).'</span><br />
				Table Size: <span class="setting">'.(WurflSupport::formatBytes($cachestats['bytesize'])).'</span><br />
				Purpose:<br />
				<span class="setting">The CACHE table stores unique user agents and the complete capabilities and device root that were determined when the device was first identified. <strong>Unlike version 1.x</strong>, the CACHE table stores every device that is detected <strong>permanently</strong>. When the device database is updated, the cached devices are also redetected and recached. This behaviour is configurable.</span></td>
		</tr>
		<tr>
			<td class="lightrow" style="vertical-align:top;">User Agent Matchers<br/>
				Purpose:<br />
				<span class="setting">The User Agent Matchers store similar user agents.  Tera-WURFL sorts all the devices into the most appropriate UserAgentMatcher table to make lookups faster and perform different matching hueristics on certain groups of devices.</span></td><td>
				<table>';


$i=0;
foreach($matchers as $matcher){
	$class = ($i % 2 == 0)? "lightrow": "darkrow";
	$html .= '<tr><td class="'.$class.'">UserAgentMatcher: <span class="setting">'.$matcher['name'].'</span><br />
Rows: <span class="setting">'.$matcher['stats']['rows'].'</span><br />
Table Size: <span class="setting">'.(WurflSupport::formatBytes($matcher['stats']['bytesize'])).'</span></td></tr>';
	$i++;
}
	$html .= '</table></td>
		</tr>
	</table>
<p><br/>
			<br/>
	</p>
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th scope="col">Tera-WURFL Settings</th>
		</tr>
		<tr><td>Installation Directory: <span class="setting">'.(dirname(dirname(__FILE__))).'</span></td></tr>
		<tr>
			<td class="lightrow"><p>-- Database options --<br/>
				DB_HOST <span class="setting">'.(TeraWurflConfig::$DB_HOST).'</span>,	database server hostname or IP<br />
				DB_USER <span class="setting">'.(TeraWurflConfig::$DB_USER).'</span>,	database username (needs SELECT,INSERT,DELETE,DROP,CREATE)<br />
				DB_PASS <span class="setting">********</span>, database password<br />
				DB_SCHEMA <span class="setting">'.(TeraWurflConfig::$DB_SCHEMA).'</span>, database schema (database name)<br />
				DB_CONNECTOR <span class="setting">'.(TeraWurflConfig::$DB_CONNECTOR).'</span>, database type (MySQL5, MSSQL, Oracle, etc...);<br />DEVICES <span class="setting">'.(TeraWurflConfig::$DEVICES).'</span>, database table name for the WURFL with the patch updates<br />CACHE <span class="setting">'.(TeraWurflConfig::$CACHE).'</span>, database table name for the cache<br />INDEX <span class="setting">'.(TeraWurflConfig::$INDEX).'</span>, database table name for the Index of the WURFL IDs and UserAgentMatchers<br />
	MERGE <span class="setting">'.(TeraWurflConfig::$MERGE).'</span>, database table name for the combined UserAgentMatcher tables <br />
							<br />
					-- General options --<br />
					WURFL_DL_URL <span class="setting">
						'.TeraWurflConfig::$WURFL_DL_URL.'
							</span>, full URL to the current WURFL<br />
					WURFL_CVS_URL <span class="setting">
						'. TeraWurflConfig::$WURFL_CVS_URL.'
					  </span>, full URL to development (CVS) WURFL<br />
					DATADIR <span class="setting">
						'. TeraWurflConfig::$DATADIR.'
			  </span>,	where all data is stored (wurfl.xml, temp files, logs)<br />
					  CACHE_ENABLE <span class="setting">'. WurflSupport::showBool(TeraWurflConfig::$CACHE_ENABLE).'</span>, enables or disables the cache <br />
					PATCH_ENABLE <span class="setting">
						'. WurflSupport::showBool(TeraWurflConfig::$PATCH_ENABLE).'
				  </span>, enables or disables the patch<br />
					PATCH_FILE <span class="setting">
						'. TeraWurflConfig::$PATCH_FILE.'
</span>, optional patch file for WURFL. To use more than one, separate them with semicolons<br />
					WURFL_FILE <span class="setting">
						'. TeraWurflConfig::$WURFL_FILE.'
						</span>, path and filename of wurfl.xml<br />
					WURFL_LOG_FILE <span class="setting">
						'. TeraWurflConfig::$LOG_FILE.'
						</span>, defines full path and filename for logging<br />
					LOG_LEVEL <span class="setting">
						'. WurflSupport::showLogLevel(TeraWurflConfig::$LOG_LEVEL).'
						</span>, desired logging level. Use the same constants as for PHP logging<br />
					OVERRIDE_MEMORY_LIMIT <span class="setting">
						'. WurflSupport::showBool(TeraWurflConfig::$OVERRIDE_MEMORY_LIMIT).'
						</span>, override PHP\'s default memory limit<br />
					MEMORY_LIMIT <span class="setting">
						'. TeraWurflConfig::$MEMORY_LIMIT.'
						</span>, the amount of memory to allocate to PHP if OVERRIDE_MEMORY_LIMIT is enabled<br />
					SIMPLE_DESKTOP_ENGINE_ENABLE <span class="setting">
						'. WurflSupport::showBool(TeraWurflConfig::$SIMPLE_DESKTOP_ENGINE_ENABLE).'
						</span>, enable the SimpleDesktop Detection Engine to increase performance<br />
					CAPABILITY_FILTER:
						'. "<pre class=\"setting\">".var_export(TeraWurflConfig::$CAPABILITY_FILTER,true)."</pre>".'
						the capability filter that is used to determine which capabilities are available<br />
			</p>
				</td>
		</tr>
	</table>
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th scope="col">Log File (last 30 lines) </th>
		</tr>
		<tr>
			<td class="lightrow"><div class="logfile">'. $lastloglines.'</div>
				<br/></td>
		</tr>
	</table>';
