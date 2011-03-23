<h2>Category - {$categoryName|splitCamelCase|capitalize}</h2>

<div class="leftCont">
	<ul id="category">
		{foreach from=$locationCategories key="k" item="v"}
			<li>
				<a href="/{$locationName}/{$categoryName}/{$v.placeId}/{$v.placeName|urlencode}">{$v.placeName}</a>
			</li>
		{/foreach}
	</ul>
</div>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBQHOBgHb0dPNDpUHJTTFgsPwKrfVBQSClVYUSL81teciONwJi469CBAdA" type="text/javascript"></script>
<script src="/js/googleMaps.js"></script>
	<script>
		var points = {literal}{{/literal}{literal}}{/literal};
				{assign var="ii" value=0}
					{foreach from=$locationCategories key="k" item="v"}
						
						points[{$ii}] =  {literal}{{/literal}lat: "{$v.latitude}", lon:"{$v.longitude}", html: "{$v.placeName|capitalize}"{literal}}{/literal};
						{math assign="ii" equation="x + y" x=$ii y="1"}
					{/foreach}
			{literal}$(function(){googleMaps.init(points);}){/literal}
	</script>
<div id="googleMapContainer" class="polaroid">
	<span class="tapeTL tape"></span>
	<div id="gMap"></div>
	<span class="tapeBR tape"></span>
</div>