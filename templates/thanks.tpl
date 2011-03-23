<h2>Thanks for that.</h2><br />

{if $smarty.get.task == 'newPlace'}
	<p>{if $username}{$username}, {/if}
		{if $smarty.get.success}
			Thanks for the update, we've added it to our list and we'll approve it as soon as possible. Do you have any photo's you'd like to <a href="/upload.html?placeId={$smarty.request.placeId}">share</a>?
		{elseif $smarty.get.error}
			Thanks for trying to give us an update, unfortunately something went wrong, but we've been alterted.
		{/if}
	</p>
{else}
	<p>Sorry{if $username} {$username}{/if}, I don't understand the task you've completed. Please email <a href="mailto:admin@stuffnearto.me">admin@stuffnearto.me</a></p>
{/if}