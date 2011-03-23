<div id="footer">
	<div class="widthContainer">
		<div class="footerBox">
			<h3>Stats</h3>
			<span>Welcome to <a href="/userInfo.html?user={$latestMember}">{$latestMember}</a>, our latest member</span>
			<dl>
				<dt>Now online:</dt>
				<dd>{$totalBrowsing}<br />({$guestsOnline} Guests, {$membersOnline} Members)</dd
				<dt>Total members:</dt>
				<dd>{$totalMembers}</dd>
				<dt>Total places:</dt>
				<dd>{$totalPlaces}</dd>		
			</dl>
		</div>
		<div class="footerBox">
			<h3>About</h3>
			<ul>
				<li><a href="/about.html" title="About Us">About</a></li>
				<li><a href="/contact.html" title="Contact Us">Contact Us</a></li>
				{*<li><a href="/blog" title="Stuff Near To Me Blog">Blog</a></li>*}
				<li><a href="/help.html" title="Help">Help</a></li>
			</ul>
		</div>
		<div class="footerBox">
			<h3>Previously viewed pages</h3>
			<ul>
				{foreach from=$visitedPages key="key" item="item" name="kkk"}
					{if $smarty.foreach.kkk.index <= 5}
						<li><a href="{$key}" title="{$key}">{$niceUrls[$key]|splitCamelCase|capitalize}</a></li>
					{/if}
				{/foreach}
			</ul>
		</div>
		{if $loggedIn}
			<div class="footerBox">
				<h3>User</h3>
				<ul>
					<li><a href="/editUser.html" title="Edit User">Edit</a></li>
					<li><a href="/userInfo.html" title="View User Information">View</a></li>
					{if $userInfo.userlevel >= 5}<a href="http://admin.stuffnearto.me" title="Administration">Admin</a>{/if}
				</ul>
			</div>
		{/if}
		<div id="bottomContainer">
			<p>The content on this website is user-generated and may not be completely accurate. If you believe some information to be in-accurate please <a href="contact.html">contact us</a></p>
			<p><a href="http://www.stuffnearto.me">Standard</a> - <a href="http://m.stuffnearto.me?permType=mobile" title="Mobile Site">Mobile Site</a></p>
			<p>Copyright &copy; 2010 - www.stuffnearto.me</p>
		</div>
	</div>
</div>