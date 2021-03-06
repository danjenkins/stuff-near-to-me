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
 * $Id: MSIEUserAgentMatcher.php,v 1.2 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: MSIEUserAgentMatcher.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */
class MSIEUserAgentMatcher extends UserAgentMatcher {
	public function __construct(TeraWurfl $wurfl){
		parent::__construct($wurfl);
	}
	public function applyConclusiveMatch($ua) {
		$matches = array();
		if(preg_match('/^Mozilla\/4\.0 \(compatible; MSIE (\d)\.(\d);/',$ua,$matches)){
			if(TeraWurflConfig::$SIMPLE_DESKTOP_ENGINE_ENABLE){
				return WurflConstants::$GENERIC_WEB_BROWSER;
			}
			switch($matches[1]){
				// cases are intentionally out of sequnce for performance
				case 7:
					return 'msie_7';
					break;
				case 8:
					return 'msie_8';
					break;
				case 6:
					return 'msie_6';
					break;
				case 4:
					return 'msie_4';
					break;
				case 5:
					return ($matches[2]==5)? 'msie_5_5': 'msie_5';
					break;
				default:
					return 'msie';
					break;
			}
		}
		$ua = preg_replace('/( \.NET CLR [\d\.]+;?| Media Center PC [\d\.]+;?| OfficeLive[a-zA-Z0-9\.\d]+;?| InfoPath[\.\d]+;?)/','',$ua);
		$tolerance = UserAgentUtils::firstSlash($ua);
		$this->wurfl->toLog("Applying ".get_class($this)." Conclusive Match: RIS with threshold $tolerance",LOG_INFO);
		return $this->risMatch($ua, $tolerance);
	}
	public function recoveryMatch($ua){
		if(self::contains($ua,array(
			'SLCC1',
			'Media Center PC',
			'.NET CLR',
			'OfficeLiveConnector'
		  ))){
			return WurflConstants::$GENERIC_WEB_BROWSER;
		}
	}
}
?>