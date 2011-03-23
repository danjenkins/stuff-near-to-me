<h2>Welcome!</h2>

<div class="leftCont niceBackground">
	<p>Out for a night with your mates? Going on a date with your partner? Just fancy a take out? Not sure where to grab a bite to eat? This website is here to help you!</p>
	<p>This website is a complete list of all the pubs, clubs, restaurants and take-out restaurants in your local area. We give you the menus, the addresses, the contact details and user-written reviews to help you pick what best suits your needs.</p>
	<p>So pick your local area, pick your category, and see what you can find!</p>
	<form method="get" id="searchPlaces" action="/process/location">
		<label for="locations">Place: </label>{location id="locations" name="location"}
		<button type="submit">Find Stuff</button>
	</form>
</div>
<div class="rightCont">
	<div class="polaroid right">
		<span class="tape tapeTR"></span>
		<img src="/images/people.jpg" alt="Happy People" />
		<span class="tape tapeBL"></span>
	</div>
</div>

<div id="social">
	<a href="http://twitter.com/stuffneartome" target="_blank" title="Follow us at twitter.com"><img src="/images/twitter.png" alt="Twitter" width="130px"/>
	<span>Follow us on twitter, ask a question or suggest something new!</span></a>
	{*Twitter API trouble forced me to comment this out*}
	{*twitter var="twitter" tas="getLastUpdates"*}
	
	{*<div id="twitter">
		<h4>Recent tweets</h4>
		{foreach from=$twitter key="k" item="v"}
			
			<div id="{$v.id}" class="tweetContainer"><span class="date">{$v.created_at|date_format:"%a %d %b, %Y - %R"}</span><br /><span class="text">{$v.text|replace_uri}</span></div>
		{/foreach}
	</div>*}

</div>