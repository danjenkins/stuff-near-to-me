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
 * $Id: TeraWurflDatabase.php,v 1.10 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: TeraWurflDatabase.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */
 require_once("/home/jenkins2/public_html/stuffnearto.me/classes/mobile/TeraWurfl.php");
require_once("/home/jenkins2/public_html/stuffnearto.me/classes/mobile/TeraWurflLoader.php");

error_reporting(E_ALL);
if(TeraWurflConfig::$OVERRIDE_MEMORY_LIMIT){
	ini_set("memory_limit",TeraWurflConfig::$MEMORY_LIMIT);
}
/**
 * Set the script time limit (default: 20 minutes)
 */
set_time_limit(60*20);

$source = (isset($_GET['source']))? $_GET['source']: "local";

$base = new TeraWurfl();
if($base->db->connected !== true){
	throw new Exception("Cannot connect to database: ".$base->db->errors[0]);
}

if(isset($_GET['action']) && $_GET['action']=='rebuildCache'){
	$base->db->rebuildCacheTable();
	header("Location: /mobile/main?msg=".urlencode("The cache has been successfully rebuilt ({$base->db->numQueries} queries).")."&severity=notice");
	exit(0);
}
if(isset($_GET['action']) && $_GET['action']=='clearCache'){
	$base->db->createCacheTable();
	header("Location: /mobile/main?msg=".urlencode("The cache has been successfully cleared ({$base->db->numQueries} queries).")."&severity=notice");
	exit(0);
}

$newfile = TeraWurfl::absoluteDataDir().TeraWurflConfig::$WURFL_FILE.".zip";
$wurflfile = TeraWurfl::absoluteDataDir().TeraWurflConfig::$WURFL_FILE;

if($source == "remote" || $source == "remote_cvs"){
	if($source == "remote"){
		$dl_url = TeraWurflConfig::$WURFL_DL_URL; 
	}elseif($source == "remote_cvs"){
		$dl_url = TeraWurflConfig::$WURFL_CVS_URL;
	}
	$html .=  "Downloading WURFL from $dl_url ...\n<br/>";
	flush();
	if(!file_exists($newfile) && !is_writable($base->rootdir.TeraWurflConfig::$DATADIR)){
		$base->toLog("Cannot write to data directory (permission denied)",LOG_ERR);
		Throw New Exception("Fatal Error: Cannot write to data directory (permission denied). (".$base->rootdir.TeraWurflConfig::$DATADIR.")<br/><br/><strong>Please make the data directory writable by the user or group that runs the webserver process, in Linux this command would do the trick if you're not too concerned about security: <pre>chmod -R 777 ".$base->rootdir.TeraWurflConfig::$DATADIR."</pre></strong>");
		exit(1);
	}
	if(file_exists($newfile) && !is_writable($newfile)){
		$base->toLog("Cannot overwrite WURFL file (permission denied)",LOG_ERR);
		Throw New Exception("Fatal Error: Cannot overwrite WURFL file (permission denied). (".$base->rootdir.TeraWurflConfig::$DATADIR.")<br/><br/><strong>Please make the data directory writable by the user or group that runs the webserver process, in Linux this command would do the trick if you're not too concerned about security: <pre>chmod -R 777 ".$base->rootdir.TeraWurflConfig::$DATADIR."</pre></strong>");
		exit(1);
	}
	// Download the new WURFL file and save it in the DATADIR as wurfl.zip
	@ini_set('user_agent', "PHP/Tera-WURFL_$version");
	$download_start = microtime(true);
	if(!$gzdata = file_get_contents($dl_url)){
		Throw New Exception("Error: Unable to download WURFL file from ".TeraWurflConfig::$WURFL_DL_URL);
		exit(1);
	}
/*	$destination=fopen($newfile,"w"); 
	$source=fopen($dl_url,"r"); 
	while ($block=fread($source,256*1024)) fwrite($destination,$block);
	fclose($source);
	fclose($destination);
*/
	$download_time = microtime(true) - $download_start;
	file_put_contents($newfile,$gzdata);
	$gzsize = WurflSupport::formatBytes(filesize($newfile));
	// Try to use ZipArchive, included from 5.2.0
	if(class_exists("ZipArchive")){
		$zip = new ZipArchive();
		if ($zip->open(str_replace('\\','/',$newfile)) === TRUE) {
		    $zip->extractTo(str_replace('\\','/',dirname($wurflfile)),array('wurfl.xml'));
		    $zip->close();
		} else {
		    Throw New Exception("Error: Unable to extract wurfl.xml from downloaded archive: $newfile");
			exit(1);
		}
	}else{
		system("gunzip $newfile");
	}
	$size = WurflSupport::formatBytes(filesize($wurflfile))." [$gzsize compressed]";
	$download_rate = WurflSupport::formatBitrate(filesize($newfile), $download_time);
	$ok = true;
	$html .=  "done ($wurflfile: $size)<br />Downloaded in $download_time sec @ $download_rate <br/><br/>";
	usleep(50000);
	flush();
}

$loader = new TeraWurflLoader($base,TeraWurflLoader::$WURFL_LOCAL);
//$ok = $base->db->initializeDB();
$ok = $loader->load();
if($ok){
	$html .=  "<strong>Database Update OK</strong><hr />";
	$html .=  "Total Time: ".$loader->totalLoadTime()."<br/>";
	$html .=  "Parse Time: ".$loader->parseTime()."<br/>";
	$html .=  "Validate Time: ".$loader->validateTime()."<br/>";
	$html .=  "Sort Time: ".$loader->sortTime()."<br/>";
	$html .=  "Patch Time: ".$loader->patchTime()."<br/>";
	$html .=  "Database Time: ".$loader->databaseTime()."<br/>";
	$html .=  "Cache Rebuild Time: ".$loader->cacheRebuildTime()."<br/>";
	$html .=  "Number of Queries: ".$base->db->numQueries."<br/>";
	if(version_compare(PHP_VERSION,'5.2.0') === 1){
		$html .=  "PHP Memory Usage: ".WurflSupport::formatBytes(memory_get_peak_usage())."<br/>";
	}
	$html .=  "--------------------------------<br/>";
	$html .=  "WURFL Version: ".$loader->version." (".$loader->last_updated.")<br />";
	$html .=  "WURFL Devices: ".$loader->mainDevices."<br/>";
	$html .=  "PATCH New Devices: ".$loader->patchAddedDevices."<br/>";
	$html .=  "PATCH Merged Devices: ".$loader->patchMergedDevices."<br/>";
}else{
	$html .=  "ERROR LOADING DATA!<br/>";
	$html .=  "Errors: <br/>\n";
	$html .=  "<pre>".htmlspecialchars(var_export($loader->errors,true))."</pre>";
}

$html .=  "<hr/><a href=\"/mobile/main\">Return to administration tool.</a>";
?>
