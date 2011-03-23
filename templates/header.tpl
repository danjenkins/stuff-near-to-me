<div id="header">
	<div class="widthContainer">
		<a id="logoContainer" href="/" title="Stuff Near To Me"><h1><span>Stuff Near To.Me</span></h1></a>
		<div id="headerRight">
			<span class="user">
			{if $loggedIn}
				Hi {$username}, <a href="/process/user">Logout</a>
			{else}
				<a href="/login.html">Login/Register</a>
			{/if}
			</span>
			
			<form method="get" action="/search">
				<input type="text" name="search" id="searchFor" /><button type="submit" class="awesome orange large">Search</button>
			</form>
			{if $ie}<div id="ieWarning"><span>You appear to be using Internet Explorer.<br />Get a better experience of www.stuffnearto.me by using a different <a id="browsersLink" href="/blank,browsers.html" class="webFancy">browser</a><br /></span></div>{/if}
		</div>
		
		<div id="navContainer">
			<ul id="nav1">
				<li><a href="/findStuff.html" title="Find Stuff">Find Stuff</a></li>
				<li><a href="/submitStuff.html" title="Submit Stuff">Submit Stuff</a></li>
				<li><a href="/help.html" title="Help">Help</a></li>
			</ul>
		</div>
	</div>
	
</div>
<div class="clearFloat"></div>
{*<div id="header">
	<div class="widthContainer">
		<a id="logoContainer" title="Stuff Near To Me" href="/"> <!-- really an achor should be within the h1 -->
			<h1>Stuff Near To.Me</h1>
		</a>
		<div id="headerRight">
			<span class="user"><a href="/login.html">Login/Register</a></span>
			<form method="get" action="/search">
				<input id="searchFor" name="search">
				<button class="awesome orange large" type="submit">Search</button>
			</form>
			<div id="ieWarning">
				<span>You appear to be using Internet Explorer, why not have a look at some standards compliant <A class="webFancy" href="/blank,browsers.html" jQuery1270061453821="6">browsers</a>?<br />You can get a much better experience of this site in one of these browsers</span>
			</div>
		</div>
		<div id="navContainer">
			<ul id="nav1">
				<li><a title="Find Stuff" href="/findStuff.html">Find Stuff</a><li>
				<li><a title="Submit Stuff" href="/submitStuff.html">Submit Stuff</a><li>
				<li><a title="Help" href="/help.html">Help</a><li>
			</ul>
		</div>
	</div>
</div>
<div class="clearFloat"></div>*}