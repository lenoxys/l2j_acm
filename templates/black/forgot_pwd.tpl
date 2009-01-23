{include file="header.tpl"}

		<div class="log_form">
			<div class="form">
				<h2>{$vm.forgot_pwd}</h2>
				<p>{$vm.forgot_pwd_text}</p>
			</div>
		</div>

		<div class="reg_form">
			<form name="create" method="POST" action="./">
				<h1>&nbsp;</h1>
{if isset($error)}
				<div class="error"><div class="error_container">{$error}</div></div>
{/if}
				<div class="field"><label>{$vm.account}</label><span class="field"><input type="text" id="Luser" name="Luser" value="{$vm.post_id}" autocomplete="off" maxlength="{$vm.account_length}" /></span></div>
				<div class="field"><label>{$vm.email}</label><span class="field"><input type="text" id="Lemail" name="Lemail" value="{$vm.post_email}" autocomplete="off"></span></div>
{if isset($image)}
				<div class="field"><label><img src="./img.php" id="L_image" onclick="reloadImage(this);"></label><span class="field"><input type="text" id="Limage" name="Limage" autocomplete="off"></span></div>
{/if}
				<input type="hidden" name="action" value="forgot_pwd">
				<hr class="clear">
				<input type="button" onClick="document.location='./'" class="button" value="{$vm.return}" />
				<input type="submit" class="button" value="{$vm.forgot_button}">
			</form>
		</div>
        
{include file="footer.tpl"}