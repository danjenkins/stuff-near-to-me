<div>
	<h2>Contact Us</h2>
	<p>If you want to contact us regarding any issues or queries you may have about the site, please fill out the following form.<br />Give as much detail as you can in your comment, as this will aid a swift and accurate response from the admin team.</p>
	<p>Alternatively, you can send an email to {mailto subject="Comment From Stuff Near To Me Contact Us" address="admin@stuffnearto.me"}</p>
	
	<div id="contactContainer">
		<form action="/process/report" method="post" id="contactUs">
			<input type="hidden" name="type" value="general" />{*as its only contact we'll send general*}
			<input type="hidden" name="id" value="1" />
			{if $loggedIn}
				<div id="loggedInReport">
					<p>Hi {$username},<br />Write your comment below and we'll get back to you as soon as possible</p>
				</div>
				<input type="hidden" name="name" value="{$username}" />
				<input type="hidden" name="email" value="{$userInfo.email}" />
			{else}		
				<div id="emailContainer">
					<label for="email">Name: </label><input type="text" name="name" value="" autocomplete="no" /><br />
					<label for="email">Email: </label><input type="text" name="email" value="" autocomplete="no" />
				</div>
			{/if}
			<label>Comment</label><textarea id="comment" name="comment" autocomplete="no">Write your comment in here</textarea><br />
			<label>&nbsp;</label><button class="awesome">Send Comment</button>	
		</form>
	</div>

</div>