<html>
	<head>
		<title>Stuff Near To Me</title>
		<script src="/js/jquery-1.4.2.min.js"></script>
		<link rel="shortcut icon" href="/images/favicon.ico" />
		<script src="/js/geolocation.js"></script>
		<link rel="stylesheet" href="/css/mobile.css" type="text/css" media="all" />{*handheld - for mobile*}
		{if $contentTpl == 'submitReview_mobile.tpl'}
			<script src="/js/jquery.MetaData.js" type="text/javascript" language="javascript"></script>
			<script src="/js/jquery.rating.js" type="text/javascript" language="javascript"></script>
			<link href="/css/jquery.rating.css" type="text/css" rel="stylesheet"/>
		{/if}
		{if $contentTpl == 'submitReview_mobile.tpl' || $contentTpl == 'report_mobile.tpl'}
			<script src="/js/review.js" type="text/javascript" language="javascript"></script>
		{/if}
		{if $browserCapabilities.product_info.model_name == 'iPhone'}
			<meta name="viewport" content="width=device-width; maximum-scale=1.0;" id="viewport">
			<link rel="apple-touch-icon" href="/images/sntm.png"/>
			<meta name="apple-touch-fullscreen" content="YES" /> 
			<script src="/js/iphone.js"></script>
		{/if}
		{*<style type="text/css">
		{literal}
			body{
			width:100%;
			height:150%;
			}
		{/literal}
		</style>*}
	</head>
	<body>{*mobile is 100% - completely fluid!*}
		<div id="pageContainer">
			{include file="header_mobile.tpl"}
			<div id="contentContainer">
				{include file=$contentTpl}
			</div>
			{include file="footer_mobile.tpl"}
		</div>
		<script type="text/javascript">{literal}
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-15593420-1");
pageTracker._setDomainName(".stuffnearto.me");
pageTracker._trackPageview();
} catch(err) {}{/literal}</script>
	</body>
</html>