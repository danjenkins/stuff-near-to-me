{*
 * UserInfo
 *
 * This page is for users to view their account information
 * with a link added for them to edit the information.
 *}

{* Requested Username error checking *}
{if $userAskedFor}

	{* Logged in user viewing own account *}
	{if $userAskedFor == $username}
		<h1>My Account</h1>
	{else}
		{* Visitor not viewing own account *}
		<h1>User Info</h1>
	{/if}

	{* Username *}
	<b>Username: {$userAskedForInfo.username}</b><br/>

	{**
	 * Note: when you add your own fields to the users table
	 * to hold more information, like homepage, location, etc.
	 * they can be easily accessed by the user info array.
	 *
	 * $session->user_info['location']; (for logged in users)
	 *
	 * ..and for this page,
	 *
	 * $req_user_info['location']; (for any user)
	 *}
	 <h2>User's Favourite Places</h2>
	 {if $userAskedForInfo.UserFavPlaces}
		<ul id="usersFavPlaces">
			{foreach from=$userAskedForInfo.UserFavPlaces key="k" item="v"}
		 		{getPlaceInfo var="info" placeId=$k}
		 		<li><a href="/{$info.locationName}/{$info.categoryName}/{$k}/{$info.placeName|urlencode}" title="{$info.placeName|splitCamelCase|capitalize}">{$info.placeName|splitCamelCase|capitalize}, {$info.locationName|capitalize}</a></li>
			{/foreach}
		</ul>
	{else}
		<p>{if $userAskedFor == $username}You don't{else}This user doesn't{/if} have any favourite places at the moment</p>
	{/if}

{else}
	<h1>View User Information</h1>
	<p>Sorry, but no username was asked for, if you'd like to view your details, or another member's details please either<a href="/login.html" title="Login">login</a> or pass in their username and try again</p>
{/if}


