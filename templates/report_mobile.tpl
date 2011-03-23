<div id="reportContainer">
	<form action="/process/report" method="post" id="placeReport">
		<input type="hidden" name="type" value="{if $smarty.request.placeId}place{elseif $smarty.request.reviewId}review{/if}" />
		<input type="hidden" name="id" value="{if $smarty.request.placeId}{$smarty.request.placeId}{elseif $smarty.request.reviewId}{$smarty.request.reviewId}{/if}" />
		{if $loggedIn}
			<div id="loggedInReport">
				<p>Hi {$username},<br />write your comment below and we'll have a look at it as soon as we can</p>
			</div>
			<input type="hidden" name="name" value="{$username}" />
			<input type="hidden" name="email" value="{$userInfo.email}" />
		{else}		
			<div id="emailContainer">
				<label for="email">Name: </label><input type="text" name="name" value="" autocomplete="no" /><br />
				<label for="email">Email: </label><input type="text" name="email" value="" autocomplete="no" />
			</div>
		{/if}
		<textarea id="comment" name="comment" autocomplete="no">Write your comment in here</textarea><br />
		<button class="awesome">Send Comment</button>	
	</form>
</div>