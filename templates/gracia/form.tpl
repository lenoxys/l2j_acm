{include file="header.tpl"}

			<form name="login" method="POST" action="./">
				<h2>{$vm.exist_account}</h2>
{if isset($error)}
				<div class="error"><div class="error_container">{$error}</div></div>
{/if}
{if isset($valid)}
				<div class="valid"><div class="valid_container">{$valid}</div></div>
{/if}
				<div class="field"><label>{$vm.account}</label><span class="field"><input type="text" id="Luser" name="Luser" autocomplete="off" maxlength="{$vm.account_length}" /></span></div>
				<div class="field"><label>{$vm.password}</label><span class="field"><input type="password" id="Lpwd" name="Lpwd" autocomplete="off" maxlength="{$vm.password_length}" /></span></div>
{if isset($image)}
				<div class="field"><label><img src="./img.php" id="L_image" onclick="reloadImage(this);"></label><span class="field"><input type="text" id="Limage" name="Limage" autocomplete="off"></span></div>
{/if}
				<hr class="clear">
				<input type="hidden" name="action" value="login">
				<input type="submit" class="button" value="{$vm.login_button}">
			</form>
				<a href="?action=show_forget">{$vm.forgot_password}</a>

			<hr class="clear" />
			<h2>{$vm.new_account}</h2>
			<p>{$vm.new_account_text}</p>
			<input type="button" onClick="document.location='./?action=show_create'" class="button" value="{$vm.create_button}" />
            
{include file="footer.tpl"}