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
 * $Id: SimpleDesktopUserAgentMatcher.php,v 1.2 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: SimpleDesktopUserAgentMatcher.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */
class SimpleDesktopUserAgentMatcher extends UserAgentMatcher {
	public function __construct(TeraWurfl $wurfl){
		parent::__construct($wurfl);
	}
	public function applyConclusiveMatch($ua) {
		return WurflConstants::$GENERIC_WEB_BROWSER;
	}
	public static function isDesktopBrowser($ua){
		if(UserAgentUtils::isMobileBrowser($ua)) return false;
		if(self::contains($ua,"Firefox") && !self::contains($ua,'Tablet')) return true;
		if(UserAgentUtils::isDesktopBrowser($ua)) return true;
		if(self::regexContains($ua,'/^Mozilla\/4\.0 \(compatible; MSIE \d.\d; Windows NT \d.\d/')) return true;
		if(self::contains($ua,array(
			"Chrome",
			"yahoo.com",
			"google.com",
			"Comcast"
		))){
			return true;
		}
		return false;
	}
}
?>