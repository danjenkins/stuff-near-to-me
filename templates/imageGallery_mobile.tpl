{stuffImage var="imageList" place=$smarty.request.placeId noHtml=1}


<div id="otherContentContainer">
	<h2>Image Gallery</h2>
	{if $imageList|@count > 0}
		<div id="imageGallery">      
			{foreach from=$imageList key="m" item="n" name="images"}
				<div class="panel" id="panel_{math equation="x + y" x=$smarty.foreach.images.index y=1}">
					<div class="inside">
						<h3>{$n.imageTitle|capitalize}</h3>
						<img src="{$n.imageLocation}" alt="{$n.imageAlternateText}" class="auto" width="100%"/>
					</div>
				</div>
			{/foreach}
		</div>
	{/if}
</div>