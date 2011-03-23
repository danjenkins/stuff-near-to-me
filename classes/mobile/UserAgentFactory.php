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
 * $Id: UserAgentFactory.php,v 1.7 2008/03/01 00:05:25 kamermans Exp $
 * $RCSfile: UserAgentFactory.php,v $
 * 
 * Based On: Java WURFL Evolution by Luca Passani
 *
 */
class UserAgentFactory{

	// Properties
	public $errors;
	
	public static $matchers = array(
		"Alcatel",
		"Android",
		"AOL",
		"Apple",
		"BenQ",
		"BlackBerry",
		"Bot",
		"CatchAll",
		"Chrome",
		"DoCoMo",
		"Firefox",
		"Grundig",
		"HTC",
		"Kddi",
		"Konqueror",
		"Kyocera",
		"LG",
		"Mitsubishi",
		"Motorola",
		"MSIE",
		"Nec",
		"Nintendo",
		"Nokia",
		"Opera",
		"OperaMini",
		"Panasonic",
		"Pantech",
		"Philips",
		"Portalmmm",
		"Qtek",
		"Safari",
		"Sagem",
		"Samsung",
		"Sanyo",
		"Sharp",
		"Siemens",
		"SonyEricsson",
		"SPV",
		"Toshiba",
		"Vodafone",
		"WindowsCE"
	);
	
	// Constructor
	public function __construct(){
		$this->errors = array();
	}
	
	// Public Methods
	/**
	 * Determines which UserAgentMatcher is the best fit for the incoming user agent and returns it
	 * @param TeraWurfl $wurfl
	 * @param String $userAgent
	 * @return UserAgentMatcher
	 */
	public static function createUserAgentMatcher(TeraWurfl $wurfl,$userAgent){
		// $isMobile means it IS MOBILE, $isDesktop means it IS DESKTOP
		// $isMobile does NOT mean it IS DESKTOP and vica-versa
		$isMobile = UserAgentUtils::isMobileBrowser($userAgent);
		$isDesktop = UserAgentUtils::isDesktopBrowser($userAgent);
		$userAgent_lcase = strtolower($userAgent);
		
		// Process exceptions
		if(TeraWurflConfig::$SIMPLE_DESKTOP_ENGINE_ENABLE && $userAgent == WurflConstants::$SIMPLE_DESKTOP_UA){
			// SimpleDesktopUserAgentMatcher is included via require_once in TeraWurfl.php
			return new SimpleDesktopUserAgentMatcher($wurfl);
		}
		
		// Process MOBILE user agents
		if(!$isDesktop){
			// High workload UAMs go first
			// Nokia
			if(UserAgentMatcher::contains($userAgent,'Nokia')){
				require_once("UserAgentMatchers/NokiaUserAgentMatcher.php");
				return new NokiaUserAgentMatcher($wurfl);
			}
			// Samsung
			if(UserAgentMatcher::contains($userAgent,array("Samsung/SGH","SAMSUNG-SGH")) ||
				UserAgentMatcher::startsWith($userAgent,array("SEC-","Samsung","SAMSUNG","SPH","SGH","SCH"))){
				require_once("UserAgentMatchers/SamsungUserAgentMatcher.php");
			    return new SamsungUserAgentMatcher($wurfl);
			}
			// Blackberry
			if(UserAgentMatcher::contains($userAgent, "BlackBerry")){
				require_once("UserAgentMatchers/BlackBerryUserAgentMatcher.php");
	            return new BlackBerryUserAgentMatcher($wurfl);
			}
			// SonyEricsson
			if(UserAgentMatcher::contains($userAgent,'Sony')){
				require_once("UserAgentMatchers/SonyEricssonUserAgentMatcher.php");
				return new SonyEricssonUserAgentMatcher($wurfl);
			}
			// Motorola
			if(	UserAgentMatcher::startsWith($userAgent,array('Mot-','MOT-','MOTO','moto')) ||
				UserAgentMatcher::contains($userAgent,'Motorola')
			   ){
				require_once("UserAgentMatchers/MotorolaUserAgentMatcher.php");
				return new MotorolaUserAgentMatcher($wurfl);
			}
			
			// Continue processing UAMs in alphabetical order
			// Alcatel
			if(UserAgentMatcher::startsWith($userAgent_lcase,"alcatel")){
				require_once("UserAgentMatchers/AlcatelUserAgentMatcher.php");
			    return new AlcatelUserAgentMatcher($wurfl);
			}
			// Apple
			if(UserAgentMatcher::contains($userAgent, "iPhone") ||
			UserAgentMatcher::contains($userAgent, "iPod")){
				require_once("UserAgentMatchers/AppleUserAgentMatcher.php");
			    return new AppleUserAgentMatcher($wurfl);
			}
			// BenQ
			if(UserAgentMatcher::startsWith($userAgent_lcase,"benq")){
			    require_once("UserAgentMatchers/BenQUserAgentMatcher.php");
			    return new BenQUserAgentMatcher($wurfl);
			}
			// DoCoMo
			if(UserAgentMatcher::startsWith($userAgent,"DoCoMo")){
				require_once("UserAgentMatchers/DoCoMoUserAgentMatcher.php");
			    return new DoCoMoUserAgentMatcher($wurfl);
			}
			// Grundig
			if(UserAgentMatcher::startsWith($userAgent_lcase,"grundig")){
				require_once("UserAgentMatchers/GrundigUserAgentMatcher.php");
			    return new GrundigUserAgentMatcher($wurfl);
			}
			// HTC
			if(UserAgentMatcher::startsWith($userAgent,"HTC")){
				require_once("UserAgentMatchers/HTCUserAgentMatcher.php");
			    return new HTCUserAgentMatcher($wurfl);
			}
			// KDDI
			if(UserAgentMatcher::contains($userAgent,"KDDI-")){
				require_once("UserAgentMatchers/KddiUserAgentMatcher.php");
			    return new KddiUserAgentMatcher($wurfl);
			}
			// Kyocera
			if(UserAgentMatcher::startsWith($userAgent,array("kyocera","QC-","KWC-"))){
				require_once("UserAgentMatchers/KyoceraUserAgentMatcher.php");
			    return new KyoceraUserAgentMatcher($wurfl);
			}
			// LG
			if(UserAgentMatcher::startsWith($userAgent_lcase,"lg")){
			    require_once("UserAgentMatchers/LGUserAgentMatcher.php");
				return new LGUserAgentMatcher($wurfl);
			}
			// Mitsubishi
			if(UserAgentMatcher::startsWith($userAgent,"Mitsu")){
				require_once("UserAgentMatchers/MitsubishiUserAgentMatcher.php");
			    return new MitsubishiUserAgentMatcher($wurfl);
			}
			// NEC
			if(UserAgentMatcher::startsWith($userAgent,array("NEC-","KGT"))){
				require_once("UserAgentMatchers/NecUserAgentMatcher.php");
			    return new NecUserAgentMatcher($wurfl);
			}
			// Nintendo
			if(UserAgentMatcher::contains($userAgent,"Nintendo") || 
				// Nintendo DS: Mozilla/4.0 (compatible; MSIE 6.0; Nitro) Opera 8.50 [en]
				(UserAgentMatcher::startsWith($userAgent,'Mozilla/') && UserAgentMatcher::contains($userAgent,"Nitro") && UserAgentMatcher::contains($userAgent,"Opera"))
				){
				require_once("UserAgentMatchers/NintendoUserAgentMatcher.php");
			    return new NintendoUserAgentMatcher($wurfl);
			}
			// Panasonic
			if(UserAgentMatcher::startsWith($userAgent,"Panasonic")){
				require_once("UserAgentMatchers/PanasonicUserAgentMatcher.php");
			    return new PanasonicUserAgentMatcher($wurfl);
			}
			// Pantech
			if(UserAgentMatcher::startsWith($userAgent,array("Pantech","PT-","PANTECH","PG-"))){
			    require_once("UserAgentMatchers/PantechUserAgentMatcher.php");
			    return new PantechUserAgentMatcher($wurfl);
			}
			// Philips
			if(UserAgentMatcher::startsWith($userAgent_lcase,"philips")){
				require_once("UserAgentMatchers/PhilipsUserAgentMatcher.php");
			    return new PhilipsUserAgentMatcher($wurfl);
			}
			// Portalmmm
			if(UserAgentMatcher::startsWith($userAgent,"portalmmm")){
				require_once("UserAgentMatchers/PortalmmmUserAgentMatcher.php");
			    return new PortalmmmUserAgentMatcher($wurfl);
			}
			// Qtek
			if(UserAgentMatcher::startsWith($userAgent,"Qtek")){
				require_once("UserAgentMatchers/QtekUserAgentMatcher.php");
			    return new QtekUserAgentMatcher($wurfl);
			}
			// Sagem
			if(UserAgentMatcher::startsWith($userAgent_lcase,"sagem")){
				require_once("UserAgentMatchers/SagemUserAgentMatcher.php");
			    return new SagemUserAgentMatcher($wurfl);
			}
			// Sanyo
			if(UserAgentMatcher::startsWith($userAgent_lcase,"sanyo") || UserAgentMatcher::contains($userAgent,"MobilePhone")){
			    require_once("UserAgentMatchers/SanyoUserAgentMatcher.php");
			    return new SanyoUserAgentMatcher($wurfl);
			}
			// Sharp
			if(UserAgentMatcher::startsWith($userAgent_lcase,"Sharp")){
				require_once("UserAgentMatchers/SharpUserAgentMatcher.php");
			    return new SharpUserAgentMatcher($wurfl);
			}
			// Siemens
			if(UserAgentMatcher::startsWith($userAgent,"SIE-")){
				require_once("UserAgentMatchers/SiemensUserAgentMatcher.php");
			    return new SiemensUserAgentMatcher($wurfl);
			}
			// SPV
			if(UserAgentMatcher::contains($userAgent,'SPV')){
				require_once("UserAgentMatchers/SPVUserAgentMatcher.php");
				return new SPVUserAgentMatcher($wurfl);
			}
			// Toshiba
			if(UserAgentMatcher::startsWith($userAgent,"Toshiba")){
				require_once("UserAgentMatchers/ToshibaUserAgentMatcher.php");
			    return new ToshibaUserAgentMatcher($wurfl);
			}
			// Vodafone
			if(UserAgentMatcher::startsWith($userAgent,'Vodafone')){
				require_once("UserAgentMatchers/VodafoneUserAgentMatcher.php");
				return new VodafoneUserAgentMatcher($wurfl);
			}
			
			// Process mobile browsers after mobile devices
			// Android
			if(UserAgentMatcher::contains($userAgent, "Android")){
				require_once("UserAgentMatchers/AndroidUserAgentMatcher.php");
	            return new AndroidUserAgentMatcher($wurfl);
			}
			// Opera Mini
			if(UserAgentMatcher::contains($userAgent,array('Opera Mini','Opera Mobi'))){
				require_once("UserAgentMatchers/OperaMiniUserAgentMatcher.php");
				return new OperaMiniUserAgentMatcher($wurfl);
			}
			// Windows CE
			if(UserAgentMatcher::contains($userAgent, "Mozilla/") && UserAgentMatcher::contains($userAgent, "Windows CE")) {
				require_once("UserAgentMatchers/WindowsCEUserAgentMatcher.php");
				return new WindowsCEUserAgentMatcher($wurfl);
			}
		} // End if(!$isDesktop)

		// Process Robots (Web Crawlers and the like)
		if(UserAgentUtils::isRobot($userAgent)){
			require_once("UserAgentMatchers/BotUserAgentMatcher.php");
            return new BotUserAgentMatcher($wurfl);
		}
		
		// Process NON-MOBILE user agents		
		if(!$isMobile){
			// MSIE
			if(UserAgentMatcher::startsWith($userAgent,"Mozilla") && UserAgentMatcher::contains($userAgent, "MSIE")
				&& !UserAgentMatcher::contains($userAgent, array("Opera", "armv", "MOTO", "BREW"))
			   ){
				require_once("UserAgentMatchers/MSIEUserAgentMatcher.php");
			    return new MSIEUserAgentMatcher($wurfl);
			}
			// Firefox
			if(UserAgentMatcher::contains($userAgent,"Firefox") && !UserAgentMatcher::contains($userAgent,array("Sony","Novarra","Opera"))){
				require_once("UserAgentMatchers/FirefoxUserAgentMatcher.php");
	            return new FirefoxUserAgentMatcher($wurfl);
			}
			// Chrome
			if(UserAgentMatcher::contains($userAgent, "Chrome")){
				require_once("UserAgentMatchers/ChromeUserAgentMatcher.php");
	            return new ChromeUserAgentMatcher($wurfl);
			}
			// Konqueror
			if(UserAgentMatcher::contains($userAgent, "Konqueror")){
				require_once("UserAgentMatchers/KonquerorUserAgentMatcher.php");
	            return new KonquerorUserAgentMatcher($wurfl);
			}
			// Opera
			if(UserAgentMatcher::contains($userAgent, "Opera")){
				require_once("UserAgentMatchers/OperaUserAgentMatcher.php");
			    return new OperaUserAgentMatcher($wurfl);
			}
			// Safari
			if(UserAgentMatcher::startsWith($userAgent,"Mozilla") && UserAgentMatcher::contains($userAgent, "Safari")){
			    require_once("UserAgentMatchers/SafariUserAgentMatcher.php");
				return new SafariUserAgentMatcher($wurfl);
			}
			// AOL
			if(UserAgentMatcher::contains($userAgent, array("AOL", "America Online")) || UserAgentMatcher::contains($userAgent_lcase,"aol 9")){
				require_once("UserAgentMatchers/AOLUserAgentMatcher.php");
	            return new AOLUserAgentMatcher($wurfl);
			}
		}
		
		// Nothing has matched so we will have to use the CatchAllUserAgentMatcher
		return new CatchAllUserAgentMatcher($wurfl);
	}
	public static function userAgentType($wurfl,$userAgent){
		$obj = self::createUserAgentMatcher($wurfl,$userAgent);
		$type = get_class($obj);
		unset($obj);
		return str_replace("UserAgentMatcher",'',$type);
	}
}
require_once("UserAgentMatchers/UserAgentMatcher.php");
require_once("UserAgentMatchers/CatchAllUserAgentMatcher.php");
?>