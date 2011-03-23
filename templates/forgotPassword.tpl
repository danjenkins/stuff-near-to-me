{**
 * ForgotPass.php
 *
 * This page is for those users who have forgotten their
 * password and want to have a new password generated for
 * them and sent to the email address attached to their
 * account in the database. The new password is not
 * displayed on the website for security purposes.
 *
 *}
{**
 * Forgot Password form has been submitted and no errors
 * were found with the form (the username is in the database)
 *}

{if $smarty.session.forgotpass}
   {**
    * New password was generated for user and sent to user's
    * email address.
    *}
   {if $smarty.session.forgotpass != ''}
      <h1>New Password Generated</h1>
      <p>Your new password has been generated and sent to the email <br>associated with your account.Want to go back <a href="/">home</a>?</p>
   {else}
		{**
		 * Email could not be sent, therefore password was not
		 * edited in the database.
		 *}

		<h1>New Password Failure</h1>
		<p>There was an error sending you the email with the new password,<br> so your password has not been changed. Want to go back <a href="/">home</a>?</p>
	{/if}
	{php}    
		unset($_SESSION['forgotpass']);
	{/php}

{else}
	{**
	 * Forgot password form is displayed, if error found
	 * it is displayed.
	 *}
	 
	<h1>Forgot Password</h1>
	<p>A new password will be generated for you and sent to the email address<br />associated with your account, all you have to do is enter your username.<br /><br />
	<form action="process.php" method="POST">
		<label>Username:</label><input type="text" name="user" maxlength="30" value="{$formValues.user}" />{if $formErr.user}<span>{$formErr.user}</span>{/if}<br />
		<input type="hidden" name="redirectTo" value="forgotPassword.html" />
		<input type="hidden" name="subforgot" value="1">
		<label>&nbsp;</label><button>Get New Password</button>
	</form>

{/if}
