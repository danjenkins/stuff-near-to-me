<h2>Upload some photo's for us</h2>

{getPlaceInfo var="placeInfo" placeId=$smarty.request.placeId categoryId=$smarty.request.categoryId locationId=$smarty.request.locationId}

{stuffImage var="imageList" place=$smarty.request.placeId location=$smarty.request.locationId category=$smarty.request.categoryId noHtml=1}

<div class="uploadDescription niceBackground">
	{if $imageList|@count == 0}
		<p>We don't have any photo's for {$placeInfo.placeName} at the moment, you'd be doing us a real favour by uploading some.</p>
	{elseif $imageList|@count == 1}
		<p>We've only got the one photo of {$placeInfo.placeName} at the moment, you'd be doing us a real favour by uploading some more.</p>
	{else}
		<p>We've got some photo's for {$placeInfo.placeName} but if you think you can do better, upload some more! Have a look at what we've already got if you want.</p>
	{/if}
	{*upload button*}
	<button id="upload">Upload File</button><br /><span id="status" ></span>
</div>

<div class="niceBackground approvedImageContainer">
	{if $imageList|@count}
		<ul id="approvedImageList">
			{foreach from=$imageList key="k" item="v"}
				<li><a rel="approved_list" title="{$v.imageTitle}" href="{$v.imageLocation}"><img src="{$v.imageLocation}" alt="{$v.imageAlternateText}" title="{$v.imageTitle}" width="150px"/></a></li>
			{/foreach}
		</ul>
	{else}
		<p>No Current Images</p>
	{/if}
</div>

<div id="uploadContainer">  
	{*list of files*}  
	<ul id="files"></ul>
</div>


<div id="terms"><p>Once an image is uploaded it becomes the property of www.stuffnearto.me and can be used however we see fit. However if a place owner has reason to want it removed they have the right to get it removed</p></div>

