		<div class="log_form">
			<div class="form">
				<h2>{vm_chg_pwd}</h2>
				<p>{vm_chg_pwd_text}</p>
			</div>
		</div>

		<div class="reg_form">
			<form name="create" method="POST" action="./">
				<h1>&nbsp;</h1>
				<!-- BEGIN error -->
				<div class="error"><div class="error_container">{error.ERROR}</div></div>
				<!-- END error -->
				<div class="field"><label>{vm_password}</label><span class="field"><input type="password" id="Lpwd" name="Lpwd" autocomplete="off" maxlength="{vm_password_length}" /></span></div>
				<div class="field"><label>{vm_email}</label><span class="field"><input type="text" id="Lemail" name="Lemail" autocomplete="off" /></span></div>
				<div class="field"><label>{vm_email2}</label><span class="field"><input type="text" id="Lemail2" name="Lemail2" autocomplete="off"></span></div>
				<input type="hidden" name="action" value="chg_email_form">
				<hr class="clear">
				<input type="button" onClick="document.location='./'" class="button" value="{vm_return}" />
				<input type="submit" class="button" value="{vm_chg_button}">
			</form>
		</div>