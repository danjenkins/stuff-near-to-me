<h2 id="placeName">{$placeInformation.placeName|capitalize}</h2>{if $loggedIn}<a id="userFavs{$placeId}" href="/process/userFavs?placeId={$placeId}&favourite={if $userInfo.UserFavPlaces[$placeId]}0{else}1{/if}" class="{if $userInfo.UserFavPlaces[$placeId]}placeFavourited{else}placeNotFavourited{/if} userFavSelect"><img src="/images/icons/star{if $userInfo.UserFavPlaces[$placeId]}{else}_off{/if}_48.png" title="Is this place a favourite or not?" /></a>{/if}

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
			{if $v != '' && $k != 'placeId' && $k != 'categoryId' && $k != 'approved' && $k != 'archived' && $k != 'locationId' && $k != 'faxNumber' && $k != 'phoneNumber' && $k != 'latitude' && $k != 'longitude' && $k != 'buildingNo' && $k != 'postCode' && $k != 'menuLocation' && $k != 'placeName' && $k != 'MenuExternalLink' && $k != 'webAddress' && $k != 'locationName' && $k != 'categoryName'}
				{math assign="i" equation="x + y" x=$i y=1}
				{if $i === 1}
					<table id="placeInfoTable">
				{/if}
				<tr {if $i is even}class="even"{/if}>
					<td class="placeInfoName">{$k|splitCamelCase|capitalize}</td>
					<td class="placeInfoVal">{if $v == '0'}No{elseif $v == '1'}Yes{else}{$v|capitalize}{/if}</td>
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

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAP5SYVmYvna1oyDk6_OuMzBQHOBgHb0dPNDpUHJTTFgsPwKrfVBQSClVYUSL81teciONwJi469CBAdA" type="text/javascript"></script>

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
<div id="webLinks">
	<h3>Get Directions</h3>
		<h4>From your address</h4>
		<form action="http://maps.google.co.uk/maps" method="get" target="_blank">
			<label for="userAddr">Address:</label>
			<input id="userAddr" type="text" name="saddr" value=""/><br />
			<input type="hidden" name="daddr" value="{$placeInformation.latitude},{$placeInformation.longitude}" />
			<button>Get Directions</button>
		</form>
		<div id="geoLocationDirections" style="display:none;">{*display noned so that it isnt shown if the browser doesnt have the capability*}
			<h4>From your current Location</h4>
			<form action="http://maps.google.co.uk/maps" method="get" target="_blank">
				<input type="hidden" name="saddr" value=""/>
				<input type="hidden" name="daddr" value="{$placeInformation.latitude},{$placeInformation.longitude}" />
				<button>Get Directions</button>
			</form>
		</div>

	
	{if $placeInformation.menuLocation}
	<h3>Menus</h3>
	
		<ul>
			{foreach from=$placeInformation.menuLocation key="m" item="n" name="menu"}
				{isUrlImage var="webMenuLinkImage" url=$n}<li><a target="_blank" class="{if $webMenuLinkImage.image}{if $webMenuLinkImage >= 800}imageFancy{else}imageFancy{/if}{else}webFancy{/if}" href="{$n}">Menu {if $placeInformation.menuLocation|@count > 1}{$smarty.foreach.menu.iteration }{/if}</a></li>
			{/foreach}
		</ul>
		<span><a href="/blank,submitMenu.html?placeId={$placeId}&categoryId={$placeInformation.categoryId}&locationId={$placeInformation.locationId}" class="web40Fancy">Want to submit another menu?</a></span><br />
	{elseif in_array($placeInformation.categoryId,$menuCategories) }
		<span>No menu,<a href="/blank,submitMenu.html?placeId={$placeId}&categoryId={$placeInformation.categoryId}&locationId={$placeInformation.locationId}" class="web40Fancy">submit one</a>?</span><br />
	{/if}
	<h3>Problem?</h3>
	<span>Something not right? <a href="/blank,report.html?placeId={$placeId}" id="reportPlace{$placeId}" class="web400x300Fancy" title="Something not right?">let us know</a></span><br />
	
	
	
</div>

{stuffImage var="imageList" place=$placeId noHtml=1}


<div id="otherContentContainer">
	<h3>Image Gallery</h3>
	{if $imageList|@count > 0}
		{if $imageList|@count > 4}
			<div id="imageGallery">      
				<div id="slider">    
					{*<img class="scrollButtons left" src="/images/leftarrow.png">*}
					<button class="scrollButtons left"><</button>
					<div style="overflow: hidden;" class="scroll">
					<div class="scrollContainer">
						{foreach from=$imageList key="m" item="n" name="images"}
							<div class="panel" id="panel_{math equation="x + y" x=$smarty.foreach.images.index y=1}">
								<div class="inside">
									<a href="{$n.imageLocation}" title="{$n.imageTitle}" rel="image_gallery"><img src="{$n.imageLocation}" alt="{$n.imageAlternateText}" class="auto" /></a>
									<h2>{$n.imageTitle|capitalize}</h2>
								</div>
							</div>
						{/foreach}
					</div>
					<span class="larger">Click on an image to view it larger</span><span class="newImage">Want to submit another <a href="/upload.html?placeId={$placeId}&categoryId={$placeInformation.categoryId}&locationId={$placeInformation.locationId}">image</a>?</span>
				</div>
				{*<img class="scrollButtons right" src="/images/rightarrow.png">*}
				<button class="scrollButtons right">></button>
				</div>
			</div>
		{else}
			<div id="imageGalleryPolaroid">
				{foreach from=$imageList key="m" item="n" name="images"}
					<div class="polaroid">
						<span class="tape tapeTL"></span>
						<a href="{$n.imageLocation}" title="{$n.imageTitle}" rel="image_gallery"><img src="{$n.imageLocation}" alt="{$n.imageAlternateText}" width="200px"/></a>
						<h3>{$n.imageTitle|capitalize}</h3>
						<span class="tape tapeBR"></span>
					</div>
				{/foreach}
			</div>
			<div class="clearFloat"></div>
			<span>Want to submit another <a href="/upload.html?placeId={$placeId}&categoryId={$placeInformation.categoryId}&locationId={$placeInformation.locationId}">image</a>?</span>
		{/if}
	{else}
		<span>No images, <a href="/upload.html?placeId={$placeId}&categoryId={$placeInformation.categoryId}&locationId={$placeInformation.locationId}">want to upload one?</a></span>
	{/if}
    
    {reviews placeId=$placeId var="reviews"}
    <div id="reviews">
    	<h3>Reviews</h3>
    	{if $reviews}
    		<p>Number of reviews: {$reviews.reviewInfo.count}<br />
    		Average Score: {$reviews.reviewInfo.average}/10
    		</p>
    		<ul>
				{foreach from=$reviews.reviews key="re" item="view"}
					<ul class="reviewContainer" id="reCon{$view.reviewId}">
						<li class="reviewInfo">{$view.addedBy} - {$view.dateAdded|date_format:"%a, %d %B %Y"}</li>
						<ul class="reviewData">
							<li><span>Score:</span> {$view.rating}/10</li>
							<li><span>Comment:</span> {$view.comment}</li>
						</ul>
						<li class="repReview"><a href="/blank,report.html?reviewId={$view.reviewId}" id="reportReview{$view.reviewId}" class="reportReview web400x300Fancy" title="Report this review">Report this review</a></li>
					</ul>
				{/foreach}
			</ul>
		{else}
			<span>Sorry, we havent got any reviews, want to give us <a href="/blank,submitReview.html?placeId={$placeId}&type=place&id={$placeId}&categoryId={$placeInformation.categoryId}&locationId={$placeInformation.locationId}" class="web500Fancy">one</a>?</span>
		{/if}
	</div>

</div>