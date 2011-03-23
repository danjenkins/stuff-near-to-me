<div class="search">
	<h2>General Search</h2>
	<form method="get" action="/search">
		<input type="text" name="search" id="searchFor" /><br />
		<button type="submit">Search</button>
	</form>
</div>

<div class="search">
	<h2>By Location</h2>
	<form method="get" id="searchPlaces" action="/process/location">
		<label for="locations">Place: </label>{location id="locations" name="location"}
		<button type="submit">Find Stuff</button>
	</form>
</div>


<div id="geoLocation" class="mobile search">
	{*h2 gets added in via js if the phone is capable of it*}
	<form method="get" action="/search.html">
		<div id="categoryContainer" style="display:none;"><label>Category: </label>{categories id="category" name="category"}</div>
		<input type="hidden" name="lat" />
		<input type="hidden" name="lon" />
		<span class="feedback"></span>
	</form>
</div>