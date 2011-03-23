{if $loggedIn}

  <h1>Registered</h1>
  <p>We're sorry <b>{$userName}</b>, but you've already registered, <a>Log out?</a></p>
  
{else}
	{if $registered}
		{if $registered == 'yes'}
			<h2>Registered!</h2>
			<p>Thank you <b>{$regUsername}</b>, you may now <a href="/login.html">log in</a> and update your personal details</p>
		{elseif $registered == 'no'}
			<h2>Registration Failed</h2>
			<p>We're sorry, but an error has occurred and your registration for the username <b>{$regUsername}</b>, could not be completed.<br />Please try again at a later time.</p>
		{/if}
	{else}		
		<h1>Register</h1>
		{if $formErr|@count >= 1}<p class="errorMessage">You have {$formErr|@count} error{if {$formErr != 1}s{/if}</p>{/if}
		<form action="process.php" method="POST">
			<label>Username:</label><input type="text" name="user" maxlength="30" value="{$formValues.user}" /><br />
			{if $formErr.user}
				<span class="error">{$formErr.user}</span><br />
			{/if}
			<label>Email:</label><input type="text" name="email" maxlength="50" value="{$formValues.email}" /><br />
			{if $formErr.email}
				<span class="error">{$formErr.email}</span><br />
			{/if}
			<label>Password:</label><input type="password" name="pass" maxlength="30" value="" /><br />
			{if $formErr.pass}
				<span class="error">{$formErr.pass}</span><br />
			{/if}
			<label>Retype Password:</label><input type="password" name="pass2" maxlength="30" value="" /><br />
			{if $formErr.pass2}
				<span class="error">{$formErr.pass2}</span><br />
			{/if}
			<input type="hidden" name="subjoin" value="1" />
			<input type="hidden" name="redirectTo" value="register.html" />
			<label>&nbsp;</label><button>Join</button>
						
			{if $formErr|@count >= 1}<p class="errorMessage">You have {$formErr|@count} error{if {$formErr != 1}s{/if}</p>{/if}
		</form>
	{/if}
{/if}