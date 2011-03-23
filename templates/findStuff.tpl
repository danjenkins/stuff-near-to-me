<div class="leftCont niceBackground">
	<p>Use the drop down box to pick either the area nearest to you or the area you intend on visiting. 
What will come up for you is a complete list of all pubs, clubs, restaurants and take away restaurants in the area you've selected. They are sorted by category for your convenience; just click on what you think looks interesting and enjoy your entertainment!</p>
</div>

	
<div class="rightCont">
	<h2>By location name</h2>
	<form method="get" id="searchPlaces" action="/process/location">
		<label for="locations">Place: </label>{location id="locations" name="location"}
		<button type="submit">Find Stuff</button>
	</form>
	
	<div id="geoLocation">
		<form method="get" action="/search.html">
			
			<div id="categoryContainer" style="display:none;"><label>Category: </label>{categories id="category" name="category"}</div>
			<input type="hidden" name="lat" />
			<input type="hidden" name="lon" />
			<span class="feedback"></span>
		</form>
	</div>
</div>