		<div class="log_form">
			<div class="form">
				<h2>{vm_forgot_pwd}</h2>
				<p>{vm_forgot_pwd_text}</p>
			</div>
		</div>

		<div class="reg_form">
			<form name="create" method="POST" action="./">
				<h1>&nbsp;</h1>
				<!-- BEGIN error -->
				<div class="error"><div class="error_container">{error.ERROR}</div></div>
				<!-- END error -->
				<div class="field"><label>{vm_account}</label><span class="field"><input type="text" id="Luser" name="Luser" value="{post_id}" autocomplete="off" maxlength="{vm_account_length}" /></span></div>
				<div class="field"><label>{vm_email}</label><span class="field"><input type="text" id="Lemail" name="Lemail" value="{post_email}" autocomplete="off"></span></div>
				<!-- BEGIN image -->
				<div class="field"><label><img src="./img.php" id="L_image" onclick="reloadImage();"></label><span class="field"><input type="text" id="Limage" name="Limage" autocomplete="off"></span></div>
				<!-- END image -->
				<input type="hidden" name="action" value="forgot_pwd_form">
				<hr class="clear">
				<input type="button" onClick="document.location='./'" class="button" value="{vm_return}" />
				<input type="submit" class="button" value="{vm_forgot_button}">
			</form>
		</div>