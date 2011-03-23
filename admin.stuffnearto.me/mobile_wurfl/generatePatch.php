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
 * $Id: TeraWurflConfig.php,v 1.11 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: TeraWurflConfig.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */

require_once("/home/jenkins2/public_html/stuffnearto.me/classes/mobile/TeraWurfl.php");

try {
	$tw = new TeraWurfl();
}catch(Exception $e){
	
}

$patch_changed = false;
$custom_patch = $tw->rootdir.TeraWurflConfig::$DATADIR."custom_web_patch.xml";
$custom_patch_user_agents = $tw->rootdir.TeraWurflConfig::$DATADIR."custom_web_patch_uas.txt";

if(isset($_POST['action']) && $_POST['action']=='generate_patch'){
	$patch_data = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<wurfl_patch>\n\t<devices>";
	$i = 0;
	$rawdata = $_POST['data'];
	if(get_magic_quotes_gpc()){$rawdata=stripslashes($rawdata);}
	$rawdata = preg_replace('/[\r\n]+/',"\n",$rawdata);
	$data = explode("\n",$rawdata);
	foreach($data as $line){
		$line = trim($line);
		if($line == "")continue;
		$patch_data .= "\n\t\t".'<device user_agent="'.htmlspecialchars($line).'" fall_back="generic_web_browser" id="terawurfl_generic_web_browser'.$i++.'"/>';
	}
	$patch_data .= "\n\t</devices>\n</wurfl_patch>";
	file_put_contents($custom_patch_user_agents,$rawdata);
	file_put_contents($custom_patch,$patch_data);
	$patch_changed = true;
}

if($patch_changed){
	$html .= '<div align="center" class="noticediv" style="width: 100%">Custom patch file saved.  <a href="#patch">View patch file</a></div>';
}

	$html .= '<table border="0" cellspacing="0" cellpadding="0">
		<tr><th>Enter your non-mobile user agents below</th></tr>
	</table>
	</td></tr>
	<tr><td class="lightrow">Enter your non-mobile user agents below, one per line, and press <strong>Generate Patch File</strong>.  These user agents will be compiled into the Tera-WURFL custom patch file <strong>'. $custom_patch.'</strong>. After you submit the changes, go to the <a href="/mobile/main">Tera-WURFL Administration Page</a> and update your WURFL database to load the new patch file.</td></tr>
	<tr>
		<td><form action="/mobile/generatePatch" method="post">
		<input type="hidden" name="action" value="generate_patch" />
		<textarea name="data" rows="25" cols="97" style="width: 100%;">'. file_get_contents($custom_patch_user_agents).'</textarea>
		<br/><center><button>Generate Patch File</button></center>
		</form></td>
	</tr>
</table>
<pre><a name="patch"></a>';
if($patch_changed){ $html .= htmlspecialchars(file_get_contents($custom_patch));}
$html .= '</pre>';