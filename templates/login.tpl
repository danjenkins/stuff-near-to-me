<div id="loginForm">
	{if !$loggedIn }
		<form action="process.php" method="POST">
			<fieldset>
			<label for="i_user">Username</label><input type="text" id="i_user" name="user" maxlength="30" value="{$formValues.user}" /><br />
				{if $formErr.user}
					<span class="error">{$formErr.user}</span><br />
				{/if}
			<label for="i_pass">Password</label><input type="password" name="pass" id="i_pass" maxlength="30" value="{$formValues.pass}" /><br />
			{if $formErr.pass}
					<span class="error">{$formErr.pass}</span><br />
				{/if}
			<fieldset>
				<div>Remember me next time</div>
				<label for="i_remember" id="remember"><input type="checkbox" name="remember" id="i_remember" {if $formValues.remember}checked="checked"{/if}/>Yes please</label>
			</fieldset>
			<input type="hidden" name="redirectTo" value="/login.html" />
			<input type="hidden" name="sublogin" value="1" />
			<label>&nbsp;</label><button>Login</button>
			<div class="helpLinks">
				<a href="/register.html">Register</a><br />
				<a href="/forgotPassword.html">Forgot your password?</a><br />
			</div>
			</fieldset>
		</form>
	{else}
		Sorry you're already logged in. Do you want to logout?
	{/if}
</div>