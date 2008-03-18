
			<div>
				<h2>{vm_new_account}</h2>
				<p>
				{vm_new_account_text}
				</p>
				<!-- BEGIN image -->
				<p>{vm_image_control_desc}</p>
				<!-- END image -->
			</div>

			<form name="create" method="POST" action="./">
				<h1>&nbsp;</h1>
				<!-- BEGIN error -->
				<div class="error"><div class="error_container">{error.ERROR}</div></div>
				<!-- END error -->
				<div class="field"><label>{vm_account}</label><span class="field"><input type="text" id="Luser" name="Luser" value="{post_id}" autocomplete="off" maxlength="{vm_account_length}" /></span><em>&#8226;</em></div>
				<div class="field"><label>{vm_password}</label><span class="field"><input type="password" id="Lpwd" name="Lpwd" autocomplete="off" maxlength="{vm_password_length}" /></span><em>&#8226;</em></div>
				<div class="field"><label>{vm_password2}</label><span class="field"><input type="password" id="Lpwd2" name="Lpwd2" autocomplete="off" maxlength="{vm_password_length}" /></span><em>&#8226;</em></div>
				<div class="field"><label>{vm_email}</label><span class="field"><input type="text" id="Lemail" name="Lemail" value="{post_email}" autocomplete="off"></span><em>&#8226;</em></div>
				<!-- BEGIN image -->
				<div class="field"><label><img src="./img.php" id="L_image" onclick="reloadImage(this);"></label><span class="field"><input type="text" id="Limage" name="Limage" autocomplete="off"></span><em>&#8226;</em></div>
				<!-- END image -->
				<input type="hidden" name="action" value="registration">
				<hr class="clear">
				<input type="button" onClick="document.location='./'" class="button" value="{vm_return}" />
				<input class="button" type="submit" value="{vm_create_button}">
			</form>