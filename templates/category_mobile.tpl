<h2>Category - {$categoryName|splitCamelCase|capitalize}</h2>

<ul id="category">
	{foreach from=$locationCategories key="k" item="v"}
		<li>
			<a href="/{$locationName}/{$categoryName}/{$v.placeId}/{$v.placeName|urlencode}">{$v.placeName}</a>
		</li>
	{/foreach}
</ul>