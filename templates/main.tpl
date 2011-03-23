<html>
	<head>
		<title>Stuff Near To Me</title>
		<link rel="stylesheet" type="text/css" href="/css/main.css" />
		<link rel="shortcut icon" href="/images/favicon.ico" />
		<script src="/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery.mousewheel-3.0.2.pack.js"></script>
		<script type="text/javascript" src="/js/jquery.fancybox-1.3.1.pack.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox-1.3.1.css" media="screen" />
		<script src="/js/geolocation.js"></script>
		<script src="/js/general.js"></script>
		{if $contentTpl == 'upload.tpl'}
			<script src="/js/ajaxupload.js"></script>
			<script src="/js/fileUpload.js"></script>
			<link rel="stylesheet" type="text/css" href="/css/upload.css" />
		{elseif $contentTpl == 'place.tpl'}
			<link rel="stylesheet" href="/css/gallerySlider.css" type="text/css" media="screen" charset="utf-8">
			<script src="/js/slider.js" type="text/javascript" charset="utf-8"></script>
			<script src="/js/favourite.js" type="text/javascript" charset="utf-8"></script>
		{/if}
		{if !$ie}
			<link rel="stylesheet" type="text/css" href="/css/notIE.css" media="screen" />
		{else}
			<link rel="stylesheet" type="text/css" href="/css/ie.css" media="screen" />
		{/if}
		{if $contentTpl == 'contact.tpl'}
			<script src="/js/review.js" type="text/javascript" charset="utf-8"></script>
		{/if}
	</head>
	<body>
		<div id="pageContainer">
			{include file="header.tpl"}
			<div id="contentContainer">
				<div class="widthContainer">
					{include file=$contentTpl}
				</div>
			</div>
			{include file="footer.tpl"}
		</div>
		<script type="text/javascript">
		{literal}
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