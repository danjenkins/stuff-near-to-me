{getPlaceInfo var="placeInfo" placeId=$smarty.request.placeId categoryId=$smarty.request.categoryId locationId=$smarty.request.locationId}

<div id="submitReviewContainer">
	<h2>{$placeInfo.placeName|capitalize}</h2>
	<form id="review" method="post" action="/process/submitReview">
		{if !$loggedIn}<label for="user">Your name:</label>{/if}<input id="user" type="{if $loggedIn}hidden{else}text{/if}" name="user" value="{if $loggedIn}{$username}{/if}" />
		<input type="hidden" name="type" value="{$smarty.request.type}" />
		<input type="hidden" name="id" value="{$smarty.request.id}" />
		<input type="hidden" name="redirectTo" value="/" />
		<label>Rating:</label>
		<div id="rating">
		{section name=foo loop=10}
    		<input type="radio" value="{$smarty.section.foo.iteration}" class="star" name="rating" />
		{/section}
		</div>
		<label for="comment">Comment:</label>
		<textarea id="comment" name="comment">Submit something</textarea><br />
		<div id="submitButton"><button type="submit">Submit</button></div>
	</form>
</div>