<h2>{$locationInfo.locationName|capitalize}</h2>
{if $placeList}
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBQHOBgHb0dPNDpUHJTTFgsPwKrfVBQSClVYUSL81teciONwJi469CBAdA" type="text/javascript"></script>
<script src="/js/googleMaps.js"></script>
	<script>
		var points = {literal}{{/literal}{literal}}{/literal};
				{assign var="ii" value=0}
				{foreach from=$placeList key="w" item="u" name="info"}
					{foreach from=$u key="e" item="f" }
						
						points[{$ii}] =  {literal}{{/literal}lat: "{$f.latitude}", lon:"{$f.longitude}", html: "{$f.placeName|capitalize}"{literal}}{/literal};
						{math assign="ii" equation="x + y" x=$ii y="1"}
					{/foreach}
				{/foreach}
			{literal}$(function(){googleMaps.init(points);}){/literal}
	</script>
<div id="googleMapContainer" class="polaroid">
	<span class="tapeTL tape"></span>
	<div id="gMap"></div>
	<span class="tapeBR tape"></span>
</div>
{/if}

<h2>Places in {$locationInfo.locationName|capitalize}</h2>

{if $placeList}
<div id="listOfPlacesInLocation">
	<ul class="locationPlaces">
	{foreach from=$placeList key="k" item="v"}
		<li>{$k|capitalize|splitCamelCase}</li>
		<ul class="categoryGroup">
		{foreach from=$v key="s" item="t"}
			<li><a href="/{$locationInfo.locationName}/{$t.categoryName}/{$t.placeId}/{$t.placeName|urlencode}" title="{$t.placeName}">{$t.placeName|capitalize}</a></li>
		{/foreach}
		</ul>
	{/foreach}
	</ul>
</div>
{else}
<p>Unfortunately there are no places currently in {$locationInfo.locationName|capitalize}</p>
{/if}

