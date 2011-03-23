<html>
	<head>
		<script src="/js/jquery-1.4.2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/main.css" />
		{if $contentTpl == 'submitMenu.tpl'}
			<script src="/js/ajaxupload.js"></script>
			<script src="/js/menuUpload.js"></script>
		{/if}
		{if $contentTpl == 'submitReview.tpl'}
			<script src="/js/jquery.MetaData.js" type="text/javascript" language="javascript"></script>
			<script src="/js/jquery.rating.js" type="text/javascript" language="javascript"></script>
			<link href="/css/jquery.rating.css" type="text/css" rel="stylesheet"/>
		{/if}
		{if $contentTpl == 'submitReview.tpl' || $contentTpl == 'report.tpl'}
			<script src="/js/review.js" type="text/javascript" language="javascript"></script>
		{/if}
		{if $contentTpl == 'browsers.tpl'}
			<link href="/css/browsers.css" type="text/css" rel="stylesheet"/>
		{/if}
	</head>
	<body>
		{include file=$contentTpl}
	</body>
</html>