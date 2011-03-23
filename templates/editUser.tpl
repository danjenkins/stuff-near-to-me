{if $smart.session.useredit}
	{php}
		unset($_SESSION['useredit']);
	{/php}
	<h1>User Account Edit Success!</h1>
	<p><b>{$username}</b>, your account has been successfully updated <a href="/">Home</a></p>
{else}

{*
 * If user is not logged in, then do not display anything.
 * If user is logged in, then display the form to edit
 * account information, with the current email address
 * already in the field.
 *}
	{if $loggedIn}
		<h1>User Account Edit : {$username}</h1>
		{if $smarty.request.success}
			<p>Thanks for the update, it was successful!</p>
		{/if}
		<p>Change your details below, leave the details you don't want to change</p>
		<form action="/process/user" method="POST">
			<label>Current Password:</label><input type="password" name="curpass" maxlength="30" value="{$formValues.curpass}" />{if $formErr.curpass}<span>{$formErr.curpass}</span>{/if}<br />
			<label>New Password:</label><input type="password" name="newpass" maxlength="30" value="{$formValues.newpass}" />{if $formErr.newpass}<span>{$formErr.newpass}</span>{/if}<br />
			<label>Email:</label><input type="text" name="email" maxlength="50" value="{if $formValues == ''}{$userInfo.email}{else}{$formValues.email}{/if}" />{if $formErr.email}<span>{$formErr.email}</span>{/if}<br />
			<label>Location</label>{location id="locations" name="location" selected=$userInfo.location addBlankOption=true}<br />
			<input type="hidden" name="subedit" value="1" />
			<input type="hidden" name="redirectTo" value="/editUser.html" />
			<label>&nbsp;</label><button>Edit Account</button>
		</form>
		{if $formNumError > 0}
			<p>{$formNumError} error(s) found</p>
		{/if} 
	{else}
		<h1>Edit User</h1>
		<p>Sorry, but you're not logged in, if you'd like to edit your details please <a href="/login.html" title="Login">login</a> and try again</p>
	{/if}
{/if}