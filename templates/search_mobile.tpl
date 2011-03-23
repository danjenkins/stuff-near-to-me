<h2>Search results - <span class="searchTerm">{if $smarty.request.search}{$smarty.request.search}{elseif $smarty.request.lat && $smarty.request.lon}Location search{/if}</span></h2>

{*if $smarty.request.lat && $smarty.request.lon}
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBQHOBgHb0dPNDpUHJTTFgsPwKrfVBQSClVYUSL81teciONwJi469CBAdA" type="text/javascript"></script>
	<script src="/js/googleMaps.js"></script>
	<script>
		var points = {literal}{{/literal} 0 : {literal}{{/literal}lat: "{$smarty.request.lat}", lon:"{$smarty.request.lon}", html : "You are here"{literal}}{/literal} {literal}}{/literal};
			{literal}$(function(){googleMaps.init(points);}){/literal}
	</script>
	<div id="googleMapContainer" class="polaroid">
		<div id="gMap">
		</div>
	</div>
{/if*}

<div class="leftCont">
	{if $searchResults|@count > 0}
		{foreach from=$searchResults key="k" item="v"}
			<h2>{$k|capitalize}</h2>
			<ul id="{$k}List" class="searchResultsList">
				{foreach from=$v key="m" item="n"}
					{if $k == 'places'}
						<li class="locationName">{$m|capitalize}</li>
						<ul {if $n|@count == 1}class="placeListContainer"{/if}>
						{foreach from=$n key="s" item="a"}
							{if $n|@count > 1}<li class="categoryName">{$s|splitCamelCase|capitalize}</li>
							<ul class="placeListContainer">{/if}
								{foreach from=$a key="t" item="u"}
									<li><a href="/{$u.locationName}/{$u.categoryName}/{$u.placeId}/{$u.placeName|strtolower|urlencode}">{$u.placeName|capitalize}</a></li>
								{/foreach}
							{if $n|@count > 1}</ul>{/if}
						{/foreach}
						</ul>
					{elseif $k == 'locations'}
						<ul>
							{foreach from=$n key="h" item="i"}
								<li><a href="/{$n.locationName|urlencode}">{$i|capitalize}</a></li>
							{/foreach}
						</ul>
					{elseif $k == 'geo'}
						<ul class="placeListContainer">
							<li><a href="/{$n.locationName}/{$n.categoryName}/{$n.placeId}/{$n.placeName|strtolower|urlencode}">{$n.placeName|capitalize}</a>{if $n.distance} - {$n.distance} miles{/if}</li>
						</ul>
					{/if}
						{*if !$placeLocation || $placeLocation != $n.locationName}
							<li>{$n.locationName}</li><ul>
						{/if}
						<li>
						{if $placeLocation != $n.locationName && $placeLocation}
						
						{/if}
					{elseif $n.locationName}
						<li><a href="/{$n.locationName}">{$n.locationName|capitalize}</a></li>
					{/if}
					{assign var="placeLocation" value=$n.locationName*}
				{/foreach}
			</ul>
		{/foreach}
		{if $searchResults.geo}
			<div id="geoResultsLimit"><a href="/search.html?category={$smarty.request.category}&lat={$smarty.request.lat}&lon={$smarty.request.lon}&limit=10">10</a> | <a href="/search.html?category={$smarty.request.category}&lat={$smarty.request.lat}&lon={$smarty.request.lon}&limit=20">20</a> | <a href="/search.html?category={$smarty.request.category}&lat={$smarty.request.lat}&lon={$smarty.request.lon}&limit=30">30</a> | <a href="/search.html?category={$smarty.request.category}&lat={$smarty.request.lat}&lon={$smarty.request.lon}&limit=40">40</a></div>
		{/if}
	{else}
		<p>Sorry, we couldn't match your term, we're working to improve this, try again below</p>
		{include file="homepage_mobile.tpl"}
	{/if}
</div>

{*if $smarty.request.lat && $smarty.request.lon}
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBQHOBgHb0dPNDpUHJTTFgsPwKrfVBQSClVYUSL81teciONwJi469CBAdA" type="text/javascript"></script>
	<script src="/js/googleMaps.js"></script>
	<script>
		var points = {literal}{{/literal}{literal}}{/literal};
		{foreach from=$searchResults.geo key="g" item="j" name="mapPoints"}
			points[{$g}] = {literal}{{/literal}lat: "{$j.latitude}", lon:"{$j.longitude}", html : "{$j.placeName}"{literal}}{/literal};
		{/foreach}
		points[{$searchResults.geo|@count}] = {literal}{{/literal}lat: "{$smarty.request.lat}", lon:"{$smarty.request.lon}", html : "You are here"{literal}}{/literal};
			{literal}$(function(){googleMaps.init(points);}){/literal}
	</script>
	<div id="googleMapContainer" class="polaroid">
		<div id="gMap">
		</div>
	</div>
{/if*}
