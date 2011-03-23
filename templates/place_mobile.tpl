<h2>{$placeInformation.placeName|capitalize}</h2>

<div id="points">
	<div id="placeAddress">
		<h3>Contact details</h3>
		<span>{$placeInformation.buildingNo}</span><br />
		<span><a href="http://maps.google.com/maps?z=17&q={if $placeInformation.latitude}{$placeInformation.latitude},{$placeInformation.longitude}{else}{$placeInformation.postCode}{/if}%28{$placeInformation.placeName|capitalize}%29" title="Open in google maps" target="_blank">{$placeInformation.postCode}</a></span>
		{if $placeInformation.phoneNumber}<br /><span>Phone no: {$placeInformation.phoneNumber}</span>{/if}
		{if $placeInformation.faxNumber}<br /><span>Fax no: {$placeInformation.faxNumber}</span>{/if}
		{if $placeInformation.webAddress}<br /><span>Web Address: <a target="_blank" href="{$placeInformation.webAddress}" title="{$placeInformation.placeName}">{$placeInformation.webAddress|shortenUrl:"40"}</a></span>{/if}
	</div>


	<div id="placeInfo">
		<h3>Place Information</h3>
		{assign var="i" value=0}
		{foreach from=$placeInformation key="k" item="v"}
			{if ($v === 0 || $v === 1 || $v == '1' || $v == '0') && $v != '' && $k != 'placeId' && $k != 'categoryId' && $k != 'approved' && $k != 'archived' && $k != 'locationId'}
				{math assign="i" equation="x + y" x=$i y=1}
				{if $i === 1}
					<table id="placeInfoTable">
				{/if}
				<tr {if $i is even}class="even"{/if}>
					<td class="placeInfoName">{$k|splitCamelCase|capitalize}</td>
					<td class="placeInfoVal">{if $v == '0'}No{elseif $v == '1'}Yes{else}{$v}{/if}</td>
				</tr>
			{/if}
		{/foreach}
		{if $i > 0}
			</table>
		{else}
			<span>No place information, sorry</span>
		{/if}
	</div>
</div>

{*<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBQHOBgHb0dPNDpUHJTTFgsPwKrfVBQSClVYUSL81teciONwJi469CBAdA" type="text/javascript"></script>

<script src="/js/googleMaps.js"></script>
	<script>
		var points = 
			{literal}{{/literal} 
				
					0 : {literal}{{/literal}lat: "{$placeInformation.latitude}", lon:"{$placeInformation.longitude}", html: "{$placeInformation.placeName}<br />{$placeInformation.buildingNo}<br />{$placeInformation.postCode}"{literal}}{/literal}
		
			{literal}}{/literal};
			{literal}$(function(){googleMaps.init(points);}){/literal}
	</script>
<div id="googleMapContainer" class="polaroid">
	<span class="tape tapeTL"></span>
	<div id="gMap">
	</div>
	<span class="tape tapeBR"></span>
</div>

*}

<div id="webLinks">
	<h3>Get Directions</h3>
	<h4>From your address</h4>
	<form action="http://maps.google.co.uk/maps" method="get" target="_blank">
		<input id="userAddr" type="text" name="saddr" value=""/>
		<input type="hidden" name="daddr" value="{$placeInformation.latitude},{$placeInformation.longitude}" />
		<button>Get Directions By Postcode</button>
	</form>
	<div id="geoLocationDirections" style="display:none;">{*display noned so that it isnt shown if the browser doesnt have the capability*}
		<h4>From your current Location</h4>
		<form action="http://maps.google.co.uk/maps" method="get" target="_blank">
			<input type="hidden" name="saddr" value=""/>
			<input type="hidden" name="daddr" value="{$placeInformation.latitude},{$placeInformation.longitude}" />
			<button>Get Directions Via Geo</button>
		</form>
	</div>
	
	{if $placeInformation.menuLocation}
	<h3>Menus</h3>
		<ul>
			{foreach from=$placeInformation.menuLocation key="m" item="n" name="menu"}
				{isUrlImage var="webMenuLinkImage" url=$n}<li><a target="_blank" class="{if $webMenuLinkImage}imageFancy{else}webFancy{/if}" href="{$n}">Menu {if $placeInformation.menuLocation|@count > 1}{$smarty.foreach.menu.iteration }{/if}</a></li>
			{/foreach}
		</ul>
	{/if}
	<h3>Problem?</h3>
	<span>Something not right? <a href="/report.html?placeId={$placeId}" id="reportPlace{$placeId}" title="Something not right?">let us know</a></span><br />
	
	
	
</div>


<div id="otherContentContainer">
    {stuffImage var="imageList" place=$placeId noHtml=1}
    {if $imageList}
    	<div id="images">
    		<h3>Images</h3>
    		<a href="/imageGallery.html?placeId={$placeId}">View Images</a>
    	</div>
    {/if}
    
    {reviews placeId=$placeId var="reviews"}
    <div id="reviews">
    	<h3>Reviews</h3>
    	{if $reviews}
    		<a href="/reviews.html?placeId={$placeId}&type=place&id={$placeId}&categoryId={$placeInformation.categoryId}&locationId={$placeInformation.locationId}">View reviews for {$placeInformation.placeName|capitalize}</a>
    	{else}
    		No reviews, want to submit <a href="/submitReview.html?placeId={$placeId}&type=place&id={$placeId}&categoryId={$placeInformation.categoryId}&locationId={$placeInformation.locationId}">one</a>?
		{/if}
	</div>

</div>