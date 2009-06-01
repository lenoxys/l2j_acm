{include file="header.tpl"}

		<div class="log_form">
			<div class="form">
				<h2>{$vm.chg_pwd}</h2>
				<p>{$vm.chg_pwd_text}</p>
			</div>
		</div>

		<div class="reg_form">
			<form name="create" method="POST" action="./{$session_id}">
				<h1>&nbsp;</h1>
{if isset($error)}
				<div class="error"><div class="error_container">{$error}</div></div>
{/if}
				<div class="field"><label>{$vm.passwordold}</label><span class="field"><input type="password" id="Lpwdold" name="Lpwdold" autocomplete="off" maxlength="{$vm.password_length}" /></span></div>
				<div class="field"><label>{$vm.password}</label><span class="field"><input type="password" id="Lpwd" name="Lpwd" autocomplete="off" maxlength="{$vm.password_length}" /></span></div>
				<div class="field"><label>{$vm.password2}</label><span class="field"><input type="password" id="Lpwd2" name="Lpwd2" autocomplete="off" maxlength="{$vm.password_length}"></span></div>
				<input type="hidden" name="action" value="chg_pwd_form">
				<hr class="clear">
				<input type="button" onClick="document.location='./{$session_id}'" class="button" value="{$vm.return}" />
				<input type="submit" class="button" value="{$vm.chg_button}">
			</form>
		</div>
        
{include file="footer.tpl"}