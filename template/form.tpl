
			<form name="login" method="POST" action="./">
				<h2>{vm_exist_account}</h2>
				<!-- BEGIN error -->
				<div class="error"><div class="error_container">{error.ERROR}</div></div>
				<!-- END error -->
				<!-- BEGIN valid -->
				<div class="valid"><div class="valid_container">{valid.VALID}</div></div>
				<!-- END valid -->
				<div class="field"><label>{vm_account}</label><span class="field"><input type="text" id="Luser" name="Luser" autocomplete="off" maxlength="{vm_account_length}" /></span></div>
				<div class="field"><label>{vm_password}</label><span class="field"><input type="password" id="Lpwd" name="Lpwd" autocomplete="off" maxlength="{vm_password_length}" /></span></div>
				<hr class="clear">
				<input type="hidden" name="action" value="login">
				<input type="submit" class="button" value="{vm_login_button}">
			</form>
				<a href="?action=forgot_pwd">{vm_forgot_password}</a>

			<hr class="clear" />
			<h2>{vm_new_account}</h2>
			<p>{vm_new_account_text}</p>
			<input type="button" onClick="document.location='./?action=create'" class="button" value="{vm_create_button}" />